<?php

return [
    'merchant_id' => env('PAYHERE_MERCHANT_ID'),
    'merchant_secret' => env('PAYHERE_MERCHANT_SECRET'),
    'notify_url' => env('PAYHERE_NOTIFY_URL'),
    'return_url' => env('PAYHERE_RETURN_URL'),
    'cancel_url' => env('PAYHERE_CANCEL_URL'),
    'mode' => env('PAYHERE_MODE', 'sandbox'),
];