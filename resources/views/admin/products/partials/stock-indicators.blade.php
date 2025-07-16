{{-- resources/views/admin/products/partials/stock-indicators.blade.php --}}
{{-- Stock Level Visual Indicators --}}
@php
    // Calculate stock percentage
    $stockPercentage = 0;
    $stockStatus = 'out-of-stock';
    $stockText = 'Out of Stock';
    $totalStock = 0;
    $lowStockThreshold = 10;

    if (isset($product)) {
        if ($product->has_variants) {
            // For products with variants, calculate total stock from variants
            $totalStock = $product->variants->sum(function($variant) {
                return $variant->inventory ? $variant->inventory->current_stock : 0;
            });
            $lowStockThreshold = $product->variants->max(function($variant) {
                return $variant->inventory ? $variant->inventory->low_stock_threshold : 10;
            });
        } else {
            // For simple products, get stock from default variant
            $defaultVariant = $product->defaultVariant();
            if ($defaultVariant && $defaultVariant->inventory) {
                $totalStock = $defaultVariant->inventory->current_stock;
                $lowStockThreshold = $defaultVariant->inventory->low_stock_threshold;
            }
        }

        if ($lowStockThreshold > 0) {
            $stockPercentage = min(100, ($totalStock / ($lowStockThreshold * 5)) * 100);
        }
        
        if ($totalStock <= 0) {
            $stockStatus = 'out-of-stock';
            $stockText = 'Out of Stock';
        } elseif ($totalStock <= $lowStockThreshold * 0.5) {
            $stockStatus = 'critical';
            $stockText = 'Critical Stock';
        } elseif ($totalStock <= $lowStockThreshold) {
            $stockStatus = 'low';
            $stockText = 'Low Stock';
        } else {
            $stockStatus = 'good';
            $stockText = 'In Stock';
        }
    }
@endphp

{{-- Stock Level Bar --}}
<div class="stock-indicator mb-2">
    <div class="flex justify-between items-center mb-1">
        <span class="text-xs font-medium text-gray-700">Stock Level</span>
        <span class="text-xs text-gray-500">{{ $totalStock }} units</span>
    </div>
    
    <div class="w-full bg-gray-200 rounded-full h-2">
        <div class="h-2 rounded-full transition-all duration-300 ease-in-out
            @if($stockStatus === 'good') bg-green-500
            @elseif($stockStatus === 'low') bg-yellow-500 
            @elseif($stockStatus === 'critical') bg-orange-500
            @else bg-red-500
            @endif"
            style="width: {{ $stockPercentage }}%">
        </div>
    </div>
    
    <div class="flex justify-between items-center mt-1">
        <span class="text-xs font-medium
            @if($stockStatus === 'good') text-green-600
            @elseif($stockStatus === 'low') text-yellow-600
            @elseif($stockStatus === 'critical') text-orange-600  
            @else text-red-600
            @endif">
            {{ $stockText }}
        </span>
        
        @if($stockStatus !== 'out-of-stock')
            <span class="text-xs text-gray-400">
                Threshold: {{ $lowStockThreshold }}
            </span>
        @endif
    </div>
</div>

{{-- Stock Status Badge --}}
<div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
    @if($stockStatus === 'good') bg-green-100 text-green-800
    @elseif($stockStatus === 'low') bg-yellow-100 text-yellow-800
    @elseif($stockStatus === 'critical') bg-orange-100 text-orange-800
    @else bg-red-100 text-red-800
    @endif">
    
    {{-- Status Icon --}}
    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
        @if($stockStatus === 'good')
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        @elseif($stockStatus === 'low' || $stockStatus === 'critical')
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        @else
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        @endif
    </svg>
    
    {{ $stockText }}
</div>

{{-- Detailed Stock Info (for expanded view) --}}
@if(isset($detailed) && $detailed)
    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
        <h4 class="text-sm font-medium text-gray-700 mb-2">Stock Details</h4>
        
        @if(isset($product) && $product->has_variants)
            <div class="space-y-2">
                @foreach($product->variants as $variant)
                    @php
                        $variantStock = $variant->inventory ? $variant->inventory->current_stock : 0;
                        $variantThreshold = $variant->inventory ? $variant->inventory->low_stock_threshold : 10;
                        $variantStatus = $variantStock <= 0 ? 'out' : ($variantStock <= $variantThreshold ? 'low' : 'good');
                    @endphp
                    
                    <div class="flex justify-between items-center p-2 bg-white rounded border
                        @if($variantStatus === 'out') border-red-200
                        @elseif($variantStatus === 'low') border-yellow-200
                        @else border-green-200
                        @endif">
                        
                        <div class="flex-1">
                            <span class="text-sm font-medium text-gray-900">{{ $variant->name }}</span>
                            <span class="text-xs text-gray-500 ml-2">({{ $variant->sku }})</span>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <span class="text-sm font-medium
                                @if($variantStatus === 'out') text-red-600
                                @elseif($variantStatus === 'low') text-yellow-600
                                @else text-green-600
                                @endif">
                                {{ $variantStock }} units
                            </span>
                            
                            @if(isset($showActions) && $showActions)
                                <a href="{{ route('admin.inventory.index') }}?search={{ $variant->sku }}" 
                                   class="text-xs text-blue-600 hover:text-blue-800">
                                    Manage Stock
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-sm text-gray-600">
                @php
                    $defaultVariant = $product->defaultVariant();
                    $stock = $defaultVariant && $defaultVariant->inventory ? $defaultVariant->inventory->current_stock : 0;
                @endphp
                <p>Single variant product</p>
                <p class="font-medium">Current stock: {{ $stock }} units</p>
            </div>
        @endif
    </div>
@endif