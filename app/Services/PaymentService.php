<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $payHereService;

    public function __construct(PayHereService $payHereService)
    {
        $this->payHereService = $payHereService;
    }

    /**
     * Process payment for an order
     */
    public function processPayment(Order $order)
    {
        // For COD orders, no payment processing needed
        if ($order->payment_method === 'cod') {
            return [
                'success' => true,
                'message' => 'Cash on Delivery order placed successfully'
            ];
        }

        // For PayHere payments
        if ($order->payment_method === 'payhere') {
            return $this->payHereService->createPayment($order);
        }

        throw new \Exception('Invalid payment method');
    }

    /**
     * Handle payment callback
     */
    public function handleCallback($paymentMethod, array $data)
    {
        if ($paymentMethod === 'payhere') {
            return $this->payHereService->handleCallback($data);
        }

        throw new \Exception('Invalid payment method for callback');
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(Order $order)
    {
        if ($order->payment_method === 'payhere' && $order->payment_reference) {
            return $this->payHereService->verifyPayment($order->payment_reference);
        }

        return false;
    }

    /**
     * Process refund
     */
    public function processRefund(Order $order, $amount = null)
    {
        if ($order->payment_method === 'payhere') {
            return $this->payHereService->processRefund($order, $amount);
        }

        throw new \Exception('Refunds not available for this payment method');
    }
}