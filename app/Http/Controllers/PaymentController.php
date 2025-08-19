<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderStatusUpdate;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle successful payment return from PayHere
     */
    public function success(Request $request, Order $order)
    {
        // Verify user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if payment was already completed
        if ($order->payment_status === 'completed') {
            return redirect()->route('checkout.success', $order)
                ->with('success', 'Payment already processed successfully!');
        }

        // CRITICAL: Check if webhook was received
        if (!$order->isWebhookReceived()) {
            // Check timeout
            if ($order->payment_initiated_at && 
                $order->payment_initiated_at->addMinutes(5)->isPast()) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Payment session expired. Please try again.');
            }

            // Show processing page to wait for webhook
            return view('checkout.payment-processing', compact('order'));
        }

        // Payment verified, redirect to success
        return redirect()->route('checkout.success', $order);
    }

    /**
     * Handle PayHere webhook notification
     */
    public function webhook(Request $request)
    {
        // Log raw webhook
        DB::table('webhook_calls')->insert([
            'merchant_id' => $request->input('merchant_id'),
            'status_code' => $request->input('status_code'),
            'md5_signature' => $request->input('md5sig'),
            'ip_address' => $request->ip(),
            'raw_payload' => json_encode($request->all()),
            'created_at' => now(),
        ]);

        Log::info('PayHere webhook received', [
            'data' => $request->all(),
            'ip' => $request->ip(),
        ]);

        try {
            // Extract webhook data
            $merchantId = $request->input('merchant_id');
            $orderId = $request->input('order_id');
            $payhereAmount = $request->input('payhere_amount');
            $payhereCurrency = $request->input('payhere_currency');
            $statusCode = $request->input('status_code');
            $md5sig = $request->input('md5sig');
            $paymentId = $request->input('payment_id');
            $customToken = $request->input('custom_1'); // Our payment token

            // Verify merchant ID
            if ($merchantId !== config('payhere.merchant_id')) {
                Log::error('Invalid merchant ID in webhook', [
                    'received' => $merchantId,
                    'expected' => config('payhere.merchant_id')
                ]);
                return response('Invalid merchant', 400);
            }

            // Find order
            $order = Order::where('order_number', $orderId)->first();
            if (!$order) {
                Log::error('Order not found for webhook', ['order_id' => $orderId]);
                return response('Order not found', 404);
            }

            // Verify MD5 signature
            $merchantSecret = config('payhere.merchant_secret');
            $localMd5sig = strtoupper(md5(
                $merchantId . 
                $orderId . 
                $payhereAmount . 
                $payhereCurrency . 
                $statusCode . 
                strtoupper(md5($merchantSecret))
            ));

            if (!hash_equals($localMd5sig, $md5sig)) {
                Log::error('Invalid MD5 signature in webhook', [
                    'expected' => $localMd5sig,
                    'received' => $md5sig,
                    'order_id' => $orderId
                ]);
                return response('Invalid signature', 400);
            }

            // CRITICAL: Verify payment token
            if ($customToken && !$order->isPaymentTokenValid($customToken)) {
                Log::error('Invalid payment token in webhook', [
                    'order_id' => $orderId,
                    'token' => $customToken
                ]);
                return response('Invalid token', 400);
            }

            // Verify amount matches
            $expectedAmount = number_format($order->total_amount, 2, '.', '');
            if ($payhereAmount !== $expectedAmount) {
                Log::error('Amount mismatch in webhook', [
                    'order_id' => $orderId,
                    'expected' => $expectedAmount,
                    'received' => $payhereAmount
                ]);
                return response('Amount mismatch', 400);
            }

            // Process based on status code
            if ($statusCode == '2') { // Success
                if ($order->payment_status !== 'completed') {
                    // Update order
                    $order->payment_status = 'completed';
                    $order->payment_reference = $paymentId ?: 'PAYHERE-' . now()->timestamp;
                    $order->status = 'payment_completed';
                    $order->markPaymentAsVerified(); // This sets webhook_received_at
                    $order->save();

                    // Add status history
                    $order->addStatusHistory('payment_completed', 'Payment confirmed by PayHere webhook');

                    // Confirm inventory
                    $inventoryService = app(\App\Services\InventoryService::class);
                    foreach ($order->items as $item) {
                        $inventoryService->confirmReservedStock(
                            $item->product_id,
                            $item->product_variant_id,
                            $item->quantity,
                            'order',
                            $order->id
                        );
                    }

                    // Send email
                    if ($order->user && $order->user->email) {
                        try {
                            Mail::to($order->user->email)
                                ->send(new OrderStatusUpdate($order, 'payment_completed', 'Payment confirmed successfully'));
                        } catch (\Exception $e) {
                            Log::error('Failed to send payment email', ['error' => $e->getMessage()]);
                        }
                    }

                    Log::info('Payment completed via webhook', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number
                    ]);
                }
            } else {
                // Payment failed
                if ($order->payment_status !== 'failed') {
                    $order->payment_status = 'failed';
                    $order->status = 'cancelled';
                    $order->save();

                    // Release inventory
                    app(OrderService::class)->releaseStockForOrder($order);
                }

                Log::warning('Payment failed via webhook', [
                    'order_id' => $order->id,
                    'status_code' => $statusCode
                ]);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('PayHere webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
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

        // Increment verification attempts
        $order->incrementVerificationAttempts();

        return response()->json([
            'payment_status' => $order->payment_status,
            'order_status' => $order->status,
            'is_completed' => $order->payment_status === 'completed',
            'webhook_received' => $order->isWebhookReceived(),
            'attempts' => $order->verification_attempts,
            'max_attempts' => 3
        ]);
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

        // Release inventory
        app(OrderService::class)->releaseStockForOrder($order);

        return redirect()->route('checkout.index')
            ->with('error', 'Payment was cancelled. Your order has been cancelled.');
    }
}