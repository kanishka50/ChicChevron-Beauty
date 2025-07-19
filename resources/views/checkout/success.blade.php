@extends('layouts.app')

@section('title', 'Order Confirmation - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="container-responsive">
        <div class="max-w-4xl mx-auto">
            <!-- Success Animation & Message -->
            <div class="text-center mb-8">
                <!-- Animated Success Icon -->
                <div class="mb-6">
                    <div class="w-20 h-20 md:w-24 md:h-24 mx-auto relative">
                        <div class="absolute inset-0 bg-green-100 rounded-full animate-ping"></div>
                        <div class="relative bg-gradient-to-br from-green-400 to-green-600 rounded-full w-20 h-20 md:w-24 md:h-24 flex items-center justify-center shadow-lg">
                            <svg class="w-10 h-10 md:w-12 md:h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-3">Order Confirmed!</h1>
                <p class="text-lg text-gray-600 mb-2">Thank you for your order</p>
                <p class="text-sm text-gray-500">A confirmation email has been sent to {{ $order->customer_email }}</p>
            </div>

            <!-- Order Summary Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
                <!-- Header with Order Info -->
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 md:px-8 py-6 text-white">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h2 class="text-xl md:text-2xl font-semibold mb-1">Order Details</h2>
                            <p class="text-white/90 text-sm">Order placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur rounded-xl px-4 py-3">
                            <p class="text-xs text-white/80 mb-0.5">Order Number</p>
                            <p class="text-lg font-bold">{{ $order->order_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Status Timeline -->
                <div class="p-6 md:p-8 border-b border-gray-100">
                    <div class="flex items-center justify-between max-w-lg mx-auto">
                        <!-- Payment Confirmed -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600 text-center">Order<br>Placed</span>
                        </div>
                        
                        <!-- Connecting Line -->
                        <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>
                        
                        <!-- Processing -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            </div>
                            <span class="text-xs text-gray-400 text-center">Processing</span>
                        </div>
                        
                        <!-- Connecting Line -->
                        <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>
                        
                        <!-- Shipping -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            </div>
                            <span class="text-xs text-gray-400 text-center">Shipping</span>
                        </div>
                        
                        <!-- Connecting Line -->
                        <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>
                        
                        <!-- Delivered -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            </div>
                            <span class="text-xs text-gray-400 text-center">Delivered</span>
                        </div>
                    </div>
                </div>

                <!-- Customer & Delivery Info -->
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-100">
                    <!-- Customer Information -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Customer Information
                        </h3>
                        <div class="space-y-2 text-sm">
                            <p class="text-gray-600">{{ $order->customer_name }}</p>
                            <p class="text-gray-600">{{ $order->customer_email }}</p>
                            <p class="text-gray-600">{{ $order->customer_phone }}</p>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Delivery Address
                        </h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>{{ $order->shipping_address_line_1 }}</p>
                            @if($order->shipping_address_line_2)
                                <p>{{ $order->shipping_address_line_2 }}</p>
                            @endif
                            <p>{{ $order->shipping_city }}, {{ $order->shipping_district }}</p>
                            @if($order->shipping_postal_code)
                                <p>{{ $order->shipping_postal_code }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Method & Total -->
                <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-100">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Payment Method</h3>
                        <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-sm text-gray-500 mb-1">Order Total</p>
                        <p class="text-2xl font-bold text-primary-600">Rs. {{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="p-6 md:p-8">
                    <h3 class="font-semibold text-gray-900 mb-4">Order Items ({{ $order->items->count() }})</h3>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : '/placeholder.jpg' }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 text-sm md:text-base">{{ $item->product_name }}</h4>
                                    @if($item->variant_details)
                                        <p class="text-sm text-gray-500 mt-0.5">{{ $item->variant_details }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">SKU: {{ $item->product_sku }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                                    <p class="font-medium text-gray-900">Rs. {{ number_format($item->total_price, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span>Rs. {{ number_format($order->subtotal_amount, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-sm text-green-600">
                                <span>Discount</span>
                                <span>-Rs. {{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span>Rs. {{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-lg pt-2 border-t">
                            <span>Total</span>
                            <span class="text-primary-600">Rs. {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('user.orders.show', $order) }}" 
                       class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-6 rounded-lg hover:from-primary-700 hover:to-primary-800 font-medium transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Track Your Order
                    </a>
                    
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-800 py-3 px-6 rounded-lg hover:bg-gray-200 font-medium transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Continue Shopping
                    </a>
                </div>

                <!-- Additional Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm">
                                <p class="text-blue-900 font-medium mb-1">What's Next?</p>
                                <ul class="text-blue-700 space-y-1">
                                    <li>• You'll receive an order confirmation email shortly</li>
                                    <li>• We'll notify you when your order is shipped</li>
                                    <li>• You can track your order status anytime from your account</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500">
                    Have questions about your order? 
                    <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">Contact our support team</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Optional: Confetti Animation -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
// Trigger confetti on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if this is the first time viewing (to avoid confetti on page refresh)
    if (!sessionStorage.getItem('order-{{ $order->id }}-celebrated')) {
        sessionStorage.setItem('order-{{ $order->id }}-celebrated', 'true');
        
        // Trigger confetti
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#ec4899', '#f472b6', '#f9a8d4', '#fbbf24', '#60a5fa']
        });
    }
});
</script>

<style>
/* Success animation */
@keyframes ping {
    75%, 100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.animate-ping {
    animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
@endsection