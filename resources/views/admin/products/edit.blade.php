@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Edit Product</h1>
            <div class="flex space-x-3">
                @if($product->has_variants)
                    <a href="{{ route('admin.products.variants', $product) }}" 
                       class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Manage Variants
                    </a>
                @endif
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $product->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Current slug: {{ $product->slug }}</p>
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                            SKU (Stock Keeping Unit)
                        </label>
                        <input type="text" 
                               name="sku" 
                               id="sku" 
                               value="{{ old('sku', $product->sku) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sku') border-red-500 @enderror">
                        @error('sku')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Brand <span class="text-red-500">*</span>
                        </label>
                        <select name="brand_id" 
                                id="brand_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('brand_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" 
                                id="category_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->path }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Type -->
                    <div>
                        <label for="product_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Type <span class="text-red-500">*</span>
                        </label>
                        <select name="product_type_id" 
                                id="product_type_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('product_type_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Product Type</option>
                            @foreach($productTypes as $type)
                                <option value="{{ $type->id }}" {{ old('product_type_id', $product->product_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_type_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Texture -->
                    <div>
                        <label for="texture_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Texture
                        </label>
                        <select name="texture_id" 
                                id="texture_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('texture_id') border-red-500 @enderror">
                            <option value="">Select Texture (Optional)</option>
                            @foreach($textures as $texture)
                                <option value="{{ $texture->id }}" {{ old('texture_id', $product->texture_id) == $texture->id ? 'selected' : '' }}>
                                    {{ $texture->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('texture_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Pricing Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Cost Price -->
                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Cost Price (LKR) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="cost_price" 
                               id="cost_price" 
                               value="{{ old('cost_price', $product->cost_price) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cost_price') border-red-500 @enderror"
                               required>
                        @error('cost_price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selling Price -->
                    <div>
                        <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Selling Price (LKR) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="selling_price" 
                               id="selling_price" 
                               value="{{ old('selling_price', $product->selling_price) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('selling_price') border-red-500 @enderror"
                               required>
                        @error('selling_price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Discount Price -->
                    <div>
                        <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Discount Price (LKR)
                        </label>
                        <input type="number" 
                               name="discount_price" 
                               id="discount_price" 
                               value="{{ old('discount_price', $product->discount_price) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('discount_price') border-red-500 @enderror">
                        @error('discount_price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Leave empty if no discount</p>
                    </div>
                </div>

                <!-- Profit Margin Display -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Profit Margin:</span>
                        <span id="profit-margin" class="ml-2 font-bold {{ $product->profit_margin > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $product->profit_margin }}%</span>
                    </p>
                </div>
            </div>

            <!-- Product Images -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Images</h2>
                
                <!-- Current Main Image -->
                <div class="mb-6">
                    <p class="block text-sm font-medium text-gray-700 mb-2">Current Main Image</p>
                    <img src="{{ Storage::url($product->main_image) }}" 
                         alt="{{ $product->name }}" 
                         class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                </div>

                <!-- Update Main Image -->
                <div class="mb-6">
                    <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Update Main Image
                    </label>
                    <input type="file" 
                           name="main_image" 
                           id="main_image"
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('main_image') border-red-500 @enderror"
                           onchange="previewMainImage(event)">
                    @error('main_image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image. Maximum file size: 2MB</p>
                    
                    <div id="main-image-preview" class="mt-4 hidden">
                        <p class="text-sm text-gray-700 mb-2">New Image Preview:</p>
                        <img src="#" alt="Main image preview" class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                    </div>
                </div>

                <!-- Current Additional Images -->
                @if($product->images->isNotEmpty())
                    <div class="mb-6">
                        <p class="block text-sm font-medium text-gray-700 mb-2">Current Additional Images</p>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($product->images as $image)
                                <div class="relative group">
                                    <img src="{{ Storage::url($image->image_path) }}" 
                                         alt="Product image" 
                                         class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                    <button type="button" 
                                            onclick="deleteImage({{ $image->id }})"
                                            class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-bl-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add Additional Images -->
                <div>
                    <label for="additional_images" class="block text-sm font-medium text-gray-700 mb-2">
                        Add More Images (Maximum {{ 4 - $product->images->count() }} remaining)
                    </label>
                    <input type="file" 
                           name="additional_images[]" 
                           id="additional_images"
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('additional_images.*') border-red-500 @enderror"
                           onchange="previewAdditionalImages(event)"
                           multiple
                           {{ $product->images->count() >= 4 ? 'disabled' : '' }}>
                    @error('additional_images.*')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    
                    <div id="additional-images-preview" class="mt-4 grid grid-cols-4 gap-4 hidden">
                        <!-- Preview images will be inserted here -->
                    </div>
                </div>
            </div>

            @include('admin.products.partials.edit-attributes')

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Update Product
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Calculate profit margin
    function calculateProfitMargin() {
        const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
        const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
        const discountPrice = parseFloat(document.getElementById('discount_price').value) || 0;
        
        const currentPrice = discountPrice > 0 ? discountPrice : sellingPrice;
        
        if (costPrice > 0 && currentPrice > 0) {
            const margin = ((currentPrice - costPrice) / currentPrice) * 100;
            document.getElementById('profit-margin').textContent = margin.toFixed(2) + '%';
            document.getElementById('profit-margin').className = margin > 0 ? 'ml-2 font-bold text-green-600' : 'ml-2 font-bold text-red-600';
        } else {
            document.getElementById('profit-margin').textContent = '0%';
        }
    }

    // Add event listeners
    document.getElementById('cost_price').addEventListener('input', calculateProfitMargin);
    document.getElementById('selling_price').addEventListener('input', calculateProfitMargin);
    document.getElementById('discount_price').addEventListener('input', calculateProfitMargin);

    // Preview main image
    function previewMainImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('main-image-preview');
            preview.querySelector('img').src = reader.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // Preview additional images
    function previewAdditionalImages(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('additional-images-preview');
        const existingImages = {{ $product->images->count() }};
        const maxImages = 4 - existingImages;
        
        previewContainer.innerHTML = '';
        
        if (files.length > maxImages) {
            alert(`You can only upload a maximum of ${maxImages} additional images`);
            event.target.value = '';
            return;
        }
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
        }
        
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Additional image ${i+1}" class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                `;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(files[i]);
        }
    }

    // Delete image
    function deleteImage(imageId) {
        if (confirm('Are you sure you want to delete this image?')) {
            fetch(`/admin/products/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting image');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting image');
            });
        }
    }
</script>
@endpush