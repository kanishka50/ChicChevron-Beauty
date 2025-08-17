@extends('layouts.app')

@section('title', 'Processing Payment - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-8">
    <div class="w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Processing State -->
            <div id="processing" class="p-8 md:p-12">
                <div class="text-center">
                    <!-- Animated Logo/Icon -->
                    <div class="mb-8 relative">
                        <div class="w-24 h-24 mx-auto relative">
                            <!-- Outer rotating ring -->
                            <div class="absolute inset-0 border-4 border-primary-200 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-primary-600 rounded-full border-t-transparent animate-spin"></div>
                            
                            <!-- Inner icon -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Processing Your Payment</h2>
                    <p class="text-gray-600 mb-6">Please wait while we confirm your payment with PayHere.</p>
                    
                    <!-- Order Info -->
                    <div class="bg-gray-50 rounded-xl p-4 inline-block">
                        <p class="text-sm text-gray-500">Order Number</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    
                    <!-- Progress dots -->
                    <div class="flex justify-center items-center gap-2 mt-8">
                        <div class="w-2 h-2 bg-primary-600 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-primary-600 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-primary-600 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>

                    <p class="text-sm text-gray-500 mt-6">This usually takes less than 30 seconds...</p>
                </div>
            </div>

            <!-- Timeout State (hidden by default) -->
            <div id="timeout-message" class="hidden p-8 md:p-12">
                <div class="text-center">
                    <div class="text-yellow-500 mb-6 flex justify-center">
                        <div class="bg-yellow-100 rounded-full p-4">
                            <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">Payment Processing Taking Longer</h2>
                    <p class="text-gray-600 mb-6">Your payment is still being processed. This sometimes takes a bit longer.</p>
                    
                    <p class="text-sm text-gray-500 mb-8">
                        If you completed the payment on PayHere, please wait a moment longer. 
                        The payment confirmation should arrive shortly.
                    </p>

                    <div class="space-y-3">
                        <a href="{{ route('user.orders.index') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                            Check Order Status
                        </a>
                        <p class="text-xs text-gray-500">You can check your order status in your account</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Help Text -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                Having trouble? 
                <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">Contact Support</a>
            </p>
        </div>
    </div>
</div>

<script>
let checkCount = 0;
const maxChecks = 60; // Check for up to 2 minutes
let checkInterval;

function checkPaymentStatus() {
    fetch('{{ route("checkout.payment.status", $order) }}', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Payment status check:', data);
        
        if (data.payment_status === 'completed') {
            // Payment successful - redirect to success page
            window.location.href = '{{ route("checkout.success", $order) }}';
        } else if (data.payment_status === 'failed' || data.payment_status === 'cancelled') {
            // Payment failed - redirect to checkout with error
            window.location.href = '{{ route("checkout.index") }}?error=payment_failed';
        } else {
            checkCount++;
            if (checkCount >= maxChecks) {
                // Show timeout message
                document.getElementById('processing').classList.add('hidden');
                document.getElementById('timeout-message').classList.remove('hidden');
                clearInterval(checkInterval);
            }
        }
    })
    .catch(error => {
        console.error('Error checking payment status:', error);
        checkCount++;
        if (checkCount >= maxChecks) {
            clearInterval(checkInterval);
            document.getElementById('processing').classList.add('hidden');
            document.getElementById('timeout-message').classList.remove('hidden');
        }
    });
}

// Start checking immediately
checkPaymentStatus();

// Then check every 2 seconds
checkInterval = setInterval(checkPaymentStatus, 2000);

// Clear interval when page is hidden/closed
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(checkInterval);
    }
});
</script>

<style>
/* Enhanced animations */
@keyframes bounce {
    0%, 100% {
        transform: translateY(-25%);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
}

@keyframes ping {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}

.animate-ping {
    animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
@endsection