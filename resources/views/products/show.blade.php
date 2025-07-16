@extends('layouts.app')

@section('title', $product->name . ' - ChicChevron Beauty')
@section('description', Str::limit(strip_tags($product->description), 160))

@section('breadcrumbs')
    <nav aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-pink-600">Home</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-pink-600">Products</a></li>
            @if($product->category)
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="text-gray-500 hover:text-pink-600">{{ $product->category->name }}</a></li>
            @endif
            <li><span class="text-gray-400">/</span></li>
            <li class="text-gray-900">{{ $product->name }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    <img id="main-image" 
                         src="{{ $product->main_image ? asset('storage/' . $product->main_image) : '/placeholder.jpg' }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover cursor-zoom-in"
                         onclick="openImageModal(this.src)">
                </div>

                <!-- Thumbnail Images -->
                @if($product->images->isNotEmpty() || $product->main_image)
                    <div class="grid grid-cols-4 gap-2">
                        <!-- Main image thumbnail -->
                        @if($product->main_image)
                            <button class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-pink-600" 
                                    onclick="changeMainImage('{{ asset('storage/' . $product->main_image) }}', this)">
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover">
                            </button>
                        @endif

                        <!-- Additional images -->
                        @foreach($product->images as $image)
                            <button class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-transparent hover:border-gray-300" 
                                    onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <!-- Basic Info -->
                <div>
                    @if($product->brand)
                        <p class="text-pink-600 font-medium">{{ $product->brand->name }}</p>
                    @endif
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $product->name }}</h1>
                    
                    <!-- Rating -->
                    @if($product->reviews->isNotEmpty())
                        <div class="flex items-center gap-2 mt-3">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($product->reviews->avg('rating')) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-gray-600">{{ number_format($product->reviews->avg('rating'), 1) }} ({{ $product->reviews->count() }} reviews)</span>
                        </div>
                    @endif
                </div>

                <!-- Price -->
                <div class="border-b border-gray-200 pb-6">
                    <div id="price-display">
    @if($product->variants->count() > 1)
        <span class="text-3xl font-bold text-gray-900">
            From Rs. {{ number_format($product->variants->min('price'), 2) }}
        </span>
    @else
        <span class="text-3xl font-bold text-gray-900">
            Rs. {{ number_format($product->variants->first()->price ?? 0, 2) }}
        </span>
    @endif
</div>

                    <!-- Stock Status -->
                    <div id="stock-status" class="mt-3">
                        @if($product->getStockLevel() > 0)
                            <p class="text-green-600 font-medium">✓ In Stock ({{ $product->getStockLevel() }} available)</p>
                        @else
                            <p class="text-red-600 font-medium">✗ Out of Stock</p>
                        @endif
                    </div>
                </div>

                @if($product->has_variants && $product->variants->isNotEmpty())
    <div class="space-y-4" id="variant-selection">
        <label class="block text-sm font-medium text-gray-900 mb-2">Select Option</label>
        <div class="grid grid-cols-1 gap-2">
            @foreach($product->variants as $variant)
                <button type="button" 
                        class="variant-option flex justify-between items-center px-4 py-3 border border-gray-300 rounded-md text-sm font-medium hover:border-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500"
                        data-variant-id="{{ $variant->id }}"
                        data-price="{{ $variant->price }}"
                        data-stock="{{ $variant->available_stock }}"
                        data-sku="{{ $variant->sku }}"
                        onclick="selectProductVariant(this)">
                    <span>{{ $variant->display_name }}</span>
                    <span class="text-gray-600">Rs. {{ number_format($variant->price, 2) }}</span>
                </button>
            @endforeach
        </div>
    </div>
@endif

                <!-- Quantity and Add to Cart -->
                <div class="space-y-4 border-b border-gray-200 pb-6">
                    <div class="flex items-center gap-4">
                        <label class="block text-sm font-medium text-gray-900">Quantity</label>
                        <div class="flex items-center border border-gray-300 rounded-md">
                            <button type="button" onclick="changeQuantity(-1)" class="px-3 py-2 text-gray-600 hover:text-gray-800">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="10" class="w-16 text-center border-0 focus:ring-0">
                            <button type="button" onclick="changeQuantity(1)" class="px-3 py-2 text-gray-600 hover:text-gray-800">+</button>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button id="add-to-cart-btn" 
                                onclick="addToCart()"
                                class="flex-1 bg-pink-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-pink-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Add to Cart
                        </button>
                        
                        <button onclick="addToWishlist({{ $product->id }})" 
                                class="p-3 border border-gray-300 rounded-lg hover:border-pink-600 hover:text-pink-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Product Features -->
                <div class="space-y-4">
                    @if($product->texture)
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-900">Texture:</span>
                            <span class="text-sm text-gray-600">{{ $product->texture->name }}</span>
                        </div>
                    @endif

                    @if($product->suitable_for)
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-900">Suitable for:</span>
                            <span class="text-sm text-gray-600">{{ $product->suitable_for }}</span>
                        </div>
                    @endif

                    @if($product->fragrance)
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-900">Fragrance:</span>
                            <span class="text-sm text-gray-600">{{ $product->fragrance }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-900">SKU:</span>
                        <span class="text-sm text-gray-600" id="product-sku">{{ $product->sku }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="mt-16">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button class="tab-button active py-2 px-1 border-b-2 border-pink-600 font-medium text-sm text-pink-600" onclick="showTab('description')">
                        Description
                    </button>
                    @if($product->ingredients->isNotEmpty())
                        <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('ingredients')">
                            Ingredients
                        </button>
                    @endif
                    @if($product->how_to_use)
                        <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('how-to-use')">
                            How to Use
                        </button>
                    @endif
                    @if($product->reviews->isNotEmpty())
                        <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('reviews')">
                            Reviews ({{ $product->reviews->count() }})
                        </button>
                    @endif
                </nav>
            </div>

            <div class="mt-8">
                <!-- Description Tab -->
                <div id="description-tab" class="tab-content">
                    <div class="prose max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Ingredients Tab -->
                @if($product->ingredients->isNotEmpty())
                    <div id="ingredients-tab" class="tab-content hidden">
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Ingredients</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($product->ingredients as $ingredient)
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-pink-600 rounded-full"></div>
                                        <span class="text-gray-700">{{ $ingredient->ingredient_name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- How to Use Tab -->
                @if($product->how_to_use)
                    <div id="how-to-use-tab" class="tab-content hidden">
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">How to Use</h3>
                            <div class="prose max-w-none">
                                {!! nl2br(e($product->how_to_use)) !!}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Reviews Tab -->
                @if($product->reviews->isNotEmpty())
                    <div id="reviews-tab" class="tab-content hidden">
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">Customer Reviews</h3>
                                @auth
                                @if($product->canBeReviewedBy(auth()->user()))
                                    <span class="text-sm text-gray-600">
                                        You can review this product from your order history
                                    </span>
                                @endif
                            @endauth
                            </div>

                            {{-- Add this note for products with variants --}}
                            @if($product->has_variants)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-sm text-blue-800">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <strong>Note:</strong> Reviews apply to all variants (sizes, colors, scents) of this product.
                                    </p>
                                </div>
                            @endif

                            <!-- Review Summary -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <div class="flex items-center gap-4">
                                    <div class="text-center">
                                        <div class="text-4xl font-bold text-gray-900">{{ number_format($product->reviews->avg('rating'), 1) }}</div>
                                        <div class="flex justify-center mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= round($product->reviews->avg('rating')) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1">{{ $product->reviews->count() }} reviews</div>
                                    </div>

                                    <div class="flex-1">
                                        @for($rating = 5; $rating >= 1; $rating--)
                                            @php
                                                $count = $product->reviews->where('rating', $rating)->count();
                                                $percentage = $product->reviews->count() > 0 ? ($count / $product->reviews->count()) * 100 : 0;
                                            @endphp
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-sm text-gray-600 w-3">{{ $rating }}</span>
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="text-sm text-gray-600 w-8">{{ $count }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <!-- Individual Reviews -->
                            <div class="space-y-6">
                                @foreach($product->reviews->take(5) as $review)
                                    <div class="border-b border-gray-200 pb-6">
                                        <div class="flex items-start justify-between mb-4">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-medium text-gray-900">{{ $review->user->name }}</span>
                                                    <div class="flex">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <p class="text-sm text-gray-600">{{ $review->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        @if($review->title)
                                            <h4 class="font-medium text-gray-900 mb-2">{{ $review->title }}</h4>
                                        @endif
                                        <p class="text-gray-700">{{ $review->review }}</p>
                                    </div>
                                @endforeach

                                @if($product->reviews->count() > 5)
                                    <div class="text-center">
                                        <button class="text-pink-600 hover:text-pink-700 font-medium">
                                            View All Reviews
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">You May Also Like</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        @include('components.shop.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Image Modal -->
    <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-xl bg-black bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center hover:bg-opacity-75">
                ×
            </button>
            <img id="modal-image" src="" alt="" class="max-w-full max-h-full object-contain">
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    let selectedVariant = null;

    function selectProductVariant(button) {
        // Remove active state from all buttons
        document.querySelectorAll('.variant-option').forEach(btn => {
            btn.classList.remove('border-pink-600', 'bg-pink-50');
            btn.classList.add('border-gray-300');
        });

        // Add active state
        button.classList.add('border-pink-600', 'bg-pink-50');
        button.classList.remove('border-gray-300');

        // Update selected variant
        selectedVariant = {
            id: button.dataset.variantId,
            price: button.dataset.price,
            stock: parseInt(button.dataset.stock),
            sku: button.dataset.sku
        };

        // Update price display
        document.getElementById('price-display').innerHTML = 
            `<span class="text-3xl font-bold text-gray-900">Rs. ${parseFloat(selectedVariant.price).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>`;

        // Update stock status
        const stockStatusEl = document.getElementById('stock-status');
        if (selectedVariant.stock > 0) {
            stockStatusEl.innerHTML = `<p class="text-green-600 font-medium">✓ In Stock (${selectedVariant.stock} available)</p>`;
            document.getElementById('add-to-cart-btn').disabled = false;
        } else {
            stockStatusEl.innerHTML = `<p class="text-red-600 font-medium">✗ Out of Stock</p>`;
            document.getElementById('add-to-cart-btn').disabled = true;
        }

        // Update SKU
        document.getElementById('product-sku').textContent = selectedVariant.sku;

        // Update max quantity
        const quantityInput = document.getElementById('quantity');
        quantityInput.max = selectedVariant.stock || 1;
        if (parseInt(quantityInput.value) > selectedVariant.stock) {
            quantityInput.value = selectedVariant.stock || 1;
        }
    }

    async function addToCart() {
        const productId = {{ $product->id }};
        const quantity = parseInt(document.getElementById('quantity').value);

        if ({{ $product->has_variants ? 'true' : 'false' }} && !selectedVariant) {
            alert('Please select a variant');
            return;
        }

        const button = document.getElementById('add-to-cart-btn');
        const originalText = button.textContent;
        button.disabled = true;
        button.textContent = 'Adding...';

        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    product_variant_id: selectedVariant?.id || null,
                    quantity: quantity
                })
            });

            const data = await response.json();
            
            if (data.success) {
                button.textContent = 'Added to Cart!';
                showToast(data.message, 'success');
                updateCartCounter();
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 2000);
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            button.textContent = originalText;
            button.disabled = false;
            showToast(error.message || 'Error adding to cart', 'error');
        }
    }

    // Auto-select first variant on load
    document.addEventListener('DOMContentLoaded', function() {
        const firstVariant = document.querySelector('.variant-option');
        if (firstVariant) {
            firstVariant.click();
        }
    });
</script>
@endpush