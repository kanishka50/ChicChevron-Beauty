@extends('admin.layouts.app')

@section('title', 'Edit Brand')

@section('content')
    <div class="container-fluid px-4 max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Brand</h1>
            <a href="{{ route('admin.brands.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Brands
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Basic Information Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                    
                    <!-- Brand Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Brand Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $brand->name) }}"
                               placeholder="Enter brand name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Current slug: {{ $brand->slug }}</p>
                    </div>
                </div>

                <!-- Logo Upload Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Brand Logo</h3>
                    
                    <div class="flex items-start space-x-6">
                        <div class="flex-1">
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                                Update Logo <span class="text-gray-500">(Optional)</span>
                            </label>
                            <input type="file" 
                                   name="logo" 
                                   id="logo"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('logo') border-red-500 @enderror"
                                   onchange="previewImage(event)">
                            @error('logo')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Leave empty to keep current logo. Maximum file size: 2MB</p>
                            <p class="mt-1 text-sm text-gray-500">Recommended size: 200x200 pixels (square)</p>
                        </div>
                        
                        <div class="flex-shrink-0">
                            <p class="text-sm font-medium text-gray-700 mb-2">Current Logo</p>
                            <div class="relative">
                                @if($brand->logo)
                                    <img id="current-logo" 
                                         src="{{ Storage::url($brand->logo) }}" 
                                         alt="Current logo" 
                                         class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                @else
                                    <div class="h-32 w-32 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div id="new-preview" class="mt-2 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">New Logo Preview</p>
                                <img id="image-preview" 
                                     src="#" 
                                     alt="New preview" 
                                     class="h-32 w-32 object-cover rounded-lg border-2 border-blue-300">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display Settings Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Display Settings</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="mt-2">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $brand->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">Inactive brands won't be shown to customers.</p>
                        </div>
                    </div>
                </div>

                <!-- Brand Information -->
                <div class="mb-8 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Brand Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Products</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $brand->product_count }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Products</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $brand->activeProducts()->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Created</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ $brand->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Last Updated</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ $brand->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Brand Warning -->
                @if($brand->product_count > 0)
                <div class="mb-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800">
                                <strong>Note:</strong> This brand has {{ $brand->product_count }} product(s) associated with it. 
                                Deactivating this brand will affect the visibility of these products.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.brands.index') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        Update Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('image-preview');
                const newPreviewSection = document.getElementById('new-preview');
                preview.src = reader.result;
                newPreviewSection.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush