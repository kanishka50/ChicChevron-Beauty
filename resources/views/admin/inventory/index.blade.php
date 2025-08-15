@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="container-fluid px-4 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Inventory Management</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.inventory.movements') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                View Movements
            </a>
            <a href="{{ route('admin.inventory.export') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Report
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Total Products</h3>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
        </div>

        <!-- Low Stock -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Low Stock</h3>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['low_stock'] }}</p>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Out of Stock</h3>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['out_of_stock'] }}</p>
        </div>

        <!-- Total Value -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Total Value</h3>
            <p class="text-2xl font-bold text-gray-900">LKR {{ number_format($stats['total_value'], 2) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[300px]">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by product name or SKU..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="w-48">
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="good" {{ request('status') == 'good' ? 'selected' : '' }}>Good Stock</option>
                </select>
            </div>
            
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                Filter
            </button>
            
            <a href="{{ route('admin.inventory.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md transition-colors">
                Clear
            </a>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-64">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-48">Variant</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-40">SKU</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-48">Stock Level</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-28 text-center">Reserved</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-28 text-center">Available</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($inventories as $inventory)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-lg object-cover border border-gray-200" 
                                             src="{{ Storage::url($inventory->product->main_image) }}" 
                                             alt="{{ $inventory->product->name }}">
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $inventory->product->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $inventory->product->brand->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($inventory->productVariant)
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $inventory->productVariant->name }}</p>
                                        <div class="text-xs text-gray-500 mt-0.5 space-x-2">
                                            @if($inventory->productVariant->size) 
                                                <span>Size: {{ $inventory->productVariant->size }}</span>
                                            @endif
                                            @if($inventory->productVariant->color) 
                                                <span>Color: {{ $inventory->productVariant->color }}</span>
                                            @endif
                                            @if($inventory->productVariant->scent) 
                                                <span>Scent: {{ $inventory->productVariant->scent }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Standard</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-900 font-mono">
                                    {{ $inventory->productVariant->sku ?? $inventory->product->sku ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900 mr-3">{{ $inventory->current_stock }}</span>
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        @php
                                            $stockPercentage = $inventory->low_stock_threshold > 0 
                                                ? min(100, ($inventory->current_stock / ($inventory->low_stock_threshold * 5)) * 100)
                                                : 0;
                                            $stockClass = $stockPercentage > 50 ? 'bg-green-500' : ($stockPercentage > 20 ? 'bg-yellow-500' : 'bg-red-500');
                                        @endphp
                                        <div class="{{ $stockClass }} h-2 rounded-full transition-all duration-300" style="width: {{ $stockPercentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-sm text-gray-600">{{ $inventory->reserved_stock }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-sm font-semibold {{ $inventory->available_stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $inventory->available_stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="openAddStockModal({{ $inventory->product_id }}, {{ $inventory->product_variant_id ?? 'null' }})" 
                                            class="text-green-600 hover:text-green-800 transition-colors"
                                            title="Add Stock">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                    
                                    <button onclick="openAdjustStockModal({{ $inventory->product_id }}, {{ $inventory->product_variant_id ?? 'null' }}, {{ $inventory->current_stock }})" 
                                            class="text-blue-600 hover:text-blue-800 transition-colors"
                                            title="Adjust Stock">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                    <button onclick="viewStockDetails({{ $inventory->product_id }}, {{ $inventory->product_variant_id ?? 'null' }})" 
                                            class="text-gray-600 hover:text-gray-800 transition-colors"
                                            title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <p class="text-gray-500 mb-2">No inventory records found</p>
                                    <p class="text-sm text-gray-400">Start by adding products to manage inventory</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($inventories->hasPages())
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                {{ $inventories->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Modals -->
    @include('admin.inventory.partials.add-stock-modal')
    @include('admin.inventory.partials.adjust-stock-modal')
    @include('admin.inventory.partials.stock-details-modal')
</div>
@endsection

@push('scripts')
@include('admin.inventory.partials.inventory-scripts')
@endpush