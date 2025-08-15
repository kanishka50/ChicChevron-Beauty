<!-- Product Images Section -->
<div class="space-y-6">
    <!-- Main Image -->
    <div>
        <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">
            Main Image <span class="text-red-500">*</span>
        </label>
        
        @if(isset($product) && $product->main_image)
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-3">Current main image:</p>
                <div class="relative inline-block">
                    <img src="{{ Storage::url($product->main_image) }}" 
                         alt="Current main image" 
                         class="h-40 w-40 object-cover rounded-lg border-2 border-gray-300 shadow-sm">
                    <div class="absolute top-2 right-2">
                        <span class="bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded-md shadow-sm">Main</span>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="mt-2">
            <input type="file" 
                   name="main_image" 
                   id="main_image"
                   accept="image/jpeg,image/png,image/jpg,image/webp"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('main_image') border-red-500 @enderror"
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
        </div>
        
        <div id="main-image-preview" class="mt-4 hidden">
            <p class="text-sm text-gray-600 mb-2">New main image preview:</p>
            <img src="#" alt="Main image preview" class="h-40 w-40 object-cover rounded-lg border-2 border-gray-300 shadow-sm">
        </div>
    </div>

    <!-- Current Additional Images (for edit mode) -->
    @if(isset($product) && $product->images->isNotEmpty())
        <div>
            <h3 class="text-sm font-medium text-gray-700 mb-3">Current Additional Images</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($product->images as $image)
                    <div class="relative group" id="current-image-{{ $image->id }}">
                        <img src="{{ Storage::url($image->image_path) }}" 
                             alt="Product image" 
                             class="h-32 w-full object-cover rounded-lg border border-gray-300 shadow-sm group-hover:shadow-md transition-shadow">
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button" 
                                    onclick="deleteImage({{ $image->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="absolute bottom-2 left-2">
                            <span class="bg-gray-800 bg-opacity-75 text-white text-xs font-medium px-2 py-1 rounded">
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
                <span class="text-gray-500">(Add more - Maximum {{ 4 - $product->images->count() }} more)</span>
            @else
                <span class="text-gray-500">(Maximum 4)</span>
            @endif
        </label>
        
        <input type="file" 
               name="additional_images[]" 
               id="additional_images"
               accept="image/jpeg,image/png,image/jpg,image/webp"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('additional_images.*') border-red-500 @enderror"
               onchange="previewAdditionalImages(event)"
               multiple
               @if(isset($product) && $product->images->count() >= 4) disabled @endif>
        @error('additional_images.*')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-sm text-gray-500">
            You can select multiple images at once. Maximum file size: 2MB per image.
            @if(isset($product) && $product->images->count() >= 4)
                <br><span class="text-orange-600 font-medium">Maximum images reached. Delete existing images to add new ones.</span>
            @endif
        </p>
        
        <div id="additional-images-preview" class="mt-4 hidden">
            <!-- Preview images will be inserted here -->
        </div>
    </div>

    <!-- Image Guidelines -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-900">Image Guidelines:</h4>
                <ul class="mt-2 text-sm text-blue-800 space-y-1">
                    <li>• Use high-quality images with good lighting</li>
                    <li>• Recommended minimum resolution: 800x600 pixels</li>
                    <li>• Square aspect ratio works best (1:1)</li>
                    <li>• Show product from different angles</li>
                    <li>• Use plain backgrounds when possible</li>
                </ul>
            </div>
        </div>
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
        
        // Clear previous previews
        previewContainer.innerHTML = '';
        
        if (files.length > maxImages) {
            alert(`You can only upload a maximum of ${maxImages} additional images`);
            event.target.value = '';
            previewContainer.classList.add('hidden');
            return;
        }
        
        // Check file sizes
        let hasInvalidFile = false;
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > 2 * 1024 * 1024) {
                alert(`File "${files[i].name}" is too large. Maximum size is 2MB.`);
                hasInvalidFile = true;
                break;
            }
        }
        
        if (hasInvalidFile) {
            event.target.value = '';
            previewContainer.innerHTML = '';
            previewContainer.classList.add('hidden');
            return;
        }
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            
            // Add heading for preview section
            const heading = document.createElement('p');
            heading.className = 'text-sm font-medium text-gray-700 mb-3 col-span-full';
            heading.textContent = `Preview of ${files.length} new image${files.length > 1 ? 's' : ''} to be uploaded:`;
            previewContainer.appendChild(heading);
            
            // Create a container for the images grid
            const gridContainer = document.createElement('div');
            gridContainer.className = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4';
            previewContainer.appendChild(gridContainer);
            
            // Process each file
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.id = `preview-image-${index}`;
                    div.innerHTML = `
                        <img src="${e.target.result}" 
                             alt="Additional image ${index + 1}" 
                             class="h-32 w-full object-cover rounded-lg border border-gray-300 shadow-sm">
                        <div class="absolute bottom-2 left-2">
                            <span class="bg-gray-800 bg-opacity-75 text-white text-xs font-medium px-2 py-1 rounded">
                                New #${index + 1}
                            </span>
                        </div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button" 
                                    onclick="removePreviewImage(${index})"
                                    class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-lg transition-colors"
                                    title="Remove from selection">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    gridContainer.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    // Remove image from preview (and file input)
    function removePreviewImage(index) {
        const input = document.getElementById('additional_images');
        const files = Array.from(input.files);
        
        // Create a new FileList without the removed file
        const dt = new DataTransfer();
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        // Update the input files
        input.files = dt.files;
        
        // Refresh the preview
        previewAdditionalImages({ target: input });
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
                        const label = document.querySelector('label[for="additional_images"] span');
                        if (label) {
                            label.textContent = `(Add more - Maximum ${4 - currentImageCount} more)`;
                        }
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
            // Simple notification
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
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