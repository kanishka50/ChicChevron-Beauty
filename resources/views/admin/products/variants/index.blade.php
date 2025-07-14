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
        
        <x-admin.table>
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sizeVariants as $variant)
                    <tr id="variant-{{ $variant->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $variant->variant_value }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $variant->full_sku }}</td>
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
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No size variants added yet</td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>
@endif

<!-- Color Variants -->
@if($colorVariants->isNotEmpty() || true)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Color Variants</h2>
        
        <x-admin.table>
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($colorVariants as $variant)
                    <tr id="variant-{{ $variant->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $variant->variant_value }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $variant->full_sku }}</td>
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
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No color variants added yet</td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>
@endif

<!-- Scent Variants -->
@if($scentVariants->isNotEmpty() || true)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Scent Variants</h2>
        
        <x-admin.table>
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($scentVariants as $variant)
                    <tr id="variant-{{ $variant->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $variant->variant_value }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $variant->full_sku }}</td>
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
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No scent variants added yet</td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>
@endif

<!-- Variant Combinations -->
@if($product->variantCombinations->isNotEmpty())
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Variant Combinations & Pricing</h2>
                <p class="text-sm text-gray-600 mt-1">Set prices and manage inventory for each combination.</p>
            </div>
            <button onclick="openAddCombinationModal()" 
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded text-sm">
                Add Manual Combination
            </button>
        </div>
        
        <x-admin.table>
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Combination</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $combination->combination_sku }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($combination->combination_price > 0)
                                <span class="font-medium">LKR {{ number_format($combination->combination_price, 2) }}</span>
                            @else
                                <span class="text-red-600 text-sm">Not set</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($combination->discount_price)
                                <span class="text-green-600">LKR {{ number_format($combination->discount_price, 2) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($combination->combination_cost_price > 0)
                                <span class="text-gray-600 text-sm">LKR {{ number_format($combination->combination_cost_price, 2) }}</span>
                            @else
                                <span class="text-gray-400 text-sm">Not set</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm {{ $combination->inventory && $combination->inventory->current_stock > 10 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $combination->inventory ? $combination->inventory->current_stock : 0 }} units
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $combination->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $combination->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="editCombination({{ $combination->id }})" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit Price</button>
                            <button onclick="updateStock({{ $combination->id }})" class="text-green-600 hover:text-green-900">Stock</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-admin.table>
    </div>
@else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <p class="text-yellow-800">
            <svg class="inline w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            No variant combinations yet. Add variants above to create combinations.
        </p>
    </div>
@endif

<!-- Add Variant Modal -->
@include('admin.products.partials.variant-form')

@push('scripts')
@include('admin.products.partials.variant-scripts')
@endpush
@endsection