@extends('layouts.app')

@section('title', 'Complete Payment - ChicChevron Beauty')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="container-responsive">
        <ol class="flex items-center space-x-1 md:space-x-2 text-xs md:text-sm flex-wrap">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-600 transition-colors">Home</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">Cart</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('checkout.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">Checkout</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-900 font-medium">Payment</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="container-responsive">
        <div class="max-w-3xl mx-auto">
            <!-- Payment Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Header with Gradient -->
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 md:px-8 py-6 md:py-8 text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold">Complete Your Payment</h1>
                    </div>
                    <p class="text-white/90 text-sm md:text-base">Secure payment powered by PayHere</p>
                </div>

                <!-- Order Summary Section -->
                <div class="p-6 md:p-8 border-b border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Order Number</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                            <p class="text-2xl md:text-3xl font-bold text-primary-600">{{ $order->total_formatted }}</p>
                        </div>
                    </div>

                    <!-- Order Items Preview (Mobile Optimized) -->
                    <details class="mt-6">
                        <summary class="cursor-pointer text-sm text-gray-600 hover:text-gray-900 font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View Order Details ({{ $order->items->count() }} items)
                        </summary>
                        <div class="mt-4 space-y-3 max-h-48 overflow-y-auto">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="flex-1">
                                        <p class="text-gray-900">{{ $item->product_name }}</p>
                                        @if($item->variant_details)
                                            <p class="text-xs text-gray-500">{{ $item->variant_details }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-gray-600">Qty: {{ $item->quantity }}</p>
                                        <p class="font-medium">{{ $item->total_formatted }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </details>
                </div>

                <!-- Payment Form Section -->
                <div class="p-6 md:p-8">
                    <form method="post" action="{{ config('payhere.checkout_url') }}" id="payhere-form">
                        @php
                            $paymentService = app(\App\Services\PayHereService::class);
                            $paymentData = $paymentService->createPayment($order);
                            $data = $paymentData['payment_data'];
                        @endphp
                        
                        @foreach($data as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <div class="text-center">
                            <!-- Security Info -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-left">
                                        <p class="text-sm text-blue-900 font-medium">Secure Payment Gateway</p>
                                        <p class="text-sm text-blue-700 mt-1">You will be redirected to PayHere's secure payment page to complete your purchase.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Main CTA Button -->
                            <button type="submit" 
                                    id="payment-button"
                                    class="w-full md:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 font-semibold text-lg transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Proceed to Secure Payment
                            </button>
                            
                            <!-- Cancel Link -->
                            <div class="mt-6">
                                <a href="{{ route('checkout.index') }}" class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                    Cancel and return to checkout
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Security Badges Section -->
                <div class="bg-gray-50 border-t border-gray-100 px-6 md:px-8 py-6">
                    <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>256-bit SSL Encryption</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>PCI DSS Compliant</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Powered by PayHere</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 mb-2">
                    By proceeding, you agree to our 
                    <a href="{{ route('terms') }}" class="text-primary-600 hover:text-primary-700 underline">Terms & Conditions</a>
                    and 
                    <a href="{{ route('privacy') }}" class="text-primary-600 hover:text-primary-700 underline">Privacy Policy</a>
                </p>
                <p class="text-sm text-gray-500">
                    Need help? 
                    <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">Contact our support team</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Auto-submit Enhancement -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit countdown
    let countdown = 5;
    const button = document.getElementById('payment-button');
    const originalText = button.innerHTML;
    
    // Show countdown
    const updateCountdown = setInterval(() => {
        if (countdown > 0) {
            button.innerHTML = `
                <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Redirecting in ${countdown}s...
            `;
            countdown--;
        } else {
            clearInterval(updateCountdown);
            button.innerHTML = `
                <span class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></span>
                Redirecting to PayHere...
            `;
            document.getElementById('payhere-form').submit();
        }
    }, 1000);
    
    // Allow manual submit
    button.addEventListener('click', function(e) {
        e.preventDefault();
        clearInterval(updateCountdown);
        button.innerHTML = `
            <span class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></span>
            Redirecting to PayHere...
        `;
        button.disabled = true;
        document.getElementById('payhere-form').submit();
    });
});
</script>

<style>
/* Smooth scrollbar for order items */
details[open] > div::-webkit-scrollbar {
    width: 4px;
}

details[open] > div::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 2px;
}

details[open] > div::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 2px;
}

details[open] > div::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Details/Summary animation */
details > summary::-webkit-details-marker {
    display: none;
}

details > summary::before {
    content: '▶';
    display: inline-block;
    margin-right: 0.5rem;
    transition: transform 0.2s;
}

details[open] > summary::before {
    transform: rotate(90deg);
}
</style>
@endsection