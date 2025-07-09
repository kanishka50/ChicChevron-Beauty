@extends('admin.layouts.app')

@section('title', 'Manage Product Variants')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-semibold text-gray-800">Manage Variants: {{ $product->name }}</h1>
        <p class="text-gray-600 mt-1">Add and manage different variants for this product</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.products.show', $product) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Back to Product
        </a>
        <button onclick="openAddVariantModal()" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Add New Variant
        </button>
    </div>
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $combination->scentVariant->variant_value }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $combination->sku }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">LKR {{ number_format($combination->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm {{ $combination->inventory && $combination->inventory->current_stock > 10 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $combination->inventory ? $combination->inventory->current_stock : 0 }} units
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="updateStock({{ $combination->id }})" class="text-indigo-600 hover:text-indigo-900">Update Stock</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Add Variant Modal -->
<div id="addVariantModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Variant</h3>
            <form id="addVariantForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Variant Type</label>
                    <select name="variant_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="size">Size</option>
                        <option value="color">Color</option>
                        <option value="scent">Scent</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Variant Value</label>
                    <input type="text" name="variant_value" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g., Large, Red, Rose">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">SKU Suffix</label>
                    <input type="text" name="sku_suffix" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g., LG, RD, RSE">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" name="price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Cost Price</label>
                    <input type="number" name="cost_price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddVariantModal()" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Add Variant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddVariantModal() {
    document.getElementById('addVariantModal').classList.remove('hidden');
}

function closeAddVariantModal() {
    document.getElementById('addVariantModal').classList.add('hidden');
    document.getElementById('addVariantForm').reset();
}

document.getElementById('addVariantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.products.variants.store", $product) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
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

function editVariant(variantId) {
    // Implement edit variant functionality
    console.log('Edit variant:', variantId);
}

function deleteVariant(variantId) {
    if (confirm('Are you sure you want to delete this variant?')) {
        fetch(`/admin/products/variants/${variantId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`variant-${variantId}`).remove();
            } else {
                alert(data.message || 'Error deleting variant');
            }
        });
    }
}

function updateStock(combinationId) {
    // Implement stock update functionality
    console.log('Update stock for combination:', combinationId);
}
</script>
@endsection