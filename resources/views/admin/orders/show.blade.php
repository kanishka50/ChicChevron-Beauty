@extends('admin.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="container-fluid px-4 max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Order {{ $order->order_number }}</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    @switch($order->status)
                        @case('processing')
                            bg-blue-100 text-blue-700
                            @break
                        @case('shipping')
                            bg-indigo-100 text-indigo-700
                            @break
                        @case('completed')
                            bg-green-100 text-green-700
                            @break
                        @case('cancelled')
                            bg-red-100 text-red-700
                            @break
                        @default
                            bg-gray-100 text-gray-700
                    @endswitch
                ">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            <p class="text-sm text-gray-600 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t H:i:s') }}</p>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="showStatusModal()" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Update Status
            </button>
            <a href="{{ route('admin.orders.invoice', $order) }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Generate Invoice
            </a>
            <button onclick="addNote()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Add Note
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Order Items ({{ $order->items->count() }})</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <img class="h-20 w-20 rounded-lg object-cover border border-gray-200" 
                                         src="{{ Storage::url($item->product->main_image) }}" 
                                         alt="{{ $item->product_name }}">
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">SKU: {{ $item->product_sku }}</p>
                                            
                                            @if($item->productVariant)
                                                <div class="mt-1">
                                                    <p class="text-sm text-gray-600">{{ $item->productVariant->display_name }}</p>
                                                    <p class="text-xs text-gray-500">Variant SKU: {{ $item->productVariant->sku }}</p>
                                                </div>
                                            @endif
                                            
                                            <div class="mt-2 text-sm text-gray-600">
                                                <span class="font-medium">Brand:</span> {{ $item->product->brand->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600">
                                                LKR {{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}
                                            </div>
                                            @if($item->discount_amount > 0)
                                                <div class="text-sm text-green-600 mt-1">
                                                    Discount: -LKR {{ number_format($item->discount_amount, 2) }}
                                                </div>
                                            @endif
                                            <div class="text-lg font-semibold text-gray-900 mt-2">
                                                LKR {{ number_format($item->total_price, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Cost: LKR {{ number_format($item->cost_price * $item->quantity, 2) }}
                                            </div>
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
                            <span class="font-medium text-gray-900">LKR {{ number_format($order->subtotal, 2) }}</span>
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
                                <span class="font-medium text-gray-900">LKR {{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-semibold pt-3 mt-3 border-t border-gray-300">
                            <span>Total:</span>
                            <span>LKR {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Status History</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($order->statusHistory as $index => $history)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-300"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                    @switch($history->status)
                                                        @case('processing')
                                                            bg-blue-500
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
                                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between">
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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Customer Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <p class="text-sm text-gray-900">{{ $order->user->name ?? $order->shipping_name }}</p>
                        <p class="text-sm text-gray-600">{{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <p class="text-sm text-gray-900">{{ $order->shipping_phone }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                        <p class="text-sm text-gray-900">{{ $order->shipping_name }}</p>
                        <p class="text-sm text-gray-600">{{ $order->full_shipping_address }}</p>
                    </div>
                    
                    @if($order->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order Notes</label>
                            <p class="text-sm text-gray-900">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Payment Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <p class="text-sm text-gray-900">{{ strtoupper($order->payment_method) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($order->payment_status === 'completed') bg-green-100 text-green-700
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif
                        ">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    
                    @if($order->payment_reference)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Reference</label>
                            <p class="text-sm text-gray-900">{{ $order->payment_reference }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profit Analysis -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Profit Analysis</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Revenue:</span>
                        <span class="text-sm font-medium text-gray-900">LKR {{ number_format($profitAnalysis['total_revenue'], 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Cost:</span>
                        <span class="text-sm font-medium text-gray-900">LKR {{ number_format($profitAnalysis['total_cost'], 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between pt-3 mt-3 border-t border-gray-200">
                        <span class="text-sm font-medium text-gray-900">Gross Profit:</span>
                        <span class="text-sm font-semibold {{ $profitAnalysis['gross_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            LKR {{ number_format($profitAnalysis['gross_profit'], 2) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-900">Profit Margin:</span>
                        <span class="text-sm font-semibold {{ $profitAnalysis['profit_margin'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($profitAnalysis['profit_margin'], 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
                </div>
                <div class="p-4 space-y-2">
                    @if($order->can_be_cancelled)
                        <button onclick="updateStatus('cancelled')"
                                class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel Order
                        </button>
                    @endif

                    @if($order->status === 'processing')
                        <button onclick="updateStatus('shipping')" 
                                class="w-full text-left px-4 py-3 text-sm text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                            </svg>
                            Mark as Shipping
                        </button>
                    @endif
                    
                    @if($order->status === 'shipping')
                        <button onclick="updateStatus('completed')" 
                                class="w-full text-left px-4 py-3 text-sm text-green-600 hover:bg-green-50 rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mark as Completed
                        </button>
                    @endif
                    
                    <button onclick="sendNotification()" 
                            class="w-full text-left px-4 py-3 text-sm text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Notification
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Update Order Status</h3>
            
            <form id="statusForm" onsubmit="submitStatusUpdate(event)">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select id="newStatus" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Status</option>
                        @switch($order->status)
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment <span class="text-gray-500">(Optional)</span></label>
                    <textarea id="statusComment" 
                              name="comment" 
                              rows="3" 
                              placeholder="Add a note about this status change..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="notify_customer" value="1" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mr-2" id="notifyCustomer">
                        <span class="text-sm text-gray-600">Send email notification to customer</span>
                    </label>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="hideStatusModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div id="noteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Add Internal Note</h3>
            
            <form id="noteForm" onsubmit="submitNote(event)">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                    <textarea id="noteText" 
                              name="note" 
                              rows="4" 
                              placeholder="Add internal note for this order..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                              required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="hideNoteModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        Add Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

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
    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Updating...';
    
    fetch(`{{ route('admin.orders.update-status', $order) }}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            status: status,
            comment: comment,
            notify_customer: true
        })
    })
    .then(response => {
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
            
            setTimeout(() => {
                window.location.reload(true);
            }, 1000);
        } else {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
            showErrorNotification(data.message || 'Failed to update order status');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
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

@push('styles')
<style>
/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endpush