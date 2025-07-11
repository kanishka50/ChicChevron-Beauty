<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayHere Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for PayHere payment gateway integration
    |
    */

    'merchant_id' => env('PAYHERE_MERCHANT_ID'),
    'merchant_secret' => env('PAYHERE_MERCHANT_SECRET'),
    
    // Use sandbox for testing
    'sandbox' => env('PAYHERE_SANDBOX', true),
    
    // URLs
    'checkout_url' => env('PAYHERE_SANDBOX', true) 
        ? 'https://sandbox.payhere.lk/pay/checkout'
        : 'https://www.payhere.lk/pay/checkout',
        
    'authorize_url' => env('PAYHERE_SANDBOX', true)
        ? 'https://sandbox.payhere.lk/merchant/v1/oauth/token'
        : 'https://www.payhere.lk/merchant/v1/oauth/token',
    
    // Return URLs (will be appended with order number)
    'return_url' => env('APP_URL') . '/checkout/payment/success',
    'cancel_url' => env('APP_URL') . '/checkout/payment/cancel',
    'notify_url' => env('APP_URL') . '/webhooks/payhere',
    
    // Currency
    'currency' => 'LKR',
    
    // App details
    'app_id' => env('PAYHERE_APP_ID'),
    'app_secret' => env('PAYHERE_APP_SECRET'),
];