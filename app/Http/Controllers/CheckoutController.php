<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Services\CartService;
use App\Services\OrderService;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Display checkout page
     */
    public function index()
    {
        // Validate cart before checkout
        $cartItems = $this->cartService->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add some items before checkout.');
        }

        // Validate cart items for checkout
        $validationErrors = $this->cartService->validateCartForCheckout();
        if (!empty($validationErrors)) {
            return redirect()->route('cart.index')
                ->with('error', 'Cart validation failed: ' . implode(' ', $validationErrors));
        }

        $cartSummary = $this->cartService->getCartSummary();
        
        // Get user addresses if logged in
        $userAddresses = collect();

        return view('checkout.index', compact('cartItems', 'cartSummary', 'userAddresses'));
    }

    /**
     * Process checkout and create order
     */
    public function store(CheckoutRequest $request)
    {
        DB::beginTransaction();
        
        try {
            // Validate cart again
            $cartItems = $this->cartService->getCartItems();
            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            // Create order
            $orderData = $this->prepareOrderData($request);
            $order = $this->orderService->createOrder($orderData, $cartItems);

            // Clear cart after successful order creation
            $this->cartService->clearCart();

            DB::commit();

            // Redirect based on payment method
            if ($request->payment_method === 'payhere') {
                return redirect()->route('checkout.payment', $order)
                    ->with('success', 'Order created successfully. Please complete payment.');
            } else {
                // Cash on Delivery
                return redirect()->route('checkout.success', $order)
                    ->with('success', 'Order placed successfully! We will contact you for delivery.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error processing checkout: ' . $e->getMessage());
        }
    }

    /**
     * Display payment page for online payments
     */
  public function payment(Order $order)
{
    // Ensure user can access this order
    if (Auth::id() !== $order->user_id) {
        abort(403, 'Unauthorized access to order');
    }

    // Only allow payment for pending orders
    if ($order->payment_status !== 'pending') {
        return redirect()->route('checkout.success', $order)
            ->with('info', 'This order has already been processed.');
    }

    // For COD orders, no payment needed
    if ($order->payment_method === 'cod') {
        return redirect()->route('checkout.success', $order);
    }

    // Load relationships for display
    $order->load(['items.product', 'items.variantCombination']);

    return view('checkout.payment', compact('order'));
}

    /**
     * Display order success page
     */
    public function success(Order $order)
    {
        // Ensure user can access this order
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $order->load(['items.product', 'items.variantCombination']);

        return view('checkout.success', compact('order'));
    }

    /**
     * Prepare order data from request
     */
    private function prepareOrderData(CheckoutRequest $request)
    {
        $cartSummary = $this->cartService->getCartSummary();

        return [
            'user_id' => Auth::id(),
            'order_number' => $this->generateOrderNumber(),
            'status' => $request->payment_method === 'cod' ? 'payment_completed' : 'pending_payment',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'pending',
            
            // Customer Details
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            
            // Delivery Address
            'delivery_address' => $request->delivery_address,
            'delivery_city' => $request->delivery_city,
            'delivery_postal_code' => $request->delivery_postal_code,
            'delivery_notes' => $request->delivery_notes,
            
            // Order Totals
            'subtotal' => $cartSummary['subtotal'],
            'discount_amount' => $cartSummary['discount_amount'],
            'shipping_amount' => $cartSummary['shipping_amount'],
            'total_amount' => $cartSummary['total'],
            
            'notes' => $request->order_notes,
        ];
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        return 'CHB-' . date('Ymd') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);
    }
}