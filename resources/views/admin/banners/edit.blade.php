@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Banner</h1>
            <p class="text-gray-600 mt-1">Update banner information</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <!-- Current Desktop Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Desktop Banner</label>
                    <img src="{{ $banner->desktop_image_url }}" alt="{{ $banner->title }}" class="max-w-full h-48 object-cover rounded-lg">
                </div>

                <!-- Current Mobile Image (if exists) -->
                @if($banner->image_mobile)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Mobile Banner</label>
                    <img src="{{ $banner->mobile_image_url }}" alt="{{ $banner->title }}" class="max-w-full h-48 object-cover rounded-lg">
                </div>
                @endif

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Banner Title <span class="text-gray-500">(Optional)</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $banner->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 @error('title') border-red-500 @enderror"
                           placeholder="e.g., Summer Collection 2025">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Desktop Image Upload (Optional for edit) -->
                <div>
                    <label for="image_desktop" class="block text-sm font-medium text-gray-700 mb-2">
                        Change Desktop Banner Image <span class="text-gray-500">(Optional)</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image_desktop" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                    <span>Upload new desktop image</span>
                                    <input id="image_desktop" name="image_desktop" type="file" class="sr-only" accept="image/*" onchange="previewImage(this, 'desktop')">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB (min. 1200x400px)</p>
                        </div>
                    </div>
                    <div id="desktop-preview" class="mt-4 hidden">
                        <p class="text-sm text-gray-700 mb-2">New Desktop Image Preview:</p>
                        <img src="" alt="Desktop Preview" class="max-w-full h-48 object-cover rounded-lg">
                    </div>
                    @error('image_desktop')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile Image Upload (Optional) -->
                <div>
                    <label for="image_mobile" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $banner->image_mobile ? 'Change' : 'Add' }} Mobile Banner Image <span class="text-gray-500">(Optional)</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image_mobile" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                    <span>Upload {{ $banner->image_mobile ? 'new' : '' }} mobile image</span>
                                    <input id="image_mobile" name="image_mobile" type="file" class="sr-only" accept="image/*" onchange="previewImage(this, 'mobile')">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">For better mobile experience (optional)</p>
                        </div>
                    </div>
                    <div id="mobile-preview" class="mt-4 hidden">
                        <p class="text-sm text-gray-700 mb-2">New Mobile Image Preview:</p>
                        <img src="" alt="Mobile Preview" class="max-w-full h-48 object-cover rounded-lg">
                    </div>
                    @error('image_mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link Type -->
                <div>
                    <label for="link_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Link Type <span class="text-red-500">*</span>
                    </label>
                    <select name="link_type" 
                            id="link_type" 
                            onchange="toggleLinkValue(this.value)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 @error('link_type') border-red-500 @enderror">
                        <option value="none" {{ old('link_type', $banner->link_type) == 'none' ? 'selected' : '' }}>No Link</option>
                        <option value="product" {{ old('link_type', $banner->link_type) == 'product' ? 'selected' : '' }}>Link to Product</option>
                        <option value="category" {{ old('link_type', $banner->link_type) == 'category' ? 'selected' : '' }}>Link to Category</option>
                        <option value="url" {{ old('link_type', $banner->link_type) == 'url' ? 'selected' : '' }}>Custom URL</option>
                    </select>
                    @error('link_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link Value (Dynamic based on type) -->
                <div id="link-value-container" class="{{ old('link_type', $banner->link_type) == 'none' ? 'hidden' : '' }}">
                    <!-- Product Dropdown -->
                    <div id="product-select" class="{{ old('link_type', $banner->link_type) == 'product' ? '' : 'hidden' }}">
                        <label for="product_link" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Product
                        </label>
                        <select name="link_value" 
                                id="product_link"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Choose a product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->slug }}" {{ old('link_value', $banner->link_value) == $product->slug ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Dropdown -->
                    <div id="category-select" class="{{ old('link_type', $banner->link_type) == 'category' ? '' : 'hidden' }}">
                        <label for="category_link" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Category
                        </label>
                        <select name="link_value" 
                                id="category_link"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Choose a category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ old('link_value', $banner->link_value) == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom URL Input -->
                    <div id="url-input" class="{{ old('link_type', $banner->link_type) == 'url' ? '' : 'hidden' }}">
                        <label for="url_link" class="block text-sm font-medium text-gray-700 mb-2">
                            Custom URL
                        </label>
                        <input type="url" 
                               name="link_value" 
                               id="url_link"
                               value="{{ old('link_value', $banner->link_value) }}"
                               placeholder="https://example.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    @error('link_value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input type="number" 
                           name="sort_order" 
                           id="sort_order" 
                           value="{{ old('sort_order', $banner->sort_order) }}"
                           min="0"
                           class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 @error('sort_order') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active (Show on homepage)</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.banners.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    Update Banner
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input, type) {
    const preview = document.getElementById(type + '-preview');
    const previewImg = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleLinkValue(linkType) {
    const container = document.getElementById('link-value-container');
    const productSelect = document.getElementById('product-select');
    const categorySelect = document.getElementById('category-select');
    const urlInput = document.getElementById('url-input');
    
    // Hide all inputs first
    productSelect.classList.add('hidden');
    categorySelect.classList.add('hidden');
    urlInput.classList.add('hidden');
    
    // Disable all inputs
    document.getElementById('product_link').disabled = true;
    document.getElementById('category_link').disabled = true;
    document.getElementById('url_link').disabled = true;
    
    if (linkType === 'none') {
        container.classList.add('hidden');
    } else {
        container.classList.remove('hidden');
        
        switch(linkType) {
            case 'product':
                productSelect.classList.remove('hidden');
                document.getElementById('product_link').disabled = false;
                break;
            case 'category':
                categorySelect.classList.remove('hidden');
                document.getElementById('category_link').disabled = false;
                break;
            case 'url':
                urlInput.classList.remove('hidden');
                document.getElementById('url_link').disabled = false;
                break;
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const linkType = document.getElementById('link_type').value;
    toggleLinkValue(linkType);
});
</script>
@endpush
@endsection