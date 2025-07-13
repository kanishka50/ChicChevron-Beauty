@extends('admin.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    @switch($order->status)
                        @case('payment_completed')
                            bg-blue-100 text-blue-800
                            @break
                        @case('processing')
                            bg-yellow-100 text-yellow-800
                            @break
                        @case('shipping')
                            bg-indigo-100 text-indigo-800
                            @break
                        @case('completed')
                            bg-green-100 text-green-800
                            @break
                        @case('cancelled')
                            bg-red-100 text-red-800
                            @break
                        @default
                            bg-gray-100 text-gray-800
                    @endswitch
                ">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t H:i:s') }}</p>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="showStatusModal()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Update Status
            </button>
            <a href="{{ route('admin.orders.invoice', $order) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-file-pdf mr-2"></i>Generate Invoice
            </a>
            <button onclick="addNote()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-sticky-note mr-2"></i>Add Note
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Order Items ({{ $order->items->count() }})</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <div class="p-6 flex items-center space-x-4">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <img class="h-16 w-16 rounded-lg object-cover" 
                                     src="{{ Storage::url($item->product->main_image) }}" 
                                     alt="{{ $item->product_name }}">
                            </div>
                            
                            <!-- Product Details -->
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h4>
                                        <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                                        
                                        @if($item->variantCombination)
                                            <div class="mt-1 flex flex-wrap gap-2">
                                                @php $variantDetails = json_decode($item->variant_details, true); @endphp
                                                @if(!empty($variantDetails['size']))
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                        Size: {{ $variantDetails['size'] }}
                                                    </span>
                                                @endif
                                                @if(!empty($variantDetails['color']))
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                        Color: {{ $variantDetails['color'] }}
                                                    </span>
                                                @endif
                                                @if(!empty($variantDetails['scent']))
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                                        Scent: {{ $variantDetails['scent'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <div class="mt-2 text-sm text-gray-600">
                                            <span class="font-medium">Brand:</span> {{ $item->product->brand->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">
                                            LKR {{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}
                                        </div>
                                        @if($item->discount_amount > 0)
                                            <div class="text-sm text-green-600">
                                                Discount: -LKR {{ number_format($item->discount_amount, 2) }}
                                            </div>
                                        @endif
                                        <div class="text-lg font-bold text-gray-900">
                                            LKR {{ number_format($item->total_price, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Cost: LKR {{ number_format($item->cost_price * $item->quantity, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Order Totals -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">LKR {{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount:</span>
                                <span class="font-medium text-green-600">-LKR {{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        @if($order->shipping_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping:</span>
                                <span class="font-medium">LKR {{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span>Total:</span>
                            <span>LKR {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Status History</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($order->statusHistory as $index => $history)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                    @switch($history->status)
                                                        @case('payment_completed')
                                                            bg-blue-500
                                                            @break
                                                        @case('processing')
                                                            bg-yellow-500
                                                            @break
                                                        @case('shipping')
                                                            bg-indigo-500
                                                            @break
                                                        @case('completed')
                                                            bg-green-500
                                                            @break
                                                        @case('cancelled')
                                                            bg-red-500
                                                            @break
                                                        @default
                                                            bg-gray-500
                                                    @endswitch
                                                ">
                                                    <i class="fas fa-circle text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        Status changed to {{ ucfirst(str_replace('_', ' ', $history->status)) }}
                                                    </p>
                                                    @if($history->comment)
                                                        <p class="mt-1 text-sm text-gray-600">{{ $history->comment }}</p>
                                                    @endif
                                                    @if($history->changedBy)
                                                        <p class="mt-1 text-xs text-gray-500">
                                                            by {{ $history->changedBy->name }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time>{{ $history->created_at->format('M d, Y H:i') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->user->name ?? $order->shipping_name }}</p>
                        <p class="text-sm text-gray-600">{{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->shipping_phone }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->shipping_name }}</p>
                        <p class="text-sm text-gray-600">{{ $order->full_shipping_address }}</p>
                    </div>
                    
                    @if($order->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Notes</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Payment Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <p class="mt-1 text-sm text-gray-900">{{ strtoupper($order->payment_method) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($order->payment_status === 'completed') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif
                        ">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    
                    @if($order->payment_reference)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Reference</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $order->payment_reference }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profit Analysis -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Profit Analysis</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Revenue:</span>
                        <span class="text-sm font-medium">LKR {{ number_format($profitAnalysis['total_revenue'], 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Cost:</span>
                        <span class="text-sm font-medium">LKR {{ number_format($profitAnalysis['total_cost'], 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between border-t pt-4">
                        <span class="text-sm font-medium text-gray-900">Gross Profit:</span>
                        <span class="text-sm font-bold {{ $profitAnalysis['gross_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            LKR {{ number_format($profitAnalysis['gross_profit'], 2) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-900">Profit Margin:</span>
                        <span class="text-sm font-bold {{ $profitAnalysis['profit_margin'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($profitAnalysis['profit_margin'], 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($order->can_be_cancelled)
                        <button onclick="updateStatus('cancelled')" 
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md">
                            <i class="fas fa-times mr-2"></i>Cancel Order
                        </button>
                    @endif
                    
                    @if($order->status === 'payment_completed')
                        <button onclick="updateStatus('processing')" 
                                class="w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50 rounded-md">
                            <i class="fas fa-cog mr-2"></i>Start Processing
                        </button>
                    @endif
                    
                    @if($order->status === 'processing')
                        <button onclick="updateStatus('shipping')" 
                                class="w-full text-left px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 rounded-md">
                            <i class="fas fa-shipping-fast mr-2"></i>Mark as Shipping
                        </button>
                    @endif
                    
                    @if($order->status === 'shipping')
                        <button onclick="updateStatus('completed')" 
                                class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50 rounded-md">
                            <i class="fas fa-check mr-2"></i>Mark as Completed
                        </button>
                    @endif
                    
                    <button onclick="sendNotification()" 
                            class="w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-md">
                        <i class="fas fa-envelope mr-2"></i>Send Notification
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Order Status</h3>
            
            <form id="statusForm" onsubmit="submitStatusUpdate(event)">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select id="newStatus" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Status</option>
                        @switch($order->status)
                            @case('payment_completed')
                                <option value="processing">Processing</option>
                                <option value="cancelled">Cancelled</option>
                                @break
                            @case('processing')
                                <option value="shipping">Shipping</option>
                                <option value="cancelled">Cancelled</option>
                                @break
                            @case('shipping')
                                <option value="completed">Completed</option>
                                @break
                        @endswitch
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional)</label>
                    <textarea id="statusComment" 
                              name="comment" 
                              rows="3" 
                              placeholder="Add a note about this status change..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="notify_customer" value="1" checked class="mr-2 rounded" id="notifyCustomer">
                        <span class="text-sm text-green-600">Send email notification to customer</span>
                    </label>
                </div>
                
                <div class="flex justify-center space-x-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Update Status
                    </button>
                    <button type="button" onclick="hideStatusModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div id="noteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add Internal Note</h3>
            
            <form id="noteForm" onsubmit="submitNote(event)">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                    <textarea id="noteText" 
                              name="note" 
                              rows="4" 
                              placeholder="Add internal note for this order..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              required></textarea>
                </div>
                
                <div class="flex justify-center space-x-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Add Note
                    </button>
                    <button type="button" onclick="hideNoteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

// Replace the entire scripts section in your show.blade.php with this:

@push('scripts')
<script>
function showStatusModal() {
    document.getElementById('statusModal').classList.remove('hidden');
}

function hideStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusForm').reset();
}

function addNote() {
    document.getElementById('noteModal').classList.remove('hidden');
}

function hideNoteModal() {
    document.getElementById('noteModal').classList.add('hidden');
    document.getElementById('noteForm').reset();
}

function submitStatusUpdate(event) {
    event.preventDefault();
    
    const status = document.getElementById('newStatus').value;
    const comment = document.getElementById('statusComment').value;
    
    if (!status) {
        alert('Please select a status.');
        return;
    }
    
    // Show loading state
    const submitButton = event.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    
    // First, let's change the route from PUT to POST
    fetch(`{{ route('admin.orders.update-status', $order) }}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            // _method: 'PUT', // Method spoofing for Laravel
            status: status,
            comment: comment,
            notify_customer: true
        })
    })
    .then(response => {
        // Log the response for debugging
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response:', text);
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json();
        } else {
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                throw new Error('Server returned non-JSON response');
            });
        }
    })
    .then(data => {
        console.log('Success data:', data);
        
        if (data.success) {
            hideStatusModal();
            showSuccessNotification('Order status updated successfully! Refreshing...');
            
            // Force reload the page
            setTimeout(() => {
                window.location.reload(true);
            }, 1000);
        } else {
            // Re-enable button on error
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
            showErrorNotification(data.message || 'Failed to update order status');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        
        // Re-enable button on error
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
        
        showErrorNotification('Error updating order status. Please check the console.');
    });
}

// Notification functions
function showSuccessNotification(message) {
    removeExistingNotifications();
    
    const notification = document.createElement('div');
    notification.className = 'notification-toast fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999]';
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showErrorNotification(message) {
    removeExistingNotifications();
    
    const notification = document.createElement('div');
    notification.className = 'notification-toast fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999]';
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function removeExistingNotifications() {
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(n => n.remove());
}

// Quick status update
function updateStatus(status) {
    if (confirm(`Are you sure you want to change the order status to ${status}?`)) {
        document.getElementById('newStatus').value = status;
        showStatusModal();
    }
}

// Other functions remain the same
function submitNote(event) {
    event.preventDefault();
    
    const note = document.getElementById('noteText').value;
    
    fetch(`{{ route('admin.orders.add-note', $order) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            note: note
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessNotification('Note added successfully!');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showErrorNotification(data.message || 'Error adding note');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorNotification('Error adding note');
    });
    
    hideNoteModal();
}

function sendNotification() {
    alert('Notification feature will be implemented in the next phase.');
}

// Modal event listeners
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideStatusModal();
    }
});

document.getElementById('noteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideNoteModal();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideStatusModal();
        hideNoteModal();
    }
});
</script>
@endpush