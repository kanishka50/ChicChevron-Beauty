@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-semibold text-gray-800">Inventory Management</h1>
            <div class="flex space-x-3">
                <button onclick="openAddStockModal()" 
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Stock
                </button>
                <a href="{{ route('admin.inventory.movements') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Stock Movements
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_products'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Low Stock</dt>
                                <dd class="text-lg font-medium text-yellow-600">{{ $stats['low_stock'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94A2 2 0 0118 6v7a2 2 0 01-2 2h-1m-1 0H9m2 0v4a2 2 0 002 2h2a2 2 0 002-2v-4"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Out of Stock</dt>
                                <dd class="text-lg font-medium text-red-600">{{ $stats['out_of_stock'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Value</dt>
                                <dd class="text-lg font-medium text-green-600">LKR {{ number_format($stats['total_value'], 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by product name or SKU..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="w-48">
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Stock Levels</option>
                        <option value="good" {{ request('status') == 'good' ? 'selected' : '' }}>Good Stock</option>
                        <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                
                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
                
                <a href="{{ route('admin.inventory.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Clear
                </a>
            </form>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <x-admin.table>
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reserved</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($inventories as $inventory)
                    @php
                        $availableStock = $inventory->current_stock - $inventory->reserved_stock;
                        $threshold = $inventory->low_stock_threshold;
                        
                        if ($availableStock <= 0) {
                            $statusClass = 'bg-red-100 text-red-800';
                            $statusText = 'Out of Stock';
                            $progressClass = 'bg-red-500';
                            $percentage = 0;
                        } elseif ($availableStock <= $threshold * 0.5) {
                            $statusClass = 'bg-orange-100 text-orange-800';
                            $statusText = 'Critical';
                            $progressClass = 'bg-orange-500';
                            $percentage = ($availableStock / ($threshold * 2)) * 100;
                        } elseif ($availableStock <= $threshold) {
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                            $statusText = 'Low Stock';
                            $progressClass = 'bg-yellow-500';
                            $percentage = ($availableStock / ($threshold * 2)) * 100;
                        } else {
                            $statusClass = 'bg-green-100 text-green-800';
                            $statusText = 'Good';
                            $progressClass = 'bg-green-500';
                            $percentage = min(100, ($availableStock / ($threshold * 2)) * 100);
                        }
                    @endphp
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" 
                                         src="{{ Storage::url($inventory->product->main_image) }}" 
                                         alt="{{ $inventory->product->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $inventory->product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $inventory->product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($inventory->variantCombination)
                                <div class="text-sm text-gray-900">
                                    @if($inventory->variantCombination->sizeVariant)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                            {{ $inventory->variantCombination->sizeVariant->variant_value }}
                                        </span>
                                    @endif
                                    @if($inventory->variantCombination->colorVariant)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-pink-100 text-pink-800 mr-1">
                                            {{ $inventory->variantCombination->colorVariant->variant_value }}
                                        </span>
                                    @endif
                                    @if($inventory->variantCombination->scentVariant)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            {{ $inventory->variantCombination->scentVariant->variant_value }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-gray-500">No variants</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $inventory->current_stock }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $inventory->reserved_stock }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">{{ $availableStock }}</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-24">
                                    <div class="h-2 rounded-full {{ $progressClass }}" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="addStockToItem({{ $inventory->product_id }}, {{ $inventory->variant_combination_id ?? 'null' }})" 
                                        class="text-green-600 hover:text-green-900"
                                        title="Add Stock">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                
                                <button onclick="adjustStockItem({{ $inventory->product_id }}, {{ $inventory->variant_combination_id ?? 'null' }}, {{ $inventory->current_stock }})" 
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Adjust Stock">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                
                                <button onclick="viewStockDetails({{ $inventory->product_id }}, {{ $inventory->variant_combination_id ?? 'null' }})" 
                                        class="text-gray-600 hover:text-gray-900"
                                        title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No inventory records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.table>
        
        @if($inventories->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $inventories->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Modals -->
    @include('admin.inventory.partials.add-stock-modal')
    @include('admin.inventory.partials.adjust-stock-modal')
    @include('admin.inventory.partials.stock-details-modal')
@endsection

@push('scripts')
@include('admin.inventory.partials.inventory-scripts')
@endpush