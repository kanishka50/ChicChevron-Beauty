@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Banner</h1>
            <a href="{{ route('admin.banners.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Banners
            </a>
        </div>
        <p class="text-gray-600 mt-1">Update banner information</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <!-- Current Images Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Current Images</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Desktop Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Desktop Banner</label>
                        <img src="{{ $banner->desktop_image_url }}" 
                             alt="{{ $banner->title }}" 
                             class="w-full h-48 object-cover rounded-lg border border-gray-300">
                    </div>

                    <!-- Current Mobile Image (if exists) -->
                    @if($banner->image_mobile)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Mobile Banner</label>
                        <img src="{{ $banner->mobile_image_url }}" 
                             alt="{{ $banner->title }}" 
                             class="w-full h-48 object-cover rounded-lg border border-gray-300">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Basic Information Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Banner Title <span class="text-gray-500">(Optional)</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $banner->title) }}"
                           placeholder="e.g., Summer Collection 2025"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Update Images Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Images</h3>
                
                <!-- Desktop Image Upload (Optional for edit) -->
                <div class="mb-6">
                    <label for="image_desktop" class="block text-sm font-medium text-gray-700 mb-2">
                        Change Desktop Banner Image <span class="text-gray-500">(Optional)</span>
                    </label>
                    <div class="flex items-start space-x-6">
                        <div class="flex-1">
                            <input type="file" 
                                   name="image_desktop" 
                                   id="image_desktop"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image_desktop') border-red-500 @enderror"
                                   onchange="previewImage(event, 'desktop')">
                            @error('image_desktop')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image. Maximum file size: 2MB</p>
                        </div>
                        
                        <div class="flex-shrink-0">
                            <p class="text-sm font-medium text-gray-700 mb-2">New Preview</p>
                            <div id="new-desktop-preview" class="hidden">
                                <img id="desktop-preview" 
                                     src="#" 
                                     alt="New preview" 
                                     class="h-32 w-48 object-cover rounded-lg border-2 border-blue-300">
                            </div>
                            <div id="desktop-placeholder" class="h-32 w-48 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                <span class="text-xs text-gray-500">No new image</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Image Upload (Optional) -->
                <div>
                    <label for="image_mobile" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $banner->image_mobile ? 'Change' : 'Add' }} Mobile Banner Image <span class="text-gray-500">(Optional)</span>
                    </label>
                    <div class="flex items-start space-x-6">
                        <div class="flex-1">
                            <input type="file" 
                                   name="image_mobile" 
                                   id="image_mobile"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image_mobile') border-red-500 @enderror"
                                   onchange="previewImage(event, 'mobile')">
                            @error('image_mobile')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">For better mobile experience. Recommended: 600x800px</p>
                        </div>
                        
                        <div class="flex-shrink-0">
                            <p class="text-sm font-medium text-gray-700 mb-2">New Preview</p>
                            <div id="new-mobile-preview" class="hidden">
                                <img id="mobile-preview" 
                                     src="#" 
                                     alt="New preview" 
                                     class="h-32 w-24 object-cover rounded-lg border-2 border-blue-300">
                            </div>
                            <div id="mobile-placeholder" class="h-32 w-24 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                <span class="text-xs text-gray-500">No new image</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Link Settings Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Link Settings</h3>
                
                <!-- Link Type -->
                <div class="mb-4">
                    <label for="link_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Link Type <span class="text-red-500">*</span>
                    </label>
                    <select name="link_type" 
                            id="link_type" 
                            onchange="toggleLinkValue(this.value)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('link_type') border-red-500 @enderror">
                        <option value="none" {{ old('link_type', $banner->link_type) == 'none' ? 'selected' : '' }}>No Link</option>
                        <option value="product" {{ old('link_type', $banner->link_type) == 'product' ? 'selected' : '' }}>Link to Product</option>
                        <option value="category" {{ old('link_type', $banner->link_type) == 'category' ? 'selected' : '' }}>Link to Category</option>
                        <option value="url" {{ old('link_type', $banner->link_type) == 'url' ? 'selected' : '' }}>Custom URL</option>
                    </select>
                    @error('link_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
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
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    @error('link_value')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Display Settings Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Display Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="mt-2">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active (Show on homepage)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.banners.index') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Update Banner
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(event, type) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById(type + '-preview');
            const newPreviewSection = document.getElementById('new-' + type + '-preview');
            const placeholder = document.getElementById(type + '-placeholder');
            
            preview.src = reader.result;
            newPreviewSection.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(file);
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