@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                    <p class="mt-2 text-gray-600">Track and manage your ChicChevron Beauty orders</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <a href="{{ route('user.orders.index') }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ !request('status') || request('status') === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All Orders
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['all'] }}</span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'payment_completed']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'payment_completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Confirmed
                        <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['payment_completed'] }}</span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'processing']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'processing' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Processing
                        <span class="ml-2 bg-yellow-100 text-yellow-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['processing'] }}</span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'shipping']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'shipping' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Shipping
                        <span class="ml-2 bg-indigo-100 text-indigo-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['shipping'] }}</span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'completed']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'completed' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Completed
                        <span class="ml-2 bg-green-100 text-green-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['completed'] }}</span>
                    </a>
                    
                    <a href="{{ route('user.orders.index', ['status' => 'cancelled']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'cancelled' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Cancelled
                        <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['cancelled'] }}</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <form method="GET" action="{{ route('user.orders.index') }}" class="flex items-center space-x-4">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by order number..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                @if(request('search'))
                    <a href="{{ route('user.orders.index', ['status' => request('status')]) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Orders List -->
        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Order Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Order {{ $order->order_number }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                <x-order-status-badge :status="$order->status" />
                            </div>
                            <div class="text-right">
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
                    <div class="px-6 py-4">
                        <div class="flex items-center space-x-4 mb-4">
                            @foreach($order->items->take(3) as $item)
                                <div class="flex items-center space-x-3">
                                    <img src="{{ Storage::url($item->product->main_image) }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="w-12 h-12 rounded-lg object-cover">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ Str::limit($item->product_name, 30) }}</p>
                                        <p class="text-xs text-gray-600">Qty: {{ $item->quantity }}</p>
                                        @if($item->productVariant)
                                            <div class="flex space-x-1 mt-1">
                                                @if($item->productVariant->size)
                                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded">
                                                        {{ $item->productVariant->size }}
                                                    </span>
                                                @endif
                                                @if($item->productVariant->color)
                                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">
                                                        {{ $item->productVariant->color }}
                                                    </span>
                                                @endif
                                                @if($item->productVariant->scent)
                                                    <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded">
                                                        {{ $item->productVariant->scent }}
                                                    </span>
                                                @endif
                                            </div>
                                        @elseif($item->variant_details)
                                            @php $variantDetails = json_decode($item->variant_details, true); @endphp
                                            <div class="flex space-x-1 mt-1">
                                                @if(is_array($variantDetails))
                                                    @foreach($variantDetails as $key => $value)
                                                        @if($value)
                                                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded">
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
                            @if($order->items->count() > 3)
                                <div class="text-sm text-gray-500">
                                    +{{ $order->items->count() - 3 }} more items
                                </div>
                            @endif
                        </div>

                        <!-- Order Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex space-x-3">
                                <a href="{{ route('user.orders.show', $order) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    View Details
                                </a>
                                
                                @if($order->status !== 'cancelled')
                                    <a href="{{ route('user.orders.invoice', $order) }}" 
                                       class="text-green-600 hover:text-green-800 font-medium text-sm">
                                        Download Invoice
                                    </a>
                                @endif
                                
                                {{-- @if($order->status === 'completed')
                                    <a href="{{ route('user.reviews.create', $order) }}" 
                                       class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                                        Leave Review
                                    </a>
                                @endif --}}
                                
                            </div>
                            
                            <div class="flex space-x-3">
                                @if($order->can_be_cancelled && in_array($order->status, ['payment_completed', 'processing']))
                                    <button onclick="requestCancellation({{ $order->id }})" 
                                            class="text-red-600 hover:text-red-800 font-medium text-sm">
                                        Request Cancellation
                                    </button>
                                @endif
                                
                                @if($order->status === 'shipping')
                                    <button onclick="markAsCompleted({{ $order->id }})" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        Mark as Received
                                    </button>
                                @endif
                                
                                <button onclick="trackOrder({{ $order->id }})" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Track Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('search'))
                            No orders match your search criteria.
                        @else
                            You haven't placed any orders yet. Start shopping to see your orders here!
                        @endif
                    </p>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-shopping-cart mr-2"></i>
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
<div id="trackingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Order Tracking</h3>
                <button onclick="closeTrackingModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="trackingContent">
                <!-- Tracking content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Request Modal -->
<div id="cancellationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Request Order Cancellation</h3>
            
            <form id="cancellationForm" onsubmit="submitCancellation(event)">
                <input type="hidden" id="cancellationOrderId" name="order_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Cancellation</label>
                    <textarea id="cancellationReason" 
                              name="reason" 
                              rows="4" 
                              placeholder="Please tell us why you want to cancel this order..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCancellationModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-4"></div>
            <span class="text-gray-700">Processing...</span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function trackOrder(orderId) {
    showLoading();
    
    fetch(`/orders/${orderId}/track`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showTrackingModal(data, orderId);
            } else {
                alert('Error loading tracking information');
            }
        })
        .catch(error => {
            hideLoading();
            alert('Error: ' + error.message);
        });
}

function showTrackingModal(trackingData, orderId) {
    const content = `
        <div class="mb-6">
            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-blue-900">Order ${trackingData.order_number}</h4>
                <p class="text-blue-700">Status: ${trackingData.current_status_label}</p>
                <p class="text-blue-600 text-sm">Estimated Delivery: ${trackingData.estimated_delivery}</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <h5 class="font-medium text-gray-900">Order Timeline</h5>
            ${trackingData.status_history.map(status => `
                <div class="flex items-center space-x-3 ${status.is_current ? 'bg-blue-50 p-3 rounded-lg' : ''}">
                    <div class="w-3 h-3 rounded-full ${status.is_current ? 'bg-blue-600' : 'bg-gray-300'}"></div>
                    <div class="flex-1">
                        <p class="font-medium ${status.is_current ? 'text-blue-900' : 'text-gray-900'}">${status.status_label}</p>
                        ${status.comment ? `<p class="text-sm text-gray-600">${status.comment}</p>` : ''}
                        <p class="text-xs text-gray-500">${status.date}</p>
                    </div>
                </div>
            `).join('')}
        </div>
        
        <div class="mt-6 flex justify-center space-x-3">
                    ${trackingData.can_complete ? `
                    <button onclick="markAsCompleted(${trackingData.order_id || orderId})" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        Mark as Received
                    </button>
                ` : ''}
            ${trackingData.can_cancel ? `
                <button onclick="requestCancellation(${orderId})" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                    Request Cancellation
                </button>
            ` : ''}
        </div>
    `;
    
    document.getElementById('trackingContent').innerHTML = content;
    document.getElementById('trackingModal').classList.remove('hidden');
}

function closeTrackingModal() {
    document.getElementById('trackingModal').classList.add('hidden');
}

function requestCancellation(orderId) {
    document.getElementById('cancellationOrderId').value = orderId;
    document.getElementById('cancellationModal').classList.remove('hidden');
    closeTrackingModal();
}

function closeCancellationModal() {
    document.getElementById('cancellationModal').classList.add('hidden');
    document.getElementById('cancellationForm').reset();
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
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        closeCancellationModal();
        alert('Error: ' + error.message);
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
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            alert('Error: ' + error.message);
        });
    }
}

function reorderItems(orderId) {
    if (confirm('Add all items from this order to your cart?')) {
        showLoading();
        
        fetch(`/orders/${orderId}/reorder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                alert(data.message);
                if (data.added_items > 0) {
                    window.location.href = '/cart';
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            alert('Error: ' + error.message);
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
    if (e.target === this) {
        closeTrackingModal();
    }
});

document.getElementById('cancellationModal').addEventListener('click', function(e) {
    if (e.target === this) {
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