<div class="variant-selector space-y-4" x-data="variantSelector()">
    @if($variants->count() > 1)
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Select Variant</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($variants as $variant)
                    <button type="button"
                            @click="selectVariant({{ $variant->toJson() }})"
                            :class="{
                                'ring-2 ring-pink-500': selectedVariant?.id === {{ $variant->id }},
                                'opacity-50 cursor-not-allowed': {{ $variant->available_stock }} <= 0
                            }"
                            @if($variant->available_stock <= 0) disabled @endif
                            class="relative px-4 py-3 border border-gray-300 rounded-lg text-left hover:border-gray-400 transition-colors">
                        <div class="font-medium text-gray-900">{{ $variant->name }}</div>
                        <div class="text-sm text-gray-600">Rs. {{ number_format($variant->price, 2) }}</div>
                        @if($variant->available_stock <= 0)
                            <div class="text-xs text-red-600 mt-1">Out of Stock</div>
                        @else
                            <div class="text-xs text-green-600 mt-1">In Stock</div>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    @else
        {{-- Single variant - auto-select it --}}
        <div x-init="selectVariant({{ $variants->first()->toJson() }})"></div>
    @endif
    
    {{-- Selected variant details --}}
    <div x-show="selectedVariant" x-cloak class="mt-4 p-4 bg-gray-50 rounded-lg">
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Price:</span>
                <span class="font-medium" x-text="'Rs. ' + formatPrice(selectedVariant?.price)"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">SKU:</span>
                <span class="text-sm" x-text="selectedVariant?.sku"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Availability:</span>
                <span class="text-sm" 
                      :class="selectedVariant?.available_stock > 0 ? 'text-green-600' : 'text-red-600'"
                      x-text="selectedVariant?.available_stock > 0 ? 'In Stock' : 'Out of Stock'"></span>
            </div>
        </div>
    </div>
    
    {{-- Hidden input for form submission --}}
    <input type="hidden" name="product_variant_id" x-model="selectedVariant?.id">
</div>

<script>
function variantSelector() {
    return {
        selectedVariant: null,
        
        selectVariant(variant) {
            this.selectedVariant = variant;
            // Emit event for other components to listen to
            this.$dispatch('variant-selected', variant);
        },
        
        formatPrice(price) {
            return parseFloat(price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    }
}
</script>