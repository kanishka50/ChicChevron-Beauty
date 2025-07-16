<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\OrderService;
use App\Mail\OrderStatusUpdate;
use Illuminate\Support\Facades\Mail;

class OrderController extends BaseController
{
    use AuthorizesRequests;
    
    protected $invoiceService;
    protected $orderService;

    public function __construct(InvoiceService $invoiceService, OrderService $orderService)
    {
        $this->middleware('auth');
        $this->invoiceService = $invoiceService;
        $this->orderService = $orderService;
    }

    /**
     * Display customer's order history
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Order::where('user_id', $user->id)
                     ->with(['items.product', 'items.productVariant']) // UPDATED
                     ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by order number
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->paginate(10);

        // Get status counts for filter tabs
        $statusCounts = [
            'all' => Order::where('user_id', $user->id)->count(),
            'payment_completed' => Order::where('user_id', $user->id)->where('status', 'payment_completed')->count(),
            'processing' => Order::where('user_id', $user->id)->where('status', 'processing')->count(),
            'shipping' => Order::where('user_id', $user->id)->where('status', 'shipping')->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => Order::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        return view('user.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display specific order details
     */
    public function show(Order $order)
    {
        // Ensure user can only view their own orders
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load([
            'items.product.brand',
            'items.productVariant', // UPDATED - simplified variant loading
            'statusHistory' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        // Check if order can be cancelled by customer
        $canCancel = $order->can_be_cancelled && 
                    in_array($order->status, ['payment_completed', 'processing']);

        // Check if order can be marked as completed by customer
        $canComplete = $order->status === 'shipping';

        // Check if customer can leave reviews (order must be completed)
        $canReview = $order->status === 'completed';

        return view('user.orders.show', compact('order', 'canCancel', 'canComplete', 'canReview'));
    }

    /**
     * Download order invoice
     */
    public function downloadInvoice(Order $order)
    {
        // Ensure user can only download their own order invoices
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized access to this order.');
        }

        try {
            $invoice = $this->invoiceService->generateInvoice($order);
            
            return response()->streamDownload(
                function () use ($invoice) {
                    echo $invoice['pdf_content'];
                },
                "invoice-{$order->order_number}.pdf",
                ['Content-Type' => 'application/pdf']
            );

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Unable to generate invoice at this time. Please try again later.');
        }
    }

    /**
     * Mark order as completed (customer received the order)
     */
    public function markComplete(Order $order)
    {
        // Ensure user can only complete their own orders
        if (Auth::id() !== $order->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this order.'
            ], 403);
        }

        if ($order->status !== 'shipping') {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be marked as completed at this time.'
            ], 400);
        }

        try {
            // For COD orders, mark payment as completed FIRST
            if ($order->payment_method === 'cod' && $order->payment_status === 'pending') {
                // Use a timestamp 1 second before current time
                $paymentTimestamp = now()->subSecond();
                
                $order->payment_status = 'completed';
                $order->payment_reference = 'COD-' . now()->timestamp;
                $order->save();
                
                // Add payment status history with earlier timestamp
                $order->addStatusHistory('payment_completed', 'Payment received via Cash on Delivery', null, $paymentTimestamp);
            }

            $this->orderService->updateOrderStatus(
                $order,
                'completed',
                'Order marked as completed by customer. Thank you for confirming delivery!',
                null // No admin ID since this is customer action
            );

            // Send completion notification email (optional)
            // Mail::to($order->user)->send(new OrderStatusUpdate($order, 'completed', 'Thank you for confirming delivery!'));

            return response()->json([
                'success' => true,
                'message' => 'Order marked as completed successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error completing order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request order cancellation
     */
    public function requestCancellation(Request $request, Order $order)
    {
        // Ensure user can only cancel their own orders
        if (Auth::id() !== $order->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this order.'
            ], 403);
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if (!$order->can_be_cancelled) {
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be cancelled at this time.'
            ], 400);
        }

        try {
            // For now, we'll just add a note requesting cancellation
            // In a full implementation, you might want a separate cancellation requests table
            $order->addStatusHistory(
                $order->status,
                'Customer requested cancellation: ' . $request->reason
            );

            // Notify admin about cancellation request
            // You could send an email or create a notification for admin

            return response()->json([
                'success' => true,
                'message' => 'Cancellation request submitted successfully. Our team will review it shortly.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting cancellation request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder - add all items from previous order to cart
     */
    public function reorder(Order $order)
    {
        // Ensure user can only reorder their own orders
        if (Auth::id() !== $order->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this order.'
            ], 403);
        }

        try {
            $addedItems = 0;
            $unavailableItems = [];

            foreach ($order->items as $item) {
                // Check if product is still available
                $product = $item->product;
                $productVariant = $item->productVariant; // UPDATED

                if (!$product || !$product->is_active) {
                    $unavailableItems[] = $item->product_name;
                    continue;
                }

                // Check if variant is still active
                if ($productVariant && !$productVariant->is_active) {
                    $unavailableItems[] = $item->product_name . ' (' . $productVariant->display_name . ')';
                    continue;
                }

                // Check stock availability - UPDATED
                if ($productVariant) {
                    $availableStock = $productVariant->available_stock;
                } else {
                    // For products without variants (shouldn't happen in new system)
                    $availableStock = 0;
                }

                if ($availableStock < $item->quantity) {
                    $unavailableItems[] = $item->product_name;
                    continue;
                }

                // Add to cart using CartService (you'll need to inject CartService)
                // $this->cartService->addToCart($product, $productVariant, $item->quantity);

                $addedItems++;
            }

            $message = "{$addedItems} items added to cart";
            if (!empty($unavailableItems)) {
                $message .= ". Some items are no longer available: " . implode(', ', $unavailableItems);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'added_items' => $addedItems,
                'unavailable_items' => count($unavailableItems)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding items to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track order status (for AJAX requests)
     */
    public function trackOrder(Order $order)
    {
        // Ensure user can only track their own orders
        if (Auth::id() !== $order->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this order.'
            ], 403);
        }

        $statusHistory = $order->statusHistory()
                              ->orderBy('created_at', 'asc')
                              ->get()
                              ->map(function ($history) use ($order) {
                                  return [
                                      'status' => $history->status,
                                      'status_label' => ucfirst(str_replace('_', ' ', $history->status)),
                                      'comment' => $history->comment,
                                      'date' => $history->created_at->format('M d, Y H:i'),
                                      'is_current' => $history->status === $order->status
                                  ];
                              });

        return response()->json([
            'success' => true,
            'order_number' => $order->order_number,
            'current_status' => $order->status,
            'current_status_label' => ucfirst(str_replace('_', ' ', $order->status)),
            'status_history' => $statusHistory,
            'estimated_delivery' => $this->getEstimatedDelivery($order),
            'can_cancel' => $order->can_be_cancelled,
            'can_complete' => $order->status === 'shipping'
        ]);
    }

    /**
     * Get estimated delivery date
     */
    protected function getEstimatedDelivery($order)
    {
        if ($order->status === 'completed') {
            return 'Delivered';
        }

        if ($order->status === 'cancelled') {
            return 'Cancelled';
        }

        // Calculate estimated delivery based on status and order date
        $estimatedDays = [
            'payment_completed' => 5, // 2 days processing + 3 days delivery
            'processing' => 3,        // 3 days delivery
            'shipping' => 1,          // 1 day remaining
        ];

        $daysToAdd = $estimatedDays[$order->status] ?? 5;
        $estimatedDate = $order->created_at->addDays($daysToAdd);

        return $estimatedDate->format('M d, Y');
    }

    /**
     * Get order statistics for user dashboard
     */
    public function getOrderStatistics()
    {
        $user = Auth::user();
        
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'completed_orders' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
            'pending_orders' => Order::where('user_id', $user->id)
                                   ->whereIn('status', ['payment_completed', 'processing', 'shipping'])
                                   ->count(),
            'total_spent' => Order::where('user_id', $user->id)
                                 ->where('status', '!=', 'cancelled')
                                 ->sum('total_amount'),
            'recent_order' => Order::where('user_id', $user->id)
                                  ->orderBy('created_at', 'desc')
                                  ->first()
        ];

        return response()->json($stats);
    }
}