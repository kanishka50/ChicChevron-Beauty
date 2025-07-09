<!-- Product Images -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Images</h2>
    
    <!-- Main Image -->
    <div class="mb-6">
        <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">
            Main Image <span class="text-red-500">*</span>
        </label>
        
        @if(isset($product) && $product->main_image)
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Current main image:</p>
                <div class="relative inline-block">
                    <img src="{{ Storage::url($product->main_image) }}" 
                         alt="Current main image" 
                         class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                    <div class="absolute top-1 right-1">
                        <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">Main</span>
                    </div>
                </div>
            </div>
        @endif
        
        <input type="file" 
               name="main_image" 
               id="main_image"
               accept="image/jpeg,image/png,image/jpg,image/webp"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('main_image') border-red-500 @enderror"
               onchange="previewMainImage(event)"
               {{ !isset($product) ? 'required' : '' }}>
        @error('main_image')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-sm text-gray-500">
            Maximum file size: 2MB. Supported formats: JPEG, PNG, JPG, WebP
            @if(isset($product))
                <br>Leave empty to keep current image.
            @endif
        </p>
        
        <div id="main-image-preview" class="mt-4 hidden">
            <p class="text-sm text-gray-600 mb-2">New main image preview:</p>
            <img src="#" alt="Main image preview" class="h-32 w-32 object-cover rounded-lg border border-gray-300">
        </div>
    </div>

    <!-- Current Additional Images (for edit mode) -->
    @if(isset($product) && $product->images->isNotEmpty())
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-700 mb-3">Current Additional Images</h3>
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->images as $image)
                    <div class="relative group" id="current-image-{{ $image->id }}">
                        <img src="{{ Storage::url($image->image_path) }}" 
                             alt="Product image" 
                             class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                        <div class="absolute top-1 right-1">
                            <button type="button" 
                                    onclick="deleteImage({{ $image->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="absolute bottom-1 left-1">
                            <span class="bg-gray-800 bg-opacity-75 text-white text-xs px-1 py-0.5 rounded">
                                #{{ $image->sort_order }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Additional Images Upload -->
    <div>
        <label for="additional_images" class="block text-sm font-medium text-gray-700 mb-2">
            Additional Images 
            @if(isset($product))
                (Add more - Maximum {{ 4 - $product->images->count() }} more)
            @else
                (Maximum 4)
            @endif
        </label>
        
        <input type="file" 
               name="additional_images[]" 
               id="additional_images"
               accept="image/jpeg,image/png,image/jpg,image/webp"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('additional_images.*') border-red-500 @enderror"
               onchange="previewAdditionalImages(event)"
               multiple
               @if(isset($product) && $product->images->count() >= 4) disabled @endif>
        @error('additional_images.*')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-sm text-gray-500">
            You can select multiple images at once. Maximum file size: 2MB per image.
            @if(isset($product) && $product->images->count() >= 4)
                <br><span class="text-orange-600">Maximum images reached. Delete existing images to add new ones.</span>
            @endif
        </p>
        
        <div id="additional-images-preview" class="mt-4 grid grid-cols-4 gap-4 hidden">
            <!-- Preview images will be inserted here -->
        </div>
    </div>

    <!-- Image Guidelines -->
    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
        <h4 class="text-sm font-medium text-blue-900 mb-2">Image Guidelines:</h4>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Use high-quality images with good lighting</li>
            <li>• Recommended minimum resolution: 800x600 pixels</li>
            <li>• Square aspect ratio works best (1:1)</li>
            <li>• Show product from different angles</li>
            <li>• Use plain backgrounds when possible</li>
            <li>• First image will be the main display image</li>
        </ul>
    </div>
</div>

@push('scripts')
<script>
    // Preview main image
    function previewMainImage(event) {
        const file = event.target.files[0];
        if (file) {
            // Check file size (2MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                event.target.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('main-image-preview');
                const img = preview.querySelector('img');
                img.src = reader.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // Preview additional images
    function previewAdditionalImages(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('additional-images-preview');
        const maxImages = {{ isset($product) ? 4 - $product->images->count() : 4 }};
        
        previewContainer.innerHTML = '';
        
        if (files.length > maxImages) {
            alert(`You can only upload a maximum of ${maxImages} additional images`);
            event.target.value = '';
            return;
        }
        
        // Check file sizes
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > 2 * 1024 * 1024) {
                alert(`File "${files[i].name}" is too large. Maximum size is 2MB.`);
                event.target.value = '';
                return;
            }
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
                    <img src="${e.target.result}" 
                         alt="Additional image ${i+1}" 
                         class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                    <div class="absolute bottom-1 left-1">
                        <span class="bg-gray-800 bg-opacity-75 text-white text-xs px-1 py-0.5 rounded">
                            New #${i+1}
                        </span>
                    </div>
                `;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(files[i]);
        }
    }

    // Delete existing image
    function deleteImage(imageId) {
        if (confirm('Are you sure you want to delete this image?')) {
            fetch(`{{ route('admin.products.images.destroy', ':id') }}`.replace(':id', imageId), {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove image from DOM
                    document.getElementById(`current-image-${imageId}`).remove();
                    
                    // Re-enable additional images input if needed
                    const additionalImagesInput = document.getElementById('additional_images');
                    const currentImageCount = document.querySelectorAll('[id^="current-image-"]').length;
                    if (currentImageCount < 4) {
                        additionalImagesInput.disabled = false;
                        // Update the label
                        const label = document.querySelector('label[for="additional_images"]');
                        label.innerHTML = `Additional Images (Add more - Maximum ${4 - currentImageCount} more)`;
                    }
                    
                    // Show success message
                    showNotification('Image deleted successfully!', 'success');
                } else {
                    alert(data.message || 'Error deleting image');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting image');
            });
        }
    }

    // Notification function (if not already defined)
    if (typeof showNotification === 'undefined') {
        function showNotification(message, type = 'info') {
            // Simple notification - you can enhance this
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }
    }
</script>
@endpush