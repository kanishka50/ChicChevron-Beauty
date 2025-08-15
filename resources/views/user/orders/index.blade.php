<!-- ORDERS LIST PAGE -->
@extends('layouts.app')

@section('title', 'My Orders - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <div class="container-responsive py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('user.account.index') }}" class="touch-target">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">My Orders</h1>
            <div class="w-10"></div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden lg:flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                <p class="mt-1 text-gray-600">Track and manage your ChicChevron Beauty orders</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-primary group">
                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Continue Shopping
            </a>
        </div>

        <!-- Status Filter Tabs with Horizontal Scroll on Mobile -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="border-b border-gray-100 overflow-x-auto scrollbar-hide">
                <nav class="-mb-px flex space-x-4 sm:space-x-6 px-4 sm:px-6 min-w-max">
                    <a href="{{ route('user.orders.index') }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200 {{ !request('status') || request('status') === 'all' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All Orders
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ !request('status') || request('status') === 'all' ? 'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $statusCounts['all'] }}
                        </span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'payment_completed']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200 {{ request('status') === 'payment_completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Confirmed
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'payment_completed' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $statusCounts['payment_completed'] }}
                        </span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'processing']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200 {{ request('status') === 'processing' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Processing
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'processing' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $statusCounts['processing'] }}
                        </span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'shipping']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200 {{ request('status') === 'shipping' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Shipping
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'shipping' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $statusCounts['shipping'] }}
                        </span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'completed']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200 {{ request('status') === 'completed' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Completed
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'completed' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $statusCounts['completed'] }}
                        </span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'cancelled']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200 {{ request('status') === 'cancelled' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Cancelled
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $statusCounts['cancelled'] }}
                        </span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 p-4 sm:p-6">
            <form method="GET" action="{{ route('user.orders.index') }}" class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by order number..."
                               class="form-input pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="btn btn-primary flex-1 sm:flex-initial">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('user.orders.index', ['status' => request('status')]) }}" 
                           class="btn btn-secondary flex-1 sm:flex-initial">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Orders List -->
        <div class="space-y-4">
            @forelse($orders as $index => $order)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 group"
                     style="animation: fadeInUp 0.5s ease-out {{ $index * 0.1 }}s backwards;">
                    <!-- Order Header -->
                    <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">
                                        Order #{{ $order->order_number }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $order->created_at->format('F d, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                <x-order-status-badge :status="$order->status" />
                            </div>
                            <div class="text-left sm:text-right">
                                <div class="text-lg font-bold text-gray-900">
                                    LKR {{ number_format($order->total_amount, 2) }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="px-4 sm:px-6 py-4">
                        <div class="flex items-center space-x-3 overflow-x-auto scrollbar-hide pb-2">
                            @foreach($order->items->take(4) as $item)
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <div class="relative group">
                                        <img src="{{ Storage::url($item->product->main_image) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="w-16 h-16 rounded-lg object-cover group-hover:scale-105 transition-transform duration-200">
                                        @if($item->quantity > 1)
                                            <span class="absolute -top-2 -right-2 bg-gray-800 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">
                                                {{ $item->quantity }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate max-w-[150px]">
                                            {{ $item->product_name }}
                                        </p>
                                        @if($item->variant_details)
                                            @php $variantDetails = json_decode($item->variant_details, true); @endphp
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @if(is_array($variantDetails))
                                                    @foreach($variantDetails as $key => $value)
                                                        @if($value)
                                                            <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded">
                                                                {{ ucfirst($key) }}: {{ $value }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($order->items->count() > 4)
                                <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <span class="text-sm text-gray-600 font-medium">
                                        +{{ $order->items->count() - 4 }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Order Actions -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 mt-4 border-t border-gray-100 space-y-3 sm:space-y-0">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('user.orders.show', $order) }}" 
                                   class="text-primary-600 hover:text-primary-700 font-medium text-sm inline-flex items-center group">
                                    <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                                
                                @if($order->status !== 'cancelled')
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('user.orders.invoice', $order) }}" 
                                       class="text-green-600 hover:text-green-700 font-medium text-sm inline-flex items-center group">
                                        <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                        </svg>
                                        Invoice
                                    </a>
                                @endif
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                @if($order->can_be_cancelled && in_array($order->status, ['payment_completed', 'processing']))
                                    <button onclick="requestCancellation({{ $order->id }})" 
                                            class="btn btn-sm btn-outline text-red-600 border-red-600 hover:bg-red-50">
                                        Cancel Order
                                    </button>
                                @endif
                                
                                @if($order->status === 'shipping')
                                    <button onclick="markAsCompleted({{ $order->id }})" 
                                            class="btn btn-sm btn-primary">
                                        Mark as Received
                                    </button>
                                @endif
                                
                                <button onclick="trackOrder({{ $order->id }})" 
                                        class="btn btn-sm btn-primary">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                    Track
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm p-8 sm:p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No orders found</h3>
                    <p class="text-gray-600 mb-8">
                        @if(request('search'))
                            No orders match your search criteria.
                        @else
                            You haven't placed any orders yet. Start shopping to see your orders here!
                        @endif
                    </p>
                    <a href="{{ route('products.index') }}" 
                       class="btn btn-primary inline-flex items-center group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Start Shopping
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Order Tracking Modal -->
<div id="trackingModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm"></div>
        </div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Order Tracking</h3>
                    <button onclick="closeTrackingModal()" class="touch-target group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="trackingContent">
                    <!-- Tracking content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div id="cancellationModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm"></div>
        </div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Order Cancellation</h3>
                
                <form id="cancellationForm" onsubmit="submitCancellation(event)">
                    <input type="hidden" id="cancellationOrderId" name="order_id">
                    
                    <div class="mb-4">
                        <label class="form-label">Reason for Cancellation</label>
                        <textarea id="cancellationReason" 
                                  name="reason" 
                                  rows="4" 
                                  placeholder="Please tell us why you want to cancel this order..."
                                  class="form-input"
                                  required></textarea>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="closeCancellationModal()" 
                                class="btn btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="btn btn-primary bg-red-600 hover:bg-red-700 focus:ring-red-500">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-500/75 backdrop-blur-sm">
    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <div class="flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
            <span class="text-gray-700">Processing...</span>
        </div>
    </div>
</div>

<!-- Toast Container -->
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
    
    .toast-notification {
        animation: slideInRight 0.3s ease-out;
    }
</style>
@endpush

@push('scripts')
<script>
// Toast notification
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

function trackOrder(orderId) {
    showLoading();
    
    fetch(`/orders/${orderId}/track`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showTrackingModal(data, orderId);
            } else {
                showToast('Error loading tracking information', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Something went wrong. Please try again.', 'error');
        });
}

function showTrackingModal(trackingData, orderId) {
    const content = `
        <div class="mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100/50 p-4 rounded-xl mb-4">
                <h4 class="font-semibold text-blue-900">Order #${trackingData.order_number}</h4>
                <p class="text-blue-700 text-sm mt-1">Status: ${trackingData.current_status_label}</p>
                <p class="text-blue-600 text-sm">Estimated Delivery: ${trackingData.estimated_delivery}</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <h5 class="font-medium text-gray-900">Order Timeline</h5>
            <div class="relative">
                ${trackingData.status_history.map((status, index) => `
                    <div class="flex items-start space-x-3 ${!status.is_current ? 'opacity-60' : ''}">
                        <div class="relative flex items-center justify-center">
                            <div class="w-10 h-10 rounded-full ${status.is_current ? 'bg-primary-100' : 'bg-gray-100'} flex items-center justify-center">
                                ${status.is_current 
                                    ? '<svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>'
                                    : '<div class="w-3 h-3 rounded-full bg-gray-400"></div>'
                                }
                            </div>
                            ${index < trackingData.status_history.length - 1 ? '<div class="absolute top-10 left-5 w-0.5 h-16 bg-gray-200"></div>' : ''}
                        </div>
                        <div class="flex-1 pb-8">
                            <p class="font-medium ${status.is_current ? 'text-primary-900' : 'text-gray-900'}">${status.status_label}</p>
                            ${status.comment ? `<p class="text-sm text-gray-600 mt-1">${status.comment}</p>` : ''}
                            <p class="text-xs text-gray-500 mt-1">${status.date}</p>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
        
        <div class="mt-6 flex justify-center space-x-3">
            ${trackingData.can_complete ? `
                <button onclick="markAsCompleted(${trackingData.order_id || orderId})" 
                        class="btn btn-primary">
                    Mark as Received
                </button>
            ` : ''}
            ${trackingData.can_cancel ? `
                <button onclick="requestCancellation(${orderId})" 
                        class="btn btn-outline text-red-600 border-red-600 hover:bg-red-50">
                    Request Cancellation
                </button>
            ` : ''}
        </div>
    `;
    
    document.getElementById('trackingContent').innerHTML = content;
    document.getElementById('trackingModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTrackingModal() {
    document.getElementById('trackingModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function requestCancellation(orderId) {
    document.getElementById('cancellationOrderId').value = orderId;
    document.getElementById('cancellationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    closeTrackingModal();
}

function closeCancellationModal() {
    document.getElementById('cancellationModal').classList.add('hidden');
    document.getElementById('cancellationForm').reset();
    document.body.style.overflow = '';
}

function submitCancellation(event) {
    event.preventDefault();
    
    const orderId = document.getElementById('cancellationOrderId').value;
    const reason = document.getElementById('cancellationReason').value;
    
    showLoading();
    
    fetch(`/orders/${orderId}/request-cancellation`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        closeCancellationModal();
        
        if (data.success) {
            showToast(data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'Error processing request', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        closeCancellationModal();
        showToast('Something went wrong. Please try again.', 'error');
    });
}

function markAsCompleted(orderId) {
    if (confirm('Are you sure you want to mark this order as completed? This confirms that you have received all items.')) {
        showLoading();
        
        fetch(`/orders/${orderId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            closeTrackingModal();
            
            if (data.success) {
                showToast(data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Error processing request', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Something went wrong. Please try again.', 'error');
        });
    }
}

function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
    document.getElementById('loadingOverlay').classList.add('flex');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
    document.getElementById('loadingOverlay').classList.remove('flex');
}

// Close modals when clicking outside
document.getElementById('trackingModal').addEventListener('click', function(e) {
    if (e.target === this || e.target.classList.contains('bg-gray-500')) {
        closeTrackingModal();
    }
});

document.getElementById('cancellationModal').addEventListener('click', function(e) {
    if (e.target === this || e.target.classList.contains('bg-gray-500')) {
        closeCancellationModal();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTrackingModal();
        closeCancellationModal();
    }
});
</script>
@endpush
@endsection