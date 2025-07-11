@extends('layouts.app')

@section('title', 'Complete Payment - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Complete Your Payment</h1>
            
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Order Number:</span>
                    <span class="font-medium">{{ $order->order_number }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Total Amount:</span>
                    <span class="text-2xl font-bold text-pink-600">{{ $order->total_formatted }}</span>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <!-- PayHere Payment Form -->
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
                        <p class="text-gray-600 mb-6">
                            You will be redirected to PayHere secure payment gateway to complete your payment.
                        </p>
                        
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Proceed to Secure Payment
                        </button>
                        
                        <div class="mt-4">
                            <a href="{{ route('checkout.index') }}" class="text-gray-600 hover:text-gray-800">
                                Cancel and return to checkout
                            </a>
                        </div>
                    </div>
                </form>
                
                <!-- Security badges -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            Secure Payment
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            SSL Encrypted
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                            </svg>
                            PayHere Secure
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Auto-submit for better UX (optional) -->
        <script>
            // Auto-submit form after 3 seconds
            setTimeout(function() {
                document.getElementById('payhere-form').submit();
            }, 3000);
        </script>
    </div>
</div>
@endsection