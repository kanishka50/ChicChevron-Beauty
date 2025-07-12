@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('user.orders.index') }}" class="text-gray-500 hover:text-gray-700">My Orders</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900">{{ $order->order_number }}</li>
            </ol>
        </nav>

        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <x-order-status-badge :status="$order->status" />
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 flex flex-wrap gap-3">
                @if($order->status !== 'cancelled')
                    <a href="{{ route('user.orders.invoice', $order) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-download mr-2"></i> Download Invoice
                    </a>
                @endif

                @if($order->status === 'shipping')
                    <button onclick="markOrderComplete({{ $order->id }})"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i> Mark as Received
                    </button>
                @endif

                @if($order->can_be_cancelled && in_array($order->status, ['payment_completed', 'processing']))
                    <button onclick="requestCancellation({{ $order->id }})"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i> Request Cancellation
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Items List -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-start space-x-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                    <div class="flex-shrink-0 w-20 h-20">
                                        @if($item->product && $item->product->main_image)
                                            <img src="{{ Storage::url($item->product->main_image) }}" 
                                                 alt="{{ $item->product_name }}"
                                                 class="w-full h-full object-cover rounded-md">
                                        @else
                                            <div class="w-full h-full bg-gray-200 rounded-md flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h3>
                                        @if($item->variant_details)
                                            @php $variantDetails = json_decode($item->variant_details, true); @endphp
                                            <div class="flex space-x-2 mt-1">
                                                @if(!empty($variantDetails['size']))
                                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded">
                                                        Size: {{ $variantDetails['size'] }}
                                                    </span>
                                                @endif
                                                @if(!empty($variantDetails['color']))
                                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">
                                                        Color: {{ $variantDetails['color'] }}
                                                    </span>
                                                @endif
                                                @if(!empty($variantDetails['scent']))
                                                    <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded">
                                                        Scent: {{ $variantDetails['scent'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="mt-1 flex items-center space-x-4">
                                            <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                            <span class="text-sm text-gray-600">LKR {{ number_format($item->unit_price, 2) }} each</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">LKR {{ number_format($item->total_price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <dl class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-600">Subtotal</dt>
                                    <dd class="text-gray-900">LKR {{ number_format($order->subtotal, 2) }}</dd>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-sm">
                                        <dt class="text-gray-600">Discount</dt>
                                        <dd class="text-green-600">- LKR {{ number_format($order->discount_amount, 2) }}</dd>
                                    </div>
                                @endif
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-600">Shipping</dt>
                                    <dd class="text-gray-900">LKR {{ number_format($order->shipping_amount, 2) }}</dd>
                                </div>
                                <div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200">
                                    <dt class="text-gray-900">Total</dt>
                                    <dd class="text-gray-900">LKR {{ number_format($order->total_amount, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Order Status Timeline -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Timeline</h2>
                    </div>
                    <div class="p-6">
                        <ol class="relative border-l border-gray-200">
                            @foreach($order->statusHistory as $history)
                                <li class="mb-6 ml-4 {{ $loop->last ? '' : 'pb-6' }}">
                                    <div class="absolute w-3 h-3 bg-gray-400 rounded-full mt-1.5 -left-1.5 border border-white"></div>
                                    <time class="mb-1 text-sm font-normal leading-none text-gray-500">
                                        {{ $history->created_at->format('F j, Y \a\t g:i A') }}
                                    </time>
                                    <h3 class="text-sm font-semibold text-gray-900">
                                        {{ ucwords(str_replace('_', ' ', $history->status)) }}
                                    </h3>
                                    @if($history->comment)
                                        <p class="text-sm text-gray-600">{{ $history->comment }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="space-y-6">
                <!-- Delivery Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Delivery Information</h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Name</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $order->shipping_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Phone</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $order->shipping_phone }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Address</dt>
                                <dd class="text-sm text-gray-900 mt-1">
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
                                    <dt class="text-sm font-medium text-gray-600">Delivery Notes</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $order->notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Payment Information</h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Payment Method</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'PayHere (Online)' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Payment Status</dt>
                                <dd class="text-sm mt-1">
                                    @if($order->payment_status === 'completed')
                                        <span class="text-green-600 font-medium">Paid</span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="text-yellow-600 font-medium">Pending</span>
                                    @else
                                        <span class="text-red-600 font-medium">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </dd>
                            </div>
                            @if($order->payment_reference)
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Transaction Reference</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $order->payment_reference }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
                alert(data.message);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
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
                alert(data.message);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}
</script>
@endpush
@endsection