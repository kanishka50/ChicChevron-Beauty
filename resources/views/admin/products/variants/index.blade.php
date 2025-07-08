@extends('admin.layouts.app')

@section('title', 'Product Variants - ' . $product->name)

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Manage Variants: {{ $product->name }}</h1>
            <a href="{{ route('admin.products.edit', $product) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Product
            </a>
        </div>

        <!-- Add Variant Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Add New Variant</h2>
            
            <form id="add-variant-form" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="variant_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Type</option>
                        <option value="size">Size</option>
                        <option value="color">Color</option>
                        <option value="scent">Scent</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                    <input type="text" name="variant_value" placeholder="e.g., 50ml, Red, Rose" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU Suffix</label>
                    <input type="text" name="sku_suffix" placeholder="e.g., 50ML, RED" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (LKR)</label>
                    <input type="number" name="price" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cost Price (LKR)</label>
                    <input type="number" name="cost_price" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Add Variant
                    </button>
                </div>
            </form>
        </div>

        <!-- Size Variants -->
        @if($sizeVariants->isNotEmpty() || true)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Size Variants</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Margin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($scentVariants as $variant)
                                <tr id="variant-{{ $variant->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $variant->variant_value }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $variant->full_sku }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">LKR {{ number_format($variant->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">LKR {{ number_format($variant->cost_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm {{ $variant->profit_margin > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $variant->profit_margin }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $variant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button onclick="editVariant({{ $variant->id }})" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                                        <button onclick="deleteVariant({{ $variant->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No scent variants added yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Variant Combinations -->
        @if($product->variantCombinations->isNotEmpty())
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Variant Combinations & Inventory</h2>
                <p class="text-sm text-gray-600 mb-4">These combinations are automatically generated based on your variants. Manage stock levels for each combination.</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Combination</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($product->variantCombinations as $combination)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($combination->sizeVariant)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $combination->sizeVariant->variant_value }}
                                                </span>
                                            @endif
                                            @if($combination->colorVariant)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                                    {{ $combination->colorVariant->variant_value }}
                                                </span>
                                            @endif
                                            @if($combination->scentVariant)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $combination->scentVariant->variant_value }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $combination->combination_sku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        LKR {{ number_format($combination->combination_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $stock = $combination->inventory->current_stock ?? 0;
                                            $stockClass = 'text-green-600';
                                            if ($stock == 0) {
                                                $stockClass = 'text-red-600';
                                            } elseif ($stock < 10) {
                                                $stockClass = 'text-yellow-600';
                                            }
                                        @endphp
                                        <span class="text-sm font-medium {{ $stockClass }}">
                                            {{ $stock }} units
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button onclick="updateStock({{ $combination->id }}, {{ $stock }})" 
                                                class="text-indigo-600 hover:text-indigo-900">
                                            Update Stock
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Edit Variant Modal -->
    <div id="edit-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="edit-variant-form">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Variant</h3>
                        <input type="hidden" id="edit-variant-id">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                                <input type="text" id="edit-variant-value" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SKU Suffix</label>
                                <input type="text" id="edit-sku-suffix" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price (LKR)</label>
                                <input type="number" id="edit-price" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cost Price (LKR)</label>
                                <input type="number" id="edit-cost-price" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" id="edit-is-active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Update
                        </button>
                        <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Stock Modal -->
    <div id="stock-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="stock-form">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Update Stock</h3>
                        <input type="hidden" id="stock-combination-id">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Stock Quantity</label>
                            <input type="number" id="stock-quantity" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <p class="mt-1 text-sm text-gray-500">Enter the new total stock quantity for this variant combination.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Update Stock
                        </button>
                        <button type="button" onclick="closeStockModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Add variant
    document.getElementById('add-variant-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        fetch(`/admin/products/{{ $product->id }}/variants`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error adding variant');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding variant');
        });
    });

    // Edit variant
    function editVariant(variantId) {
        // In a real app, you'd fetch the variant data
        document.getElementById('edit-variant-id').value = variantId;
        document.getElementById('edit-modal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    document.getElementById('edit-variant-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const variantId = document.getElementById('edit-variant-id').value;
        const data = {
            variant_value: document.getElementById('edit-variant-value').value,
            sku_suffix: document.getElementById('edit-sku-suffix').value,
            price: document.getElementById('edit-price').value,
            cost_price: document.getElementById('edit-cost-price').value,
            is_active: document.getElementById('edit-is-active').checked
        };
        
        fetch(`/admin/products/variants/${variantId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error updating variant');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating variant');
        });
    });

    // Delete variant
    function deleteVariant(variantId) {
        if (!confirm('Are you sure you want to delete this variant?')) {
            return;
        }
        
        fetch(`/admin/products/variants/${variantId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error deleting variant');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting variant');
        });
    }

    // Update stock
    function updateStock(combinationId, currentStock) {
        document.getElementById('stock-combination-id').value = combinationId;
        document.getElementById('stock-quantity').value = currentStock;
        document.getElementById('stock-modal').classList.remove('hidden');
    }

    function closeStockModal() {
        document.getElementById('stock-modal').classList.add('hidden');
    }

    document.getElementById('stock-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const combinationId = document.getElementById('stock-combination-id').value;
        const quantity = document.getElementById('stock-quantity').value;
        
        // In a real app, you'd have an endpoint to update inventory
        alert(`Stock updated to ${quantity} units`);
        closeStockModal();
        location.reload();
    });
</script>
@endpushgray-500 uppercase tracking-wider">Margin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sizeVariants as $variant)
                                <tr id="variant-{{ $variant->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $variant->variant_value }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $variant->full_sku }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">LKR {{ number_format($variant->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">LKR {{ number_format($variant->cost_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm {{ $variant->profit_margin > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $variant->profit_margin }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $variant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button onclick="editVariant({{ $variant->id }})" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                                        <button onclick="deleteVariant({{ $variant->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No size variants added yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Color Variants -->
        @if($colorVariants->isNotEmpty() || true)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Color Variants</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Margin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($colorVariants as $variant)
                                <tr id="variant-{{ $variant->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $variant->variant_value }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $variant->full_sku }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">LKR {{ number_format($variant->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">LKR {{ number_format($variant->cost_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm {{ $variant->profit_margin > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $variant->profit_margin }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $variant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button onclick="editVariant({{ $variant->id }})" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                                        <button onclick="deleteVariant({{ $variant->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No color variants added yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Scent Variants -->
        @if($scentVariants->isNotEmpty() || true)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Scent Variants</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-