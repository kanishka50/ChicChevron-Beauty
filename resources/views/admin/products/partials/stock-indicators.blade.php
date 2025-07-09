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
            // For products with variants, calculate total stock from combinations
            $totalStock = $product->variantCombinations->sum(function($combination) {
                return $combination->inventory ? $combination->inventory->current_stock : 0;
            });
            $lowStockThreshold = $product->variantCombinations->max(function($combination) {
                return $combination->inventory ? $combination->inventory->low_stock_threshold : 10;
            });
        } else {
            // For simple products, get stock from main inventory
            $totalStock = $product->inventory ? $product->inventory->current_stock : 0;
            $lowStockThreshold = $product->inventory ? $product->inventory->low_stock_threshold : 10;
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
                @foreach($product->variantCombinations as $combination)
                    @php
                        $variantStock = $combination->inventory ? $combination->inventory->current_stock : 0;
                        $variantThreshold = $combination->inventory ? $combination->inventory->low_stock_threshold : 10;
                        $variantStatus = $variantStock <= 0 ? 'out' : ($variantStock <= $variantThreshold ? 'low' : 'good');
                    @endphp
                    
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center space-x-2">
                            @if($combination->sizeVariant)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $combination->sizeVariant->variant_value }}</span>
                            @endif
                            @if($combination->colorVariant)
                                <span class="px-2 py-1 bg-pink-100 text-pink-800 rounded text-xs">{{ $combination->colorVariant->variant_value }}</span>
                            @endif
                            @if($combination->scentVariant)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">{{ $combination->scentVariant->variant_value }}</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <span class="font-medium {{ $variantStatus === 'out' ? 'text-red-600' : ($variantStatus === 'low' ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $variantStock }} units
                            </span>
                            
                            {{-- Mini progress bar for variant --}}
                            <div class="w-12 bg-gray-200 rounded-full h-1">
                                <div class="h-1 rounded-full 
                                    {{ $variantStatus === 'out' ? 'bg-red-500' : ($variantStatus === 'low' ? 'bg-yellow-500' : 'bg-green-500') }}"
                                    style="width: {{ $variantThreshold > 0 ? min(100, ($variantStock / ($variantThreshold * 2)) * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Current Stock:</span>
                    <span class="font-medium">{{ $totalStock }} units</span>
                </div>
                <div class="flex justify-between">
                    <span>Low Stock Alert:</span>
                    <span class="font-medium">{{ $lowStockThreshold }} units</span>
                </div>
                @if($product && $product->inventory)
                    <div class="flex justify-between">
                        <span>Last Updated:</span>
                        <span class="font-medium">{{ $product->inventory->updated_at->diffForHumans() }}</span>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endif

{{-- Quick Actions for Stock Management --}}
@if(isset($showActions) && $showActions && isset($product))
    <div class="mt-3 flex space-x-2">
        @if($product->has_variants)
            <a href="{{ route('admin.products.variants', $product) }}" 
               class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">
                Manage Variants
            </a>
        @else
            <button onclick="updateSingleProductStock({{ $product->id }})" 
                    class="text-xs bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded">
                Update Stock
            </button>
        @endif
        
        @if($stockStatus === 'low' || $stockStatus === 'critical')
            <button onclick="createStockAlert({{ $product->id }})" 
                    class="text-xs bg-orange-500 hover:bg-orange-600 text-white px-2 py-1 rounded">
                Create Alert
            </button>
        @endif
    </div>
@endif

@push('scripts')
<script>
    // Function to update single product stock
    function updateSingleProductStock(productId) {
        // This would open a modal or redirect to inventory management
        console.log('Update stock for product:', productId);
        // Implementation depends on your inventory management setup
    }

    // Function to create stock alert
    function createStockAlert(productId) {
        // This would create a low stock alert/notification
        console.log('Create stock alert for product:', productId);
        // Implementation depends on your notification system
    }
</script>
@endpush