@extends('admin.layouts.app')

@section('title', 'Create Category')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Create New Category</h1>
            <a href="{{ route('admin.categories.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Categories
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">The slug will be automatically generated from the name.</p>
                    </div>

                    <!-- Main Category -->
                    <div>
                        <label for="main_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Main Category <span class="text-red-500">*</span>
                        </label>
                        <select name="main_category_id" 
                                id="main_category_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('main_category_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Main Category</option>
                            @foreach($mainCategories as $mainCategory)
                                <option value="{{ $mainCategory->id }}" {{ old('main_category_id') == $mainCategory->id ? 'selected' : '' }}>
                                    {{ $mainCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('main_category_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Image <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex items-center">
                            <div class="flex-1">
                                <input type="file" 
                                       name="image" 
                                       id="image"
                                       accept="image/jpeg,image/png,image/jpg,image/webp"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror"
                                       onchange="previewImage(event)"
                                       required>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Maximum file size: 2MB. Supported formats: JPEG, PNG, JPG, WebP</p>
                            </div>
                            <div class="ml-4">
                                <img id="image-preview" 
                                     src="#" 
                                     alt="Preview" 
                                     class="h-20 w-20 object-cover rounded-lg border border-gray-300 hidden">
                            </div>
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number" 
                               name="sort_order" 
                               id="sort_order" 
                               value="{{ old('sort_order', 0) }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sort_order') border-red-500 @enderror">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first. Leave as 0 for auto-ordering.</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Inactive categories won't be shown to customers.</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Create Category
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