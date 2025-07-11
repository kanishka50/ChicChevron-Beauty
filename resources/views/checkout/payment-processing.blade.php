@extends('layouts.app')

@section('title', 'Processing Payment - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="text-center">
                <div class="mb-6">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-pink-600 mx-auto"></div>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Processing Your Payment</h1>
                
                <p class="text-gray-600 mb-6">
                    Please wait while we confirm your payment. This may take a few moments.
                </p>
                
                <p class="text-sm text-gray-500">
                    Order Number: <span class="font-medium">{{ $order->order_number }}</span>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Check payment status every 3 seconds
// Check payment status every 3 seconds
setInterval(function() {
    // Force HTTPS URL
    const statusUrl = '{{ route('checkout.payment.status', $order) }}'.replace('http://', 'https://');
    const successUrl = '{{ route('checkout.success', $order) }}'.replace('http://', 'https://');
    
    fetch(statusUrl)
        .then(response => response.json())
        .then(data => {
            if (data.is_completed) {
                window.location.href = successUrl;
            }
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
            // Fallback: redirect to success page after multiple failures
            if (window.paymentCheckAttempts === undefined) {
                window.paymentCheckAttempts = 0;
            }
            window.paymentCheckAttempts++;
            if (window.paymentCheckAttempts > 5) {
                window.location.href = successUrl;
            }
        });
}, 3000);
</script>
@endsection