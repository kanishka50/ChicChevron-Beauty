<!-- Product Attributes -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-6">Product Attributes</h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- How to Use -->
        <div class="lg:col-span-2">
            <label for="how_to_use" class="block text-sm font-medium text-gray-700 mb-2">
                How to Use
            </label>
            <textarea name="how_to_use" 
                      id="how_to_use" 
                      rows="3"
                      placeholder="Provide clear instructions on how to use this product..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('how_to_use') border-red-500 @enderror">{{ old('how_to_use', $product->how_to_use) }}</textarea>
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
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('suitable_for') border-red-500 @enderror">
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
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('fragrance') border-red-500 @enderror">
            @error('fragrance')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Product Variants Option -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input type="checkbox" 
                       name="has_variants" 
                       id="has_variants"
                       value="1" 
                       {{ old('has_variants', $product->has_variants) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            </div>
            <div class="ml-3">
                <label for="has_variants" class="text-sm font-medium text-gray-700">
                    This product has variants
                </label>
                <p class="text-sm text-gray-500">
                    @if($product->has_variants && $product->variants->count() > 0)
                        This product currently has {{ $product->variants->count() }} variant(s). 
                        <a href="{{ route('admin.products.variants.index', $product) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            Manage variants →
                        </a>
                    @else
                        Check this if the product comes in different sizes, colors, or scents.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Product Status -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Product Status</h2>
        <div class="flex items-center">
            <input type="checkbox" 
                   name="is_active" 
                   id="is_active"
                   value="1" 
                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                Active (Visible to customers)
            </label>
        </div>
    </div>
    
    <!-- Product Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-sm font-medium text-gray-600">Total Stock</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $product->total_stock }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-sm font-medium text-gray-600">Views</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $product->views_count }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-sm font-medium text-gray-600">Reviews</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $product->review_count }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-sm font-medium text-gray-600">Rating</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($product->average_rating, 1) }} / 5.0</p>
        </div>
    </div>

    <!-- Status Help -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    <strong>Tip:</strong> Deactivating a product will hide it from customers but preserve all data. You can reactivate it anytime.
                </p>
            </div>
        </div>
    </div>
</div>