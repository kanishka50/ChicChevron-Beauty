<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
            abort(403);
        }

        // Check if payment was already processed
        if ($order->payment_status === 'completed') {
            return redirect()->route('checkout.success', $order)
                ->with('success', 'Payment already processed successfully!');
        }

        // Show a processing page that checks payment status
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

        // Release inventory
        app(OrderService::class)->releaseStockForOrder($order);

        return redirect()->route('checkout.index')
            ->with('error', 'Payment was cancelled. Your order has been cancelled.');
    }

    /**
     * Handle PayHere webhook notification
     */
    public function webhook(Request $request)
    {
        Log::info('PayHere webhook received', [
            'data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        try {
            // Validate the webhook data
            $merchantId = $request->input('merchant_id');
            $orderId = $request->input('order_id');
            $payhereAmount = $request->input('payhere_amount');
            $payhereCurrency = $request->input('payhere_currency');
            $statusCode = $request->input('status_code');
            $md5sig = $request->input('md5sig');

            // Verify merchant ID
            if ($merchantId !== config('payhere.merchant_id')) {
                Log::error('Invalid merchant ID in webhook');
                return response('Invalid merchant', 400);
            }

            // Find the order
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

            if ($localMd5sig !== $md5sig) {
                Log::error('Invalid MD5 signature in webhook', [
                    'expected' => $localMd5sig,
                    'received' => $md5sig
                ]);
                return response('Invalid signature', 400);
            }

            // Process based on status code
            if ($statusCode == '2') { // Success
                if ($order->payment_status !== 'completed') {
                    // Update order
                    $order->payment_status = 'completed';
                    $order->payment_reference = $request->input('payment_id', 'PAYHERE-' . now()->timestamp);
                    $order->status = 'payment_completed';
                    $order->save();

                    // Add status history
                    $order->addStatusHistory('payment_completed', 'Payment confirmed by PayHere webhook');

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

                    // Send payment confirmation email
                    if ($order->user && $order->user->email) {
                        try {
                            Mail::to($order->user->email)
                                ->send(new OrderStatusUpdate($order, 'payment_completed', 'Payment confirmed successfully'));
                            
                            Log::info('Payment completion email sent', [
                                'order_id' => $order->id,
                                'order_number' => $order->order_number,
                                'email' => $order->user->email
                            ]);
                        } catch (\Exception $emailException) {
                            Log::error('Failed to send payment completion email', [
                                'order_id' => $order->id,
                                'error' => $emailException->getMessage()
                            ]);
                        }
                    }

                    Log::info('Payment completed via webhook', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number
                    ]);
                }
            } else {
                // Payment failed
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
     * Check payment status (AJAX) - for the processing page
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

    // DELETE THE confirmPayment METHOD - NO LONGER NEEDED
    // DELETE THE webhookDebug METHOD - NO LONGER NEEDED
}