<div class="variant-selector space-y-4" data-product-id="{{ $product->id }}">
    @if($product->has_variants)
        <!-- Size Variants -->
        @if($sizeVariants->isNotEmpty())
            <div class="variant-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($sizeVariants as $variant)
                        <button type="button" 
                                class="variant-option size-variant px-3 py-2 border border-gray-300 rounded-md text-sm hover:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                data-variant-type="size"
                                data-variant-id="{{ $variant->id }}"
                                data-variant-value="{{ $variant->variant_value }}">
                            {{ $variant->variant_value }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Color Variants -->
        @if($colorVariants->isNotEmpty())
            <div class="variant-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($colorVariants as $variant)
                        <button type="button" 
                                class="variant-option color-variant w-8 h-8 rounded-full border-2 border-gray-300 hover:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                data-variant-type="color"
                                data-variant-id="{{ $variant->id }}"
                                data-variant-value="{{ $variant->variant_value }}"
                                style="background-color: {{ $variant->color_hex ?? '#ccc' }}"
                                title="{{ $variant->variant_value }}">
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Scent Variants -->
        @if($scentVariants->isNotEmpty())
            <div class="variant-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">Scent</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($scentVariants as $variant)
                        <button type="button" 
                                class="variant-option scent-variant px-3 py-2 border border-gray-300 rounded-md text-sm hover:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                data-variant-type="scent"
                                data-variant-id="{{ $variant->id }}"
                                data-variant-value="{{ $variant->variant_value }}">
                            {{ $variant->variant_value }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Hidden inputs to store selected variants -->
        <input type="hidden" id="selected-size-variant" name="size_variant_id">
        <input type="hidden" id="selected-color-variant" name="color_variant_id">
        <input type="hidden" id="selected-scent-variant" name="scent_variant_id">
        <input type="hidden" id="selected-combination-id" name="variant_combination_id">

        <!-- Price and Stock Display -->
        <div class="variant-info mt-4">
            <div class="price-display">
                <span class="text-2xl font-bold text-gray-900" id="variant-price">
                    Rs. {{ number_format($product->selling_price, 2) }}
                </span>
            </div>
            <div class="stock-display mt-2">
                <span class="text-sm text-gray-600" id="variant-stock">
                    Select options to see availability
                </span>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantSelector = document.querySelector('.variant-selector');
    if (!variantSelector) return;

    const combinations = @json($variantCombinations);
    const productId = variantSelector.dataset.productId;
    
    let selectedVariants = {
        size: null,
        color: null,
        scent: null
    };

    // Variant selection handling
    variantSelector.addEventListener('click', function(e) {
        if (e.target.classList.contains('variant-option')) {
            const button = e.target;
            const variantType = button.dataset.variantType;
            const variantId = button.dataset.variantId;

            // Remove active class from other options of same type
            const sameTypeButtons = variantSelector.querySelectorAll(`.${variantType}-variant`);
            sameTypeButtons.forEach(btn => {
                btn.classList.remove('border-pink-500', 'bg-pink-100');
                btn.classList.add('border-gray-300');
            });

            // Add active class to selected option
            button.classList.remove('border-gray-300');
            button.classList.add('border-pink-500', 'bg-pink-100');

            // Store selection
            selectedVariants[variantType] = variantId;
            document.getElementById(`selected-${variantType}-variant`).value = variantId;

            // Update combination
            updateVariantCombination();
        }
    });

    function updateVariantCombination() {
        // Find matching combination
        const matchingCombination = combinations.find(combo => {
            return (!selectedVariants.size || combo.size_variant_id == selectedVariants.size) &&
                   (!selectedVariants.color || combo.color_variant_id == selectedVariants.color) &&
                   (!selectedVariants.scent || combo.scent_variant_id == selectedVariants.scent);
        });

        if (matchingCombination) {
            // Update hidden field
            document.getElementById('selected-combination-id').value = matchingCombination.id;
            
            // Update price display
            document.getElementById('variant-price').textContent = 
                'Rs. ' + parseFloat(matchingCombination.combination_price).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

            // Update stock display
            const inventory = matchingCombination.inventory;
            const availableStock = inventory ? (inventory.current_stock - inventory.reserved_stock) : 0;
            
            const stockDisplay = document.getElementById('variant-stock');
            if (availableStock > 0) {
                stockDisplay.textContent = `${availableStock} in stock`;
                stockDisplay.className = 'text-sm text-green-600';
            } else {
                stockDisplay.textContent = 'Out of stock';
                stockDisplay.className = 'text-sm text-red-600';
            }
        }
    }
});
</script>