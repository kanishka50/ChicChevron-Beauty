@extends('admin.layouts.app')

@section('title', 'Order Management')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order Management</h1>
            <p class="text-gray-600">Manage and track all customer orders</p>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="exportOrders()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-download mr-2"></i>Export CSV
            </button>
            <button onclick="showBulkActions()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-tasks mr-2"></i>Bulk Actions
            </button>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6">
                <a href="{{ route('admin.orders.index') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ !request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All Orders
                    <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['all'] }}</span>
                </a>
                
                <a href="{{ route('admin.orders.index', ['status' => 'payment_completed']) }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'payment_completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Payment Completed
                    <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['payment_completed'] }}</span>
                </a>
                
                <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'processing' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Processing
                    <span class="ml-2 bg-yellow-100 text-yellow-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['processing'] }}</span>
                </a>
                
                <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'shipping' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Shipping
                    <span class="ml-2 bg-indigo-100 text-indigo-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['shipping'] }}</span>
                </a>
                
                <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'completed' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Completed
                    <span class="ml-2 bg-green-100 text-green-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['completed'] }}</span>
                </a>
                
                <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'cancelled' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Cancelled
                    <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2.5 rounded-full text-xs">{{ $statusCounts['cancelled'] }}</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="status" value="{{ request('status') }}">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Orders</label>
                <input type="text" 
                       name="search" 
                       value="{{ $filters['search'] }}"
                       placeholder="Order number, customer name, email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ $filters['date_from'] }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" 
                       name="date_to" 
                       value="{{ $filters['date_to'] }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">
                    Orders ({{ $orders->total() }})
                </h3>
                
                <!-- Bulk Actions (Hidden by default) -->
                <div id="bulkActions" class="hidden flex items-center space-x-3">
                    <select id="bulkStatus" class="px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Action</option>
                        <option value="processing">Mark as Processing</option>
                        <option value="shipping">Mark as Shipping</option>
                        <option value="completed">Mark as Completed</option>
                        <option value="cancelled">Mark as Cancelled</option>
                    </select>
                    <button onclick="executeBulkAction()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Apply
                    </button>
                    <button onclick="hideBulkActions()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Items
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" 
                                       class="order-checkbox rounded border-gray-300" 
                                       value="{{ $order->id }}">
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $order->order_number }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ strtoupper($order->payment_method) }}
                                        @if($order->payment_reference)
                                            | Ref: {{ $order->payment_reference }}
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $order->shipping_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->user->email ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->shipping_phone }}</div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->items->count() }} items</div>
                                <div class="text-xs text-gray-500">
                                    Total Qty: {{ $order->items->sum('quantity') }}
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    LKR {{ number_format($order->total_amount, 2) }}
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="text-xs text-green-600">
                                        Discount: LKR {{ number_format($order->discount_amount, 2) }}
                                    </div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
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
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-xs">{{ $order->created_at->format('H:i:s') }}</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <button onclick="showStatusModal({{ $order->id }}, '{{ $order->status }}')" 
                                            class="text-yellow-600 hover:text-yellow-900" title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <a href="{{ route('admin.orders.invoice', $order) }}" 
                                       class="text-green-600 hover:text-green-900" title="Generate Invoice">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                                <p class="text-lg">No orders found</p>
                                <p class="text-sm">Orders will appear here once customers start placing them.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Order Status</h3>
            
            <form id="statusForm" onsubmit="updateOrderStatus(event)">
                <input type="hidden" id="orderId" name="order_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select id="newStatus" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Status</option>
                        <option value="processing">Processing</option>
                        <option value="shipping">Shipping</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
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
let selectedOrders = [];

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedOrders();
});

// Individual checkbox handling
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('order-checkbox')) {
        updateSelectedOrders();
    }
});

function updateSelectedOrders() {
    selectedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked'))
                          .map(checkbox => checkbox.value);
    
    // Update select all checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    const totalCheckboxes = document.querySelectorAll('.order-checkbox').length;
    const checkedCheckboxes = selectedOrders.length;
    
    selectAllCheckbox.checked = checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0;
    selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
}

function showBulkActions() {
    updateSelectedOrders();
    if (selectedOrders.length === 0) {
        alert('Please select at least one order to perform bulk actions.');
        return;
    }
    document.getElementById('bulkActions').classList.remove('hidden');
}

function hideBulkActions() {
    document.getElementById('bulkActions').classList.add('hidden');
    document.getElementById('bulkStatus').value = '';
}

function executeBulkAction() {
    const status = document.getElementById('bulkStatus').value;
    if (!status) {
        alert('Please select an action.');
        return;
    }
    
    if (selectedOrders.length === 0) {
        alert('Please select at least one order.');
        return;
    }
    
    if (!confirm(`Are you sure you want to update ${selectedOrders.length} orders to ${status}?`)) {
        return;
    }
    
    showLoading();
    
    fetch('{{ route("admin.orders.bulk-update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            order_ids: selectedOrders,
            status: status,
            comment: 'Bulk status update'
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        alert('Error performing bulk action: ' + error.message);
    });
}

function showStatusModal(orderId, currentStatus) {
    document.getElementById('orderId').value = orderId;
    
    // Filter status options based on current status
    const statusSelect = document.getElementById('newStatus');
    const options = statusSelect.querySelectorAll('option');
    
    // Reset all options
    options.forEach(option => {
        option.style.display = 'block';
        option.disabled = false;
    });
    
    // Define valid transitions
    const validTransitions = {
        'payment_completed': ['processing', 'cancelled'],
        'processing': ['shipping', 'cancelled'],
        'shipping': ['completed'],
        'completed': [],
        'cancelled': []
    };
    
    const allowedStatuses = validTransitions[currentStatus] || [];
    
    // Hide/disable invalid options
    options.forEach(option => {
        if (option.value && !allowedStatuses.includes(option.value)) {
            option.style.display = 'none';
            option.disabled = true;
        }
    });
    
    document.getElementById('statusModal').classList.remove('hidden');
}

function hideStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusForm').reset();
}

function updateOrderStatus(event) {
    event.preventDefault();
    
    const orderId = document.getElementById('orderId').value;
    const status = document.getElementById('newStatus').value;
    const comment = document.getElementById('statusComment').value;
    
    if (!status) {
        alert('Please select a status.');
        return;
    }
    
    showLoading();
    
    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        hideStatusModal();
        
        if (data.success) {
            alert('Order status updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        hideStatusModal();
        alert('Error updating order status: ' + error.message);
    });
}

function exportOrders() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', '1');
    
    window.location.href = `{{ route('admin.orders.export') }}?${params.toString()}`;
}

function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
    document.getElementById('loadingOverlay').classList.add('flex');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
    document.getElementById('loadingOverlay').classList.remove('flex');
}

// Auto-refresh order statistics every 30 seconds
setInterval(function() {
    fetch('{{ route("admin.orders.statistics") }}')
        .then(response => response.json())
        .then(data => {
            // Update status counts if elements exist
            Object.keys(data).forEach(key => {
                const element = document.querySelector(`[data-stat="${key}"]`);
                if (element) {
                    element.textContent = data[key];
                }
            });
        })
        .catch(error => {
            console.log('Error refreshing statistics:', error);
        });
}, 30000);

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideStatusModal();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Escape key to close modal
    if (e.key === 'Escape') {
        hideStatusModal();
        hideBulkActions();
    }
    
    // Ctrl+A to select all orders
    if (e.ctrlKey && e.key === 'a' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
        document.getElementById('selectAll').checked = true;
        document.getElementById('selectAll').dispatchEvent(new Event('change'));
    }
});
</script>
@endpush

@push('styles')
<style>
/* Custom checkbox styling */
.order-checkbox:checked {
    background-color: #3B82F6;
    border-color: #3B82F6;
}

/* Hover effects for table rows */
tbody tr:hover {
    background-color: #F9FAFB;
}

/* Status badge animations */
.status-badge {
    transition: all 0.2s ease;
}

/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Modal animations */
#statusModal {
    transition: opacity 0.25s ease;
}

/* Responsive table */
@media (max-width: 768px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
}
</style>
@endpush