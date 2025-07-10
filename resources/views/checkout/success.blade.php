@extends('layouts.app')

@section('title', 'Order Confirmation - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Order Confirmed!</h1>
            <p class="text-gray-600 mt-2">Thank you for your order. We'll process it shortly.</p>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Order Number</p>
                    <p class="font-medium">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Order Date</p>
                    <p class="font-medium">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payment Method</p>
                    <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Amount</p>
                    <p class="font-medium">Rs. {{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 py-4 border-b border-gray-200 last:border-b-0">
                        <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : '/placeholder.jpg' }}" 
                             alt="{{ $item->product_name }}" 
                             class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item->product_name }}</h3>
                            @if($item->variant_details)
                                <p class="text-sm text-gray-500">{{ $item->variant_details }}</p>
                            @endif
                            <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                            <p class="font-medium">Rs. {{ number_format($item->total_price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="text-center space-y-4">
            <a href="{{ route('user.orders.show', $order) }}" 
               class="inline-flex items-center px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                Track Your Order
            </a>
            
            <div>
                <a href="{{ route('products.index') }}" 
                   class="text-pink-600 hover:text-pink-700">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection