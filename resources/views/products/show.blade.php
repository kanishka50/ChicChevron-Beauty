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
                        @if($product->has_variants && $product->variantCombinations->isNotEmpty())
                            @if($priceRange['min'] != $priceRange['max'])
                                <span class="text-3xl font-bold text-gray-900">
                                    Rs. {{ number_format($priceRange['min'], 2) }} - Rs. {{ number_format($priceRange['max'], 2) }}
                                </span>
                            @else
                                <span class="text-3xl font-bold text-gray-900">Rs. {{ number_format($priceRange['min'], 2) }}</span>
                            @endif
                        @else
                            <div class="flex items-center gap-3">
                                @if($product->discount_price && $product->discount_price < $product->selling_price)
                                    <span class="text-3xl font-bold text-gray-900">Rs. {{ number_format($product->discount_price, 2) }}</span>
                                    <span class="text-xl text-gray-500 line-through">Rs. {{ number_format($product->selling_price, 2) }}</span>
                                    @php
                                        $discountPercent = round((($product->selling_price - $product->discount_price) / $product->selling_price) * 100);
                                    @endphp
                                    <span class="bg-red-500 text-white text-sm px-2 py-1 rounded">-{{ $discountPercent }}%</span>
                                @else
                                    <span class="text-3xl font-bold text-gray-900">Rs. {{ number_format($product->selling_price, 2) }}</span>
                                @endif
                            </div>
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

                <!-- Variants Selection -->
                @if($product->has_variants && $availableVariants)
                    <div class="space-y-4" id="variant-selection">
                        <!-- Size Selection -->
                        @if($availableVariants['sizes']->isNotEmpty())
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Size</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($availableVariants['sizes'] as $size)
                                        <button type="button" 
                                                class="variant-option size-variant px-4 py-2 border border-gray-300 rounded-md text-sm font-medium hover:border-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                                data-variant-type="size"
                                                data-variant-id="{{ $size->id }}"
                                                data-variant-value="{{ $size->variant_value }}"
                                                onclick="selectVariant(this)">
                                            {{ $size->variant_value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Color Selection -->
                        @if($availableVariants['colors']->isNotEmpty())
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Color</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($availableVariants['colors'] as $color)
                                        <button type="button" 
                                                class="variant-option color-variant flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md text-sm font-medium hover:border-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                                data-variant-type="color"
                                                data-variant-id="{{ $color->id }}"
                                                data-variant-value="{{ $color->variant_value }}"
                                                onclick="selectVariant(this)">
                                            @if($product->colors->where('name', $color->variant_value)->first())
                                                <div class="w-4 h-4 rounded-full border border-gray-300" 
                                                     style="background-color: {{ $product->colors->where('name', $color->variant_value)->first()->color_code }}">
                                                </div>
                                            @endif
                                            {{ $color->variant_value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Scent Selection -->
                        @if($availableVariants['scents']->isNotEmpty())
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Scent</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($availableVariants['scents'] as $scent)
                                        <button type="button" 
                                                class="variant-option scent-variant px-4 py-2 border border-gray-300 rounded-md text-sm font-medium hover:border-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                                data-variant-type="scent"
                                                data-variant-id="{{ $scent->id }}"
                                                data-variant-value="{{ $scent->variant_value }}"
                                                onclick="selectVariant(this)">
                                            {{ $scent->variant_value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Selected Variant Info -->
                        <div id="selected-variant-info" class="hidden bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Selected:</h4>
                            <div id="variant-details" class="text-sm text-gray-600"></div>
                            <div id="variant-sku" class="text-xs text-gray-500 mt-1"></div>
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
                                    <a href="{{ route('reviews.create', $product) }}" class="text-pink-600 hover:text-pink-700 font-medium">
                                        Write a Review
                                    </a>
                                @endauth
                            </div>

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
        let selectedVariants = {
            size: null,
            color: null,
            scent: null
        };

        let currentCombination = null;

        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-pink-600', 'text-pink-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');

            // Add active class to clicked tab button
            event.target.classList.add('active', 'border-pink-600', 'text-pink-600');
            event.target.classList.remove('border-transparent', 'text-gray-500');
        }

        // Image functionality
        function changeMainImage(src, thumbnail) {
            document.getElementById('main-image').src = src;
            
            // Update active thumbnail
            document.querySelectorAll('.aspect-square button').forEach(btn => {
                btn.classList.remove('border-pink-600');
                btn.classList.add('border-transparent');
            });
            thumbnail.classList.add('border-pink-600');
            thumbnail.classList.remove('border-transparent');
        }

        function openImageModal(src) {
            document.getElementById('modal-image').src = src;
            document.getElementById('image-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('image-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Variant selection
        function selectVariant(button) {
            const variantType = button.dataset.variantType;
            const variantId = button.dataset.variantId;
            const variantValue = button.dataset.variantValue;

            // Remove active state from other variants of same type
            document.querySelectorAll(`.${variantType}-variant`).forEach(btn => {
                btn.classList.remove('border-pink-600', 'bg-pink-50');
                btn.classList.add('border-gray-300');
            });

            // Add active state to selected variant
            button.classList.add('border-pink-600', 'bg-pink-50');
            button.classList.remove('border-gray-300');

            // Update selected variants
            selectedVariants[variantType] = {
                id: variantId,
                value: variantValue
            };

            // Update variant combination details
            updateVariantCombination();
        }

        function updateVariantCombination() {
            // Check if we have a complete combination (for products with variants)
            const hasVariants = {{ $product->has_variants ? 'true' : 'false' }};
            
            if (!hasVariants) return;

            // Get variant details for API call
            const sizeId = selectedVariants.size?.id || null;
            const colorId = selectedVariants.color?.id || null;
            const scentId = selectedVariants.scent?.id || null;

            // Make API call to get variant combination details
            fetch(`{{ route('products.variant-details', $product) }}?size_id=${sizeId}&color_id=${colorId}&scent_id=${scentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentCombination = data.data;
                        
                        // Update price display
                        document.getElementById('price-display').innerHTML = `
                            <span class="text-3xl font-bold text-gray-900">Rs. ${data.data.price}</span>
                        `;

                        // Update stock status
                        const stockStatusEl = document.getElementById('stock-status');
                        if (data.data.in_stock) {
                            stockStatusEl.innerHTML = `<p class="text-green-600 font-medium">✓ In Stock (${data.data.stock_level} available)</p>`;
                            document.getElementById('add-to-cart-btn').disabled = false;
                        } else {
                            stockStatusEl.innerHTML = `<p class="text-red-600 font-medium">✗ Out of Stock</p>`;
                            document.getElementById('add-to-cart-btn').disabled = true;
                        }

                        // Update SKU
                        document.getElementById('product-sku').textContent = data.data.sku;

                        // Show selected variant info
                        const infoDiv = document.getElementById('selected-variant-info');
                        const detailsDiv = document.getElementById('variant-details');
                        const skuDiv = document.getElementById('variant-sku');
                        
                        detailsDiv.textContent = data.data.variant_details;
                        skuDiv.textContent = `SKU: ${data.data.sku}`;
                        infoDiv.classList.remove('hidden');

                        // Update quantity max based on stock
                        const quantityInput = document.getElementById('quantity');
                        quantityInput.max = data.data.stock_level;
                        if (parseInt(quantityInput.value) > data.data.stock_level) {
                            quantityInput.value = data.data.stock_level;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching variant details:', error);
                });
        }

        // Quantity management
        function changeQuantity(delta) {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            const newValue = currentValue + delta;
            const min = parseInt(quantityInput.min);
            const max = parseInt(quantityInput.max);

            if (newValue >= min && newValue <= max) {
                quantityInput.value = newValue;
            }
        }

        async function addToCart() {
            const productId = {{ $product->id }};
            const quantity = parseInt(document.getElementById('quantity').value);
            const variantCombinationId = currentCombination?.combination_id || null;

            // Validate variant selection for products with variants
            const hasVariants = {{ $product->has_variants ? 'true' : 'false' }};
            if (hasVariants && !currentCombination) {
                alert('Please select all product options before adding to cart.');
                return;
            }

            // Show loading state
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
                        variant_combination_id: variantCombinationId,
                        quantity: quantity
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Success state
                    button.textContent = 'Added to Cart!';
                    button.classList.add('bg-green-600');
                    button.classList.remove('bg-pink-600');
                    
                    // Show success message
                    showToast(data.message, 'success');
                    
                    // Update cart counter if it exists
                    updateCartCounter();
                    
                    // Ask if user wants to go to cart
                    setTimeout(() => {
                        if (confirm('Item added to cart! Would you like to view your cart?')) {
                            window.location.href = '/cart';
                        } else {
                            // Reset button
                            button.textContent = originalText;
                            button.classList.remove('bg-green-600');
                            button.classList.add('bg-pink-600');
                            button.disabled = false;
                        }
                    }, 1500);
                } else {
                    // Error state
                    button.textContent = 'Error';
                    button.classList.add('bg-red-600');
                    button.classList.remove('bg-pink-600');
                    
                    showToast(data.message, 'error');
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('bg-red-600');
                        button.classList.add('bg-pink-600');
                        button.disabled = false;
                    }, 2000);
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                
                // Error state
                button.textContent = 'Error';
                button.classList.add('bg-red-600');
                button.classList.remove('bg-pink-600');
                
                showToast('Error adding item to cart. Please try again.', 'error');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-red-600');
                    button.classList.add('bg-pink-600');
                    button.disabled = false;
                }, 2000);
            }
        }


        async function updateCartCounter() {
            try {
                const response = await fetch('/cart/count');
                const data = await response.json();
                
                // Update cart counter in navigation
                const cartCounters = document.querySelectorAll('.cart-counter');
                cartCounters.forEach(counter => {
                    counter.textContent = data.count || 0;
                });
            } catch (error) {
                console.error('Error updating cart counter:', error);
            }
        }

        // Add to wishlist
        function addToWishlist(productId) {
            // This will be implemented when wishlist functionality is added
            console.log('Adding to wishlist:', productId);
            
            // Show temporary feedback
            showToast('Added to wishlist!');
        }

        // Toast notification helper
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Initialize first variant selection if only one option available
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-select if only one option available for each variant type
            ['size', 'color', 'scent'].forEach(type => {
                const variants = document.querySelectorAll(`.${type}-variant`);
                if (variants.length === 1) {
                    variants[0].click();
                }
            });
        });
    </script>
@endpush