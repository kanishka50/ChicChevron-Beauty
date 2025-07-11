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

        // For PayHere, the actual payment confirmation comes via webhook
        // This is just the return URL
        return view('checkout.payment-processing', compact('order'));
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
}