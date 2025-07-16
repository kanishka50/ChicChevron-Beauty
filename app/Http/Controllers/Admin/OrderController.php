<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $orderService;
    protected $invoiceService;

    public function __construct(OrderService $orderService, InvoiceService $invoiceService)
    {
        $this->orderService = $orderService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display orders listing with filters
     */
    public function index(Request $request)
    {
        $filters = [
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'search' => $request->search,
        ];

        $orders = $this->orderService->getOrdersWithFilters($filters)->paginate(20);

        // Get status counts for filter badges
        $statusCounts = [
            'all' => Order::count(),
            'payment_completed' => Order::where('status', 'payment_completed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipping' => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts', 'filters'));
    }

    /**
     * Display order details
     */
    public function show(Order $order)
    {
        $order->load([
    'user',
    'items.product.brand',
    'items.productVariant',
    'statusHistory.changedBy'
]);

        // Calculate profit margins
        $totalCost = $order->items->sum(function ($item) {
            return $item->cost_price * $item->quantity;
        });
        
        $totalRevenue = $order->items->sum('total_price');
        $grossProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        $profitAnalysis = [
            'total_cost' => $totalCost,
            'total_revenue' => $totalRevenue,
            'gross_profit' => $grossProfit,
            'profit_margin' => $profitMargin
        ];

        return view('admin.orders.show', compact('order', 'profitAnalysis'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:processing,shipping,completed,cancelled',
        'comment' => 'nullable|string|max:500',
        'notify_customer' => 'sometimes|boolean',
        
    ]);

    try {

        // Use the OrderService to update status
        $result = $this->orderService->updateOrderStatus(
            $order,
            $request->status,
            $request->comment,
            Auth::guard('admin')->id()
        );

        // Check if request wants JSON (from Accept header or ajax request)
        if ($request->wantsJson() || $request->ajax() || $request->acceptsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!',
                'new_status' => $order->fresh()->status,
                'status_label' => ucfirst(str_replace('_', ' ', $order->fresh()->status)),
                'order' => [
                        'status' => $order->fresh()->status,
                        'status_label' => ucfirst(str_replace('_', ' ', $order->fresh()->status)),
                        'status_color' => $this->getStatusColor($order->fresh()->status),
                        'can_be_cancelled' => in_array($order->fresh()->status, ['payment_completed', 'processing']),
                        'can_be_completed' => $order->fresh()->status === 'shipping'
                    ]
            ]);
        }

        // For non-AJAX requests
        return redirect()->route('admin.orders.show', $order)
                       ->with('success', 'Order status updated successfully! Customer has been notified.');

    } catch (\Exception $e) {
             Log::error('Order status update failed', [
            'order_id' => $order->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Return JSON error for AJAX requests
        if ($request->wantsJson() || $request->ajax() || $request->acceptsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage()
            ], 500);
        }

        // For non-AJAX requests
        return redirect()->back()
                       ->with('error', 'Error updating order status: ' . $e->getMessage());
    }
}



/**
 * Helper method to get status color
 */
  private function getStatusColor($status)
    {
        $colors = [
            'payment_completed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-yellow-100 text-yellow-800',
            'shipping' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $colors[$status] ?? 'bg-gray-100 text-gray-800';
    }
    /**
     * Generate and download invoice
     */
    public function generateInvoice(Order $order)
    {
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
                           ->with('error', 'Error generating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Bulk status update
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:processing,shipping,completed,cancelled',
            'comment' => 'nullable|string|max:500'
        ]);

        $successCount = 0;
        $failedOrders = [];

        foreach ($request->order_ids as $orderId) {
            try {
                $order = Order::findOrFail($orderId);
                $this->orderService->updateOrderStatus(
                    $order,
                    $request->status,
                    $request->comment,
                    Auth::guard('admin')->id()
                );
                $successCount++;
            } catch (\Exception $e) {
                $failedOrders[] = $order->order_number ?? $orderId;
            }
        }

        $message = "{$successCount} orders updated successfully!";
        if (!empty($failedOrders)) {
            $message .= " Failed to update: " . implode(', ', $failedOrders);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'updated_count' => $successCount,
            'failed_count' => count($failedOrders)
        ]);
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $filters = [
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'search' => $request->search,
        ];

        $orders = $this->orderService->getOrdersWithFilters($filters)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Status',
                'Payment Method',
                'Subtotal',
                'Discount',
                'Total Amount',
                'Items Count',
                'Order Date',
                'Shipped Date',
                'Completed Date'
            ]);

            // Data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->shipping_name,
                    $order->user->email ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $order->status)),
                    strtoupper($order->payment_method),
                    number_format($order->subtotal, 2),
                    number_format($order->discount_amount, 2),
                    number_format($order->total_amount, 2),
                    $order->items->count(),
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->shipped_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $order->completed_at?->format('Y-m-d H:i:s') ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get order statistics for AJAX requests
     */
    public function statistics()
    {
        $stats = $this->orderService->getOrderStatistics();
        
        return response()->json($stats);
    }

    /**
     * Search orders for autocomplete
     */
    public function search(Request $request)
    {
        $term = $request->get('term');
        
        $orders = Order::where('order_number', 'like', "%{$term}%")
                      ->orWhere('shipping_name', 'like', "%{$term}%")
                      ->orWhere('shipping_phone', 'like', "%{$term}%")
                      ->orWhereHas('user', function ($query) use ($term) {
                          $query->where('email', 'like', "%{$term}%");
                      })
                      ->limit(10)
                      ->get(['id', 'order_number', 'shipping_name', 'total_amount', 'status']);

        return response()->json($orders);
    }

    /**
     * Mark order as priority (if needed for future enhancement)
     */
    public function markPriority(Order $order)
    {
        // Future enhancement: Add priority flag to orders table
        return response()->json(['success' => true]);
    }

    /**
     * Add internal notes to order
     */
    public function addNote(Request $request, Order $order)
    {
        $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        $order->addStatusHistory(
            $order->status,
            'Internal Note: ' . $request->note,
            Auth::guard('admin')->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully!'
        ]);
    }
}