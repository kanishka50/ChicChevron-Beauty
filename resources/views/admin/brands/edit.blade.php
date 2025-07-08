@extends('admin.layouts.app')

@section('title', 'Edit Brand')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Edit Brand</h1>
            <a href="{{ route('admin.brands.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Brands
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Brand Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Brand Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $brand->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Enter brand name"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Current slug: {{ $brand->slug }}</p>
                </div>

                <!-- Logo Upload -->
                <div class="mb-6">
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Brand Logo
                    </label>
                    <div class="mt-1 flex items-center">
                        <div class="flex-1">
                            <input type="file" 
                                   name="logo" 
                                   id="logo"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('logo') border-red-500 @enderror"
                                   onchange="previewImage(event)">
                            @error('logo')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Leave empty to keep current logo. Maximum file size: 2MB</p>
                            <p class="mt-1 text-sm text-gray-500">Recommended size: 200x200 pixels (square)</p>
                        </div>
                        <div class="ml-4">
                            <img id="image-preview" 
                                 src="{{ $brand->logo ? Storage::url($brand->logo) : '#' }}" 
                                 alt="Preview" 
                                 class="h-20 w-20 object-cover rounded-lg border border-gray-300 {{ $brand->logo ? '' : 'hidden' }}">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $brand->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-500">Inactive brands won't be shown to customers.</p>
                </div>

                <!-- Brand Info -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Brand Information</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Total Products:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $brand->product_count }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Active Products:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $brand->activeProducts()->count() }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $brand->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Updated:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $brand->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.brands.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
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
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('image-preview');
            preview.src = reader.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush