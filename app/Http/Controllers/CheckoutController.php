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
use Illuminate\Support\Facades\Log;

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
    
    // Get user addresses if logged in - THIS IS THE FIX
    $userAddresses = collect();
    if (Auth::check()) {
        $userAddresses = UserAddress::where('user_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

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

        // Save address if requested and not using saved address
        if ($request->has('save_address') && $request->save_address && !$request->saved_address_id) {
            $this->saveUserAddress($request);
        }

        // Create order
        $orderData = $this->prepareOrderData($request);
        $order = $this->orderService->createOrder($orderData, $cartItems);

            // Clear cart after successful order creation (with silent flag to prevent events)
            $this->cartService->clearCart(true);

            DB::commit();

            // Handle AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                if ($request->payment_method === 'payhere') {
                    return response()->json([
                        'success' => true,
                        'redirect' => route('checkout.payment', $order)
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'redirect' => route('checkout.success', $order)
                    ]);
                }
            }

            // Regular form submission
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
            
            Log::error('Checkout error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error processing checkout: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error processing checkout: ' . $e->getMessage());
        }
    }




    private function saveUserAddress(Request $request)
{
    UserAddress::create([
        'user_id' => Auth::id(),
        'name' => $request->customer_name,
        'phone' => $request->customer_phone,
        'address_line_1' => $request->address_line_1,
        'address_line_2' => $request->address_line_2,
        'city' => $request->city,
        'district' => $request->district,
        'postal_code' => $request->postal_code,
        'is_default' => UserAddress::where('user_id', Auth::id())->count() === 0,
        'is_active' => true,
    ]);
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

    // If using saved address
    if ($request->saved_address_id) {
        $address = UserAddress::find($request->saved_address_id);
        if ($address && $address->user_id == Auth::id()) {
            return [
                'user_id' => Auth::id(),
                'order_number' => $this->generateOrderNumber(),
                'status' => $request->payment_method === 'cod' ? 'processing' : 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                
                // Use saved address data
                'customer_name' => Auth::user()->name, 
                'customer_phone' => Auth::user()->phone,
                'customer_email' => Auth::user()->email,
                
                // Delivery information from saved address
                'delivery_address' => $address->address_line_1, // For your existing field
                'address_line_1' => $address->address_line_1,   // New field
                'address_line_2' => $address->address_line_2,   // New field
                'delivery_city' => $address->city,
                'city' => $address->city,                        // New field
                'district' => $address->district,                // New field
                'delivery_postal_code' => $address->postal_code,
                'delivery_notes' => $request->delivery_notes,
                
                // Add these for OrderService
                'saved_address_id' => $request->saved_address_id,
                
                // Totals
                'subtotal' => $cartSummary['subtotal'],
                'discount_amount' => $cartSummary['discount_amount'] ?? 0,
                'shipping_amount' => $cartSummary['shipping_amount'] ?? 0,
                'total_amount' => $cartSummary['total'],
            ];
        }
    }

    // Use form data
    return [
        'user_id' => Auth::id(),
        'order_number' => $this->generateOrderNumber(),
        'status' => $request->payment_method === 'cod' ? 'processing' : 'pending',
        'payment_method' => $request->payment_method,
        'payment_status' => 'pending',
        
        // Customer information
        'customer_name' => $request->customer_name,
        'customer_phone' => $request->customer_phone,
        'customer_email' => $request->customer_email ?? Auth::user()->email,
        
        // Delivery information - include both old and new field names
        'delivery_address' => $request->address_line_1,  // For backward compatibility
        'address_line_1' => $request->address_line_1,    // New field
        'address_line_2' => $request->address_line_2,    // New field
        'delivery_city' => $request->city,
        'city' => $request->city,                         // New field
        'district' => $request->district,                 // New field
        'delivery_postal_code' => $request->postal_code,
        'delivery_notes' => $request->delivery_notes,
        
        // Totals
        'subtotal' => $cartSummary['subtotal'],
        'discount_amount' => $cartSummary['discount_amount'] ?? 0,
        'shipping_amount' => $cartSummary['shipping_amount'] ?? 0,
        'total_amount' => $cartSummary['total'],
    ];
}
    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('ymdHis');
        $random = mt_rand(100, 999);
        
        return $prefix . $timestamp . $random;
    }
}