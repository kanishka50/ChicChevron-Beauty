<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use App\Services\OrderService;  // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;  // Add this import

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle successful payment return
     */
    // public function success(Request $request, Order $order)
    // {
    //     // Verify user owns this order
    //     if ($order->user_id !== Auth::id()) {
    //         abort(403);
    //     }

    //     // Check if payment was already processed
    //     if ($order->payment_status === 'completed') {
    //         return redirect()->route('checkout.success', $order)
    //             ->with('success', 'Payment already processed successfully!');
    //     }

    //     // For PayHere, the actual payment confirmation comes via webhook
    //     // This is just the return URL
    //     return view('checkout.payment-processing', compact('order'));
    // }


    public function success(Request $request, Order $order)
{
    // Verify user owns this order
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    // Check if payment was already processed
    if ($order->payment_status === 'completed') {
        return redirect()->route('checkout.success', $order)
            ->with('success', 'Payment already processed successfully!');
    }

    // Show the payment success page with status checking
    return view('checkout.payment-success', compact('order'));
}

    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request, Order $order)
    {
        // Verify user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Update order status
        $order->status = 'cancelled';
        $order->payment_status = 'failed';
        $order->save();

        // Add status history
        $order->addStatusHistory('cancelled', 'Payment cancelled by user');

        // Release inventory using OrderService
        app(OrderService::class)->releaseStockForOrder($order);

        return redirect()->route('checkout.index')
            ->with('error', 'Payment was cancelled. Your order has been cancelled.');
    }

    /**
     * Handle PayHere webhook notification
     */
    public function webhook(Request $request)
    {
        Log::info('PayHere webhook received', $request->all());

        try {
            $result = $this->paymentService->handleCallback('payhere', $request->all());
            
            if ($result) {
                return response('OK', 200);
            }
            
            return response('Payment processing failed', 400);
            
        } catch (\Exception $e) {
            Log::error('PayHere webhook error', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);
            
            return response('Error processing webhook', 500);
        }
    }

    /**
     * Check payment status (AJAX)
     */
    public function checkStatus(Request $request, Order $order)
    {
        // Verify user owns this order
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'payment_status' => $order->payment_status,
            'order_status' => $order->status,
            'is_completed' => $order->payment_status === 'completed'
        ]);
    }


    /**
 * Manually confirm payment (for testing with ngrok)
 */
public function confirmPayment(Request $request, Order $order)
{
    // Check if user owns this order
    if ($order->user_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Check if already completed
    if ($order->payment_status === 'completed') {
        return response()->json(['message' => 'Payment already completed'], 200);
    }

    // Update the order
    $order->payment_status = 'completed';
    $order->payment_reference = 'MANUAL-' . now()->timestamp;
    $order->status = 'payment_completed';
    $order->save();
    
    // Add status history
    $order->addStatusHistory('payment_completed', 'Payment manually confirmed');
    
    // Confirm inventory
    $inventoryService = app(\App\Services\InventoryService::class);
    foreach ($order->items as $item) {
        $inventoryService->confirmReservedStock(
            $item->product_id,
            $item->variant_combination_id,
            $item->quantity,
            'order',
            $order->id
        );
    }
    
    Log::info('Payment manually confirmed', [
        'order_id' => $order->id,
        'order_number' => $order->order_number
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Payment confirmed successfully'
    ]);
}

/**
 * Enhanced webhook with better logging
 */
public function webhookDebug(Request $request)
{
    Log::info('=== PAYHERE WEBHOOK DEBUG ===');
    Log::info('Method: ' . $request->method());
    Log::info('Headers:', $request->headers->all());
    Log::info('All Data:', $request->all());
    Log::info('Raw Content: ' . $request->getContent());
    
    // Call the original webhook method
    return $this->webhook($request);
}
}