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
                    <p class="text-gray-600 mb-6">Please wait while we confirm your payment. This may take a few moments.</p>
                    
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
                </div>
            </div>

            <!-- Success State -->
            <div id="success" class="hidden p-8 md:p-12">
                <div class="text-center">
                    <!-- Success Animation -->
                    <div class="mb-8">
                        <div class="w-24 h-24 mx-auto relative">
                            <div class="absolute inset-0 bg-green-100 rounded-full animate-ping"></div>
                            <div class="relative bg-green-100 rounded-full w-24 h-24 flex items-center justify-center">
                                <svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Payment Successful!</h2>
                    <p class="text-gray-600 mb-6">Thank you for your order. We've sent a confirmation email to your address.</p>
                    
                    <!-- Order Total -->
                    <div class="bg-green-50 rounded-xl p-4 mb-8">
                        <p class="text-sm text-gray-600 mb-1">Order Total</p>
                        <p class="text-2xl font-bold text-green-600">Rs. {{ number_format($order->total_amount, 2) }}</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="{{ route('user.orders.show', $order) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-6 rounded-lg hover:from-primary-700 hover:to-primary-800 font-medium transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View Order Details
                        </a>
                        <a href="{{ route('home') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-800 py-3 px-6 rounded-lg hover:bg-gray-200 font-medium transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <!-- Failed State -->
            <div id="failed" class="hidden p-8 md:p-12">
                <div class="text-center">
                    <!-- Error Animation -->
                    <div class="mb-8">
                        <div class="w-24 h-24 mx-auto relative">
                            <div class="absolute inset-0 bg-red-100 rounded-full animate-ping"></div>
                            <div class="relative bg-red-100 rounded-full w-24 h-24 flex items-center justify-center">
                                <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Payment Failed</h2>
                    <p class="text-gray-600 mb-6">We couldn't process your payment. Don't worry, you can try again.</p>
                    
                    <!-- Error Info -->
                    <div class="bg-red-50 rounded-xl p-4 mb-8">
                        <p class="text-sm text-red-800">Please check your payment details and try again. If the problem persists, contact your bank.</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="{{ route('checkout.index') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-6 rounded-lg hover:from-primary-700 hover:to-primary-800 font-medium transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Try Again
                        </a>
                        <a href="{{ route('home') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-800 py-3 px-6 rounded-lg hover:bg-gray-200 font-medium transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Back to Shop
                        </a>
                    </div>
                </div>
            </div>
            
            @if(config('app.debug'))
            <!-- Development Mode: Manual Confirmation -->
            <div id="manual-confirm" class="p-4 bg-yellow-50 border-t border-yellow-200">
                <div class="text-center">
                    <p class="text-xs text-gray-700 mb-2">Development Mode</p>
                    <p class="text-sm text-gray-700 mb-3">If payment was successful in PayHere but not updating here:</p>
                    <button onclick="manualConfirm()" 
                            class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Manually Confirm Payment
                    </button>
                </div>
            </div>
            @endif
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
const maxChecks = 30;

function checkPaymentStatus() {
    // Force HTTPS
    const statusUrl = '{{ route("checkout.payment.status", $order) }}'.replace('http://', 'https://');
    
    fetch(statusUrl, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Payment status:', data);
        
        if (data.payment_status === 'completed') {
            // Show success state
            document.getElementById('processing').classList.add('hidden');
            document.getElementById('success').classList.remove('hidden');
            if (document.getElementById('manual-confirm')) {
                document.getElementById('manual-confirm').classList.add('hidden');
            }
            
            // Trigger confetti or celebration animation (optional)
            if (typeof confetti !== 'undefined') {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }
        } else if (data.payment_status === 'failed' || data.payment_status === 'cancelled') {
            // Show failed state
            document.getElementById('processing').classList.add('hidden');
            document.getElementById('failed').classList.remove('hidden');
            if (document.getElementById('manual-confirm')) {
                document.getElementById('manual-confirm').classList.add('hidden');
            }
        } else {
            // Continue checking
            checkCount++;
            if (checkCount < maxChecks) {
                setTimeout(checkPaymentStatus, 2000);
            } else {
                // Timeout - show manual confirm option
                if (document.getElementById('manual-confirm')) {
                    document.getElementById('manual-confirm').classList.remove('bg-yellow-50');
                    document.getElementById('manual-confirm').classList.add('bg-orange-50', 'border-orange-200');
                }
            }
        }
    })
    .catch(error => {
        console.error('Error checking payment status:', error);
        checkCount++;
        if (checkCount < maxChecks) {
            setTimeout(checkPaymentStatus, 2000);
        }
    });
}

// Manual confirmation function
function manualConfirm() {
    if (confirm('Confirm that payment was successful in PayHere?')) {
        // Force HTTPS
        const confirmUrl = '{{ route("checkout.payment.confirm", $order) }}'.replace('http://', 'https://');
        
        // Show loading state on button
        const button = event.target;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span> Confirming...';
        
        fetch(confirmUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                payment_id: 'MANUAL-CONFIRM'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                // Restore button
                button.disabled = false;
                button.innerHTML = originalText;
                alert('Error confirming payment. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Restore button
            button.disabled = false;
            button.innerHTML = originalText;
            alert('Error confirming payment. Please try again.');
        });
    }
}

// Start checking immediately
checkPaymentStatus();

// Also check on page visibility change (in case user switches tabs)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && checkCount < maxChecks) {
        checkPaymentStatus();
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