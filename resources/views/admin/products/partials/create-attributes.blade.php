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
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('how_to_use') border-red-500 @enderror">{{ old('how_to_use') }}</textarea>
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
                   value="{{ old('suitable_for') }}"
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
                   value="{{ old('fragrance') }}"
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
                       {{ old('has_variants') ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700">This product has variants (size, color, scent)</span>
            </label>
            <p class="mt-1 text-sm text-gray-500">Check this if the product comes in different sizes, colors, or scents</p>
        </div>
    </div>
</div>

