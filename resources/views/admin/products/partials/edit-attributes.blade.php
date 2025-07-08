<!-- Product Attributes -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Attributes</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- How to Use -->
        <div>
            <label for="how_to_use" class="block text-sm font-medium text-gray-700 mb-2">
                How to Use
            </label>
            <textarea name="how_to_use" 
                      id="how_to_use" 
                      rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('how_to_use') border-red-500 @enderror">{{ old('how_to_use', $product->how_to_use) }}</textarea>
            @error('how_to_use')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Suitable For -->
        <div>
            <label for="suitable_for" class="block text-sm font-medium text-gray-700 mb-2">
                Suitable For
            </label>
            <input type="text" 
                   name="suitable_for" 
                   id="suitable_for" 
                   value="{{ old('suitable_for', $product->suitable_for) }}"
                   placeholder="e.g., All skin types, Dry skin, Oily skin"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('suitable_for') border-red-500 @enderror">
            @error('suitable_for')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Fragrance -->
        <div>
            <label for="fragrance" class="block text-sm font-medium text-gray-700 mb-2">
                Fragrance
            </label>
            <input type="text" 
                   name="fragrance" 
                   id="fragrance" 
                   value="{{ old('fragrance', $product->fragrance) }}"
                   placeholder="e.g., Rose, Lavender, Unscented"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('fragrance') border-red-500 @enderror">
            @error('fragrance')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Has Variants -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Product Options</label>
            <label class="flex items-center">
                <input type="checkbox" 
                       name="has_variants" 
                       id="has_variants"
                       value="1" 
                       {{ old('has_variants', $product->has_variants) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700">This product has variants (size, color, scent)</span>
            </label>
            <p class="mt-1 text-sm text-gray-500">
                @if($product->has_variants)
                    This product has variants. <a href="{{ route('admin.products.variants', $product) }}" class="text-blue-500 hover:underline">Manage variants</a>
                @else
                    Check this if the product comes in different sizes, colors, or scents
                @endif
            </p>
        </div>
    </div>
</div>

<!-- Ingredients -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Ingredients</h2>
    
    <div id="ingredients-container">
        @forelse($product->ingredients as $index => $ingredient)
            <div class="ingredient-row mb-3">
                <div class="flex gap-2">
                    <input type="text" 
                           name="ingredients[]" 
                           value="{{ old('ingredients.' . $index, $ingredient->ingredient_name) }}"
                           placeholder="Enter ingredient name"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="removeIngredient(this)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded {{ $loop->first ? 'hidden' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="ingredient-row mb-3">
                <div class="flex gap-2">
                    <input type="text" 
                           name="ingredients[]" 
                           placeholder="Enter ingredient name"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="removeIngredient(this)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endforelse
    </div>
    
    <button type="button" onclick="addIngredient()" class="mt-3 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm">
        + Add Ingredient
    </button>
</div>

<!-- Colors -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Available Colors</h2>
    
    @php
        $selectedColors = old('colors', $product->colors->pluck('id')->toArray());
    @endphp
    
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
        @foreach($colors as $color)
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" 
                       name="colors[]" 
                       value="{{ $color->id }}"
                       {{ in_array($color->id, $selectedColors) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="flex items-center space-x-2">
                    <span class="w-4 h-4 rounded-full border border-gray-300" 
                          style="background-color: {{ $color->hex_code }}"></span>
                    <span class="text-sm text-gray-700">{{ $color->name }}</span>
                </span>
            </label>
        @endforeach
    </div>
    
    @if($colors->isEmpty())
        <p class="text-gray-500 text-sm">No colors available. Please add colors first.</p>
    @endif
</div>

<!-- Product Status -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Status</h2>
    
    <label class="flex items-center">
        <input type="checkbox" 
               name="is_active" 
               value="1" 
               {{ old('is_active', $product->is_active) ? 'checked' : '' }}
               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        <span class="ml-2 text-sm text-gray-700">Active (Visible to customers)</span>
    </label>
    
    <!-- Product Stats -->
    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div class="bg-gray-50 p-3 rounded">
            <p class="text-gray-500">Total Stock</p>
            <p class="font-semibold text-gray-900">{{ $product->total_stock }}</p>
        </div>
        <div class="bg-gray-50 p-3 rounded">
            <p class="text-gray-500">Views</p>
            <p class="font-semibold text-gray-900">{{ $product->views_count }}</p>
        </div>
        <div class="bg-gray-50 p-3 rounded">
            <p class="text-gray-500">Reviews</p>
            <p class="font-semibold text-gray-900">{{ $product->review_count }}</p>
        </div>
        <div class="bg-gray-50 p-3 rounded">
            <p class="text-gray-500">Rating</p>
            <p class="font-semibold text-gray-900">{{ number_format($product->average_rating, 1) }} / 5.0</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Ingredients management
    function addIngredient() {
        const container = document.getElementById('ingredients-container');
        const newRow = document.createElement('div');
        newRow.className = 'ingredient-row mb-3';
        newRow.innerHTML = `
            <div class="flex gap-2">
                <input type="text" 
                       name="ingredients[]" 
                       placeholder="Enter ingredient name"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" onclick="removeIngredient(this)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        container.appendChild(newRow);
        updateIngredientButtons();
    }

    function removeIngredient(button) {
        button.closest('.ingredient-row').remove();
        updateIngredientButtons();
    }

    function updateIngredientButtons() {
        const rows = document.querySelectorAll('.ingredient-row');
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('button');
            if (rows.length === 1) {
                removeBtn.classList.add('hidden');
            } else {
                removeBtn.classList.remove('hidden');
            }
        });
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        updateIngredientButtons();
    });
</script>
@endpush