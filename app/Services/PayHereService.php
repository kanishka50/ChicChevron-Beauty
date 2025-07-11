<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayHereService
{
    protected $merchantId;
    protected $merchantSecret;
    protected $checkoutUrl;
    protected $currency;

    public function __construct()
    {
        $this->merchantId = config('payhere.merchant_id');
        $this->merchantSecret = config('payhere.merchant_secret');
        $this->checkoutUrl = config('payhere.checkout_url');
        $this->currency = config('payhere.currency');
    }

    /**
     * Create a payment request for PayHere
     */
    public function createPayment(Order $order)
    {
        $hash = $this->generateHash($order);
        
        $paymentData = [
            'merchant_id' => $this->merchantId,
            'return_url' => route('checkout.payment.success', $order),
            'cancel_url' => route('checkout.payment.cancel', $order),
            'notify_url' => route('webhooks.payhere'),
            
            // Order details
            'order_id' => $order->order_number,
            'items' => 'Order #' . $order->order_number,
            'currency' => $this->currency,
            'amount' => number_format($order->total_amount, 2, '.', ''),
            
            // Customer details
            'first_name' => $order->shipping_name,
            'last_name' => '',
            'email' => $order->customer_email ?? $order->user->email,
            'phone' => $order->shipping_phone,
            'address' => $order->shipping_address_line_1,
            'city' => $order->shipping_city,
            'country' => 'Sri Lanka',
            
            // Hash
            'hash' => $hash,
        ];

        return [
            'success' => true,
            'checkout_url' => $this->checkoutUrl,
            'payment_data' => $paymentData
        ];
    }

    /**
     * Generate hash for payment security
     */
    protected function generateHash(Order $order)
    {
        $merchantId = $this->merchantId;
        $orderId = $order->order_number;
        $amount = number_format($order->total_amount, 2, '.', '');
        $currency = $this->currency;
        $merchantSecret = $this->merchantSecret;
        
        $hash = strtoupper(
            md5(
                $merchantId . 
                $orderId . 
                $amount . 
                $currency . 
                strtoupper(md5($merchantSecret))
            )
        );
        
        return $hash;
    }

    /**
     * Handle payment callback from PayHere
     */
    public function handleCallback(array $data)
    {
        // Verify the callback is legitimate
        if (!$this->verifyCallback($data)) {
            Log::error('PayHere callback verification failed', $data);
            return false;
        }

        // Find the order
        $order = Order::where('order_number', $data['order_id'])->first();
        
        if (!$order) {
            Log::error('Order not found for PayHere callback', ['order_id' => $data['order_id']]);
            return false;
        }

        // Update order based on payment status
        switch ($data['status_code']) {
            case '2': // Success
                $order->payment_status = 'completed';
                $order->payment_reference = $data['payment_id'] ?? null;
                $order->status = 'payment_completed';
                $order->save();
                
                // Add status history
                $order->addStatusHistory('payment_completed', 'Payment received via PayHere');
                
                // Confirm inventory for each order item
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
                
                // Send confirmation email
                // TODO: Implement email service
                
                return true;
                
            case '0': // Pending
                $order->payment_status = 'pending';
                $order->save();
                return true;
                
            case '-1': // Canceled
            case '-2': // Failed
            case '-3': // Chargedback
                $order->payment_status = 'failed';
                $order->status = 'cancelled';
                $order->save();
                
                $order->addStatusHistory('cancelled', 'Payment failed or cancelled');
                
                // Release inventory for each order item
                $inventoryService = app(\App\Services\InventoryService::class);
                foreach ($order->items as $item) {
                    $inventoryService->releaseReservedStock(
                        $item->product_id,
                        $item->variant_combination_id,
                        $item->quantity,
                        'order',
                        $order->id
                    );
                }
                
                return false;
        }
        
        return false;
    }

    /**
     * Verify callback data
     */
    protected function verifyCallback(array $data)
    {
        $merchantId = $data['merchant_id'] ?? '';
        $orderId = $data['order_id'] ?? '';
        $paymentId = $data['payment_id'] ?? '';
        $payhereAmount = $data['payhere_amount'] ?? '';
        $payhereCurrency = $data['payhere_currency'] ?? '';
        $statusCode = $data['status_code'] ?? '';
        $md5sig = $data['md5sig'] ?? '';
        
        $merchantSecret = $this->merchantSecret;
        
        $localMd5sig = strtoupper(
            md5(
                $merchantId . 
                $orderId . 
                $paymentId . 
                $payhereAmount . 
                $payhereCurrency . 
                $statusCode . 
                strtoupper(md5($merchantSecret))
            )
        );
        
        return $localMd5sig === $md5sig;
    }

    /**
     * Verify payment status with PayHere API
     */
    public function verifyPayment($paymentReference)
    {
        // This would call PayHere's API to verify payment status
        // Implementation depends on PayHere's API documentation
        
        try {
            // Get access token first
            $token = $this->getAccessToken();
            
            if (!$token) {
                return false;
            }
            
            // Make API call to verify payment
            $response = Http::withToken($token)
                ->get("https://sandbox.payhere.lk/merchant/v1/payment/search/{$paymentReference}");
                
            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] === 'RECEIVED';
            }
            
        } catch (\Exception $e) {
            Log::error('PayHere payment verification failed', [
                'payment_reference' => $paymentReference,
                'error' => $e->getMessage()
            ]);
        }
        
        return false;
    }

    /**
     * Get OAuth access token
     */
    protected function getAccessToken()
    {
        try {
            $response = Http::asForm()->post(config('payhere.authorize_url'), [
                'grant_type' => 'client_credentials',
                'client_id' => config('payhere.app_id'),
                'client_secret' => config('payhere.app_secret'),
            ]);
            
            if ($response->successful()) {
                return $response->json()['access_token'];
            }
        } catch (\Exception $e) {
            Log::error('Failed to get PayHere access token', ['error' => $e->getMessage()]);
        }
        
        return null;
    }

    /**
     * Process refund
     */
    public function processRefund(Order $order, $amount = null)
    {
        // Implement refund logic based on PayHere API
        // This is a placeholder implementation
        
        $refundAmount = $amount ?? $order->total_amount;
        
        try {
            $token = $this->getAccessToken();
            
            if (!$token) {
                throw new \Exception('Failed to get access token');
            }
            
            $response = Http::withToken($token)
                ->post('https://sandbox.payhere.lk/merchant/v1/payment/refund', [
                    'payment_id' => $order->payment_reference,
                    'description' => 'Refund for Order #' . $order->order_number,
                    'amount' => $refundAmount
                ]);
                
            if ($response->successful()) {
                $order->payment_status = 'refunded';
                $order->save();
                
                return true;
            }
            
        } catch (\Exception $e) {
            Log::error('PayHere refund failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return false;
    }
}