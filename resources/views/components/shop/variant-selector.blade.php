<div class="variant-selector" data-product-id="{{ $product->id }}">
    @if($product->has_multiple_variants)
        <div class="space-y-4">
            @php
                $sizes = $product->variants->pluck('size')->filter()->unique();
                $colors = $product->variants->pluck('color')->filter()->unique();
                $scents = $product->variants->pluck('scent')->filter()->unique();
            @endphp
            
            @if($sizes->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sizes as $size)
                            <button type="button" 
                                    class="variant-option size-option px-4 py-2 border rounded-md hover:border-pink-500"
                                    data-variant-type="size"
                                    data-value="{{ $size }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if($colors->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($colors as $color)
                            <button type="button" 
                                    class="variant-option color-option px-4 py-2 border rounded-md hover:border-pink-500"
                                    data-variant-type="color"
                                    data-value="{{ $color }}">
                                {{ $color }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if($scents->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Scent</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($scents as $scent)
                            <button type="button" 
                                    class="variant-option scent-option px-4 py-2 border rounded-md hover:border-pink-500"
                                    data-variant-type="scent"
                                    data-value="{{ $scent }}">
                                {{ $scent }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <input type="hidden" id="selected-variant-id" name="product_variant_id" value="">
        
        <div class="mt-4">
            <div class="price-display">
                <span class="text-2xl font-bold text-gray-900" id="variant-price">
                    Select options
                </span>
            </div>
            <div class="stock-display mt-2">
                <span class="text-sm text-gray-600" id="variant-stock">
                    Select options to see availability
                </span>
            </div>
        </div>
    @else
        @php $defaultVariant = $product->variants->first(); @endphp
        <input type="hidden" name="product_variant_id" value="{{ $defaultVariant->id }}">
        <div class="price-display">
            <span class="text-2xl font-bold text-gray-900">
                Rs. {{ number_format($defaultVariant->price, 2) }}
            </span>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantSelector = document.querySelector('.variant-selector');
    if (!variantSelector) return;
    
    const variants = @json($product->variants);
    let selectedOptions = {
        size: null,
        color: null,
        scent: null
    };
    
    // Handle variant selection
    document.querySelectorAll('.variant-option').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.variantType;
            const value = this.dataset.value;
            
            // Update visual state
            document.querySelectorAll(`.${type}-option`).forEach(btn => {
                btn.classList.remove('border-pink-500', 'bg-pink-100');
            });
            this.classList.add('border-pink-500', 'bg-pink-100');
            
            // Update selection
            selectedOptions[type] = value;
            
            // Find matching variant
            updateSelectedVariant();
        });
    });
    
    function updateSelectedVariant() {
        const matchingVariant = variants.find(v => {
            return (!selectedOptions.size || v.size === selectedOptions.size) &&
                   (!selectedOptions.color || v.color === selectedOptions.color) &&
                   (!selectedOptions.scent || v.scent === selectedOptions.scent);
        });
        
        if (matchingVariant) {
            document.getElementById('selected-variant-id').value = matchingVariant.id;
            document.getElementById('variant-price').textContent = 
                'Rs. ' + new Intl.NumberFormat().format(matchingVariant.price);
            
            const stockText = matchingVariant.available_stock > 0 
                ? `${matchingVariant.available_stock} in stock`
                : 'Out of stock';
            document.getElementById('variant-stock').textContent = stockText;
            
            // Enable/disable add to cart button
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.disabled = matchingVariant.available_stock === 0;
            }
        }
    }
});
</script>