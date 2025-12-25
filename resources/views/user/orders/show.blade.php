<!-- ORDER DETAIL PAGE -->
@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('user.orders.index') }}" class="touch-target">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">#{{ $order->order_number }}</h1>
            <div class="w-10"></div>
        </div>

        <!-- Desktop Breadcrumb -->
        <nav class="hidden lg:block mb-6 text-sm">
            <ol class="flex items-center space-x-1">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition-colors">Home</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('user.orders.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">My Orders</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">#{{ $order->order_number }}</li>
            </ol>
        </nav>

        <!-- Order Header -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">Order #{{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <x-order-status-badge :status="$order->status" />
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 flex flex-wrap gap-3">
                @if($order->status !== 'cancelled')
                    <a href="{{ route('user.orders.invoice', $order) }}" 
                       class="btn btn-outline inline-flex items-center group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Download Invoice
                    </a>
                @endif

                @if($order->status === 'shipping')
                    <button onclick="markOrderComplete({{ $order->id }})"
                            class="btn btn-primary inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mark as Received
                    </button>
                @endif

                @if($order->can_be_cancelled && in_array($order->status, ['payment_completed', 'processing']))
                    <button onclick="requestCancellation({{ $order->id }})"
                            class="btn btn-outline text-red-600 border-red-600 hover:bg-red-50 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Request Cancellation
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Items List -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-white border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            Order Items
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @php
                                $groupedItems = $order->items->groupBy('product_id');
                            @endphp

                            @foreach($groupedItems as $productId => $productItems)
                                @php
                                    $firstItem = $productItems->first();
                                    $product = $firstItem->product;
                                @endphp

                                <div class="border border-gray-200 rounded-xl p-4 {{ $productItems->count() > 1 ? 'bg-gradient-to-r from-gray-50 to-white' : 'bg-white' }} hover:shadow-sm transition-shadow duration-200">
                                    @if($productItems->count() > 1)
                                        <div class="mb-3 pb-3 border-b border-gray-100">
                                            <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $productItems->count() }} variants ordered</p>
                                        </div>
                                    @endif

                                    @foreach($productItems as $item)
                                        <div class="flex items-start space-x-4 {{ !$loop->last && $productItems->count() > 1 ? 'mb-3 pb-3 border-b border-gray-100' : '' }}">
                                            <div class="flex-shrink-0 w-20 h-20">
                                                @if($loop->first || $productItems->count() == 1)
                                                    @if($item->product && $item->product->main_image)
                                                        <img src="{{ Storage::url($item->product->main_image) }}" 
                                                             alt="{{ $item->product_name }}"
                                                             class="w-full h-full object-cover rounded-lg hover:scale-105 transition-transform duration-200">
                                                    @else
                                                        <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="w-20"></div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1">
                                                @if($productItems->count() == 1)
                                                    <h3 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h3>
                                                @endif
                                                
                                                @if($item->variant_details)
                                                    @php $variantDetails = json_decode($item->variant_details, true); @endphp
                                                    <div class="flex flex-wrap gap-2 mt-1">
                                                        @if(!empty($variantDetails['size']))
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                Size: {{ $variantDetails['size'] }}
                                                            </span>
                                                        @endif
                                                        @if(!empty($variantDetails['color']))
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                Color: {{ $variantDetails['color'] }}
                                                            </span>
                                                        @endif
                                                        @if(!empty($variantDetails['scent']))
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                Scent: {{ $variantDetails['scent'] }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                                
                                                <div class="mt-2 flex items-center space-x-4 text-sm">
                                                    <span class="text-gray-600">Qty: <span class="font-medium">{{ $item->quantity }}</span></span>
                                                    <span class="text-gray-600">Unit: <span class="font-medium">LKR {{ number_format($item->unit_price, 2) }}</span></span>
                                                </div>
                                            </div>
                                            
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">LKR {{ number_format($item->total_price, 2) }}</p>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <dl class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-600">Subtotal</dt>
                                    <dd class="text-gray-900 font-medium">LKR {{ number_format($order->subtotal, 2) }}</dd>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-sm">
                                        <dt class="text-gray-600">Discount</dt>
                                        <dd class="text-green-600 font-medium">- LKR {{ number_format($order->discount_amount, 2) }}</dd>
                                    </div>
                                @endif
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-600">Shipping</dt>
                                    <dd class="text-gray-900 font-medium">LKR {{ number_format($order->shipping_amount, 2) }}</dd>
                                </div>
                                <div class="flex justify-between text-base font-semibold pt-3 border-t border-gray-200">
                                    <dt class="text-gray-900">Total</dt>
                                    <dd class="text-gray-900">LKR {{ number_format($order->total_amount, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Order Status Timeline -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Order Timeline
                        </h2>
                    </div>
                    <div class="p-6">
                        <ol class="relative">
                            @foreach($order->statusHistory as $index => $history)
                                <li class="mb-8 ml-6 {{ $loop->last ? '' : '' }}">
                                    <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -left-4 ring-4 ring-white {{ $loop->first ? 'bg-primary-100' : 'bg-gray-100' }}">
                                        @if($loop->first)
                                            <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="3"></circle>
                                            </svg>
                                        @endif
                                    </span>
                                    @if(!$loop->last)
                                        <div class="absolute w-0.5 bg-gray-200 h-full left-4 top-8"></div>
                                    @endif
                                    <div class="p-4 bg-gray-50 rounded-lg {{ $loop->first ? 'ring-1 ring-primary-200' : '' }}">
                                        <time class="mb-1 text-xs font-normal leading-none text-gray-500">
                                            {{ $history->created_at->format('F j, Y \a\t g:i A') }}
                                        </time>
                                        <h3 class="text-sm font-semibold text-gray-900">
                                            {{ ucwords(str_replace('_', ' ', $history->status)) }}
                                        </h3>
                                        @if($history->comment)
                                            <p class="text-sm text-gray-600 mt-1">{{ $history->comment }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="space-y-6">
                <!-- Delivery Information -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-white border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            Delivery Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Name</dt>
                                <dd class="text-sm text-gray-900">{{ $order->shipping_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Phone</dt>
                                <dd class="text-sm text-gray-900">{{ $order->shipping_phone }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Address</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $order->shipping_address_line_1 }}<br>
                                    @if($order->shipping_address_line_2)
                                        {{ $order->shipping_address_line_2 }}<br>
                                    @endif
                                    {{ $order->shipping_city }}, {{ $order->shipping_district }}<br>
                                    @if($order->shipping_postal_code)
                                        {{ $order->shipping_postal_code }}
                                    @endif
                                </dd>
                            </div>
                            @if($order->notes)
                                <div>
                                    <dt class="text-sm font-medium text-gray-600 mb-1">Delivery Notes</dt>
                                    <dd class="text-sm text-gray-900 bg-gray-50 rounded-lg p-3">{{ $order->notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-white border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            Payment Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Payment Method</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'PayHere (Online)' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Payment Status</dt>
                                <dd class="text-sm">
                                    @if($order->payment_status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Paid
                                        </span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @if($order->payment_reference)
                                <div>
                                    <dt class="text-sm font-medium text-gray-600 mb-1">Transaction Reference</dt>
                                    <dd class="text-sm text-gray-900 font-mono bg-gray-50 rounded px-2 py-1">
                                        {{ $order->payment_reference }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Quick Help -->
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-6">
                    <h3 class="text-sm font-semibold text-primary-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Need Help?
                    </h3>
                    <p class="text-sm text-primary-800 mb-4">Have questions about your order?</p>
                    <a href="{{ route('contact') }}" class="btn btn-sm btn-primary w-full justify-center">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast for Actions -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .toast-notification {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
    
    toast.className = `toast-notification flex items-center space-x-3 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg max-w-md`;
    toast.innerHTML = `
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${icon}
        </svg>
        <span class="text-sm font-medium">${message}</span>
    `;
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100px)';
        toast.style.transition = 'all 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function markOrderComplete(orderId) {
    if (confirm('Have you received your order? This action confirms delivery and cannot be undone.')) {
        fetch(`/orders/${orderId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast(data.message || 'Error processing request', 'error');
            }
        })
        .catch(error => {
            showToast('Something went wrong. Please try again.', 'error');
        });
    }
}

function requestCancellation(orderId) {
    const reason = prompt('Please provide a reason for cancellation:');
    if (reason) {
        fetch(`/orders/${orderId}/request-cancellation`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast(data.message || 'Error processing request', 'error');
            }
        })
        .catch(error => {
            showToast('Something went wrong. Please try again.', 'error');
        });
    }
}

// Smooth scroll to sections
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endpush
@endsection
