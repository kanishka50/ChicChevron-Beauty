@extends('layouts.app')

@section('title', $product->name . ' - ChicChevron Beauty')
@section('description', Str::limit(strip_tags($product->description), 160))

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="container-responsive">
        <ol class="flex items-center space-x-1 md:space-x-2 text-xs md:text-sm flex-wrap">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-600 transition-colors">Home</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">Products</a></li>
            @if($product->category)
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="text-gray-500 hover:text-primary-600 transition-colors">{{ $product->category->name }}</a></li>
            @endif
            <li class="text-gray-400">/</li>
            <li class="text-gray-900 font-medium truncate max-w-[200px]">{{ $product->name }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-responsive py-4 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <!-- Enhanced Product Images -->
            <div class="space-y-4">
                <!-- Main Image with Modern Styling -->
                <div class="relative aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl overflow-hidden shadow-lg group">
                    <img id="main-image" 
                         src="{{ $product->main_image ? asset('storage/' . $product->main_image) : '/placeholder.jpg' }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-contain p-4 md:p-8 cursor-zoom-in transform transition-transform duration-500 group-hover:scale-105"
                         onclick="openImageModal(this.src)">
                    
                    <!-- Zoom indicator -->
                    <div class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Thumbnail Images with Better Styling -->
                @if($product->images->isNotEmpty() || $product->main_image)
                    <div class="flex gap-2 md:gap-3 overflow-x-auto pb-2 scrollbar-hide">
                        <!-- Main image thumbnail -->
                        @if($product->main_image)
                            <button class="flex-shrink-0 w-20 h-20 md:w-24 md:h-24 bg-white rounded-xl overflow-hidden border-2 border-primary-500 shadow-md transition-all duration-200 hover:shadow-lg" 
                                    onclick="changeMainImage('{{ asset('storage/' . $product->main_image) }}', this)">
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover">
                            </button>
                        @endif

                        <!-- Additional images -->
                        @foreach($product->images as $image)
                            <button class="flex-shrink-0 w-20 h-20 md:w-24 md:h-24 bg-white rounded-xl overflow-hidden border-2 border-transparent hover:border-gray-300 shadow-sm transition-all duration-200 hover:shadow-md" 
                                    onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Enhanced Product Details -->
            <div class="space-y-6">
                <!-- Brand, Title & Rating -->
                <div>
                    @if($product->brand)
                        <a href="{{ route('products.index', ['brands' => [$product->brand->id]]) }}" 
                           class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium text-sm md:text-base transition-colors">
                            @if($product->brand->logo)
                                <img src="{{ asset('storage/' . $product->brand->logo) }}" alt="{{ $product->brand->name }}" class="h-6 w-auto">
                            @else
                                {{ $product->brand->name }}
                            @endif
                        </a>
                    @endif
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mt-2 leading-tight">{{ $product->name }}</h1>
                    
                    <!-- Enhanced Rating Display -->
                    @if($product->reviews->isNotEmpty())
                        <div class="flex items-center gap-3 mt-4">
                            <div class="flex items-center bg-yellow-50 px-3 py-1 rounded-full">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 md:w-5 md:h-5 {{ $i <= round($product->reviews->avg('rating')) ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                <span class="ml-2 font-semibold text-gray-900">{{ number_format($product->reviews->avg('rating'), 1) }}</span>
                            </div>
                            <a href="#reviews" class="text-sm text-gray-600 hover:text-primary-600 transition-colors">
                                ({{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }})
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Enhanced Price Section -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 space-y-4">
                    <div id="price-display">
                        @if($product->variants->count() > 1)
                            <div class="flex items-baseline gap-2">
                                <span class="text-sm text-gray-600">Starting from</span>
                                <span class="text-3xl md:text-4xl font-bold text-gray-900">
                                    Rs. {{ number_format($product->variants->min('price'), 2) }}
                                </span>
                            </div>
                        @else
                            <span class="text-3xl md:text-4xl font-bold text-gray-900">
                                Rs. {{ number_format($product->variants->first()->price ?? 0, 2) }}
                            </span>
                        @endif
                    </div>

                    <!-- Stock Status with Visual Indicator -->
                    <div id="stock-status" class="flex items-center gap-2">
                        @if($product->getStockLevel() > 0)
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <p class="text-green-700 font-medium">In Stock</p>
                                <span class="text-gray-600">({{ $product->getStockLevel() }} available)</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <p class="text-red-700 font-medium">Out of Stock</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Modern Variant Selection -->
                @if($product->has_variants && $product->variants->isNotEmpty())
                    <div class="space-y-4" id="variant-selection">
                        <label class="block text-base font-semibold text-gray-900">Choose Your Option</label>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($product->variants as $variant)
                                <button type="button" 
                                        class="variant-option group relative flex items-center justify-between p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-primary-400 hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                        data-variant-id="{{ $variant->id }}"
                                        data-price="{{ $variant->price }}"
                                        data-stock="{{ $variant->available_stock }}"
                                        data-sku="{{ $variant->sku }}"
                                        onclick="selectProductVariant(this)">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-primary-100 to-primary-200 rounded-lg flex items-center justify-center">
                                            <span class="text-primary-700 font-medium text-sm">{{ substr($variant->display_name, 0, 2) }}</span>
                                        </div>
                                        <div class="text-left">
                                            <span class="font-medium text-gray-900">{{ $variant->display_name }}</span>
                                            @if($variant->available_stock < 5 && $variant->available_stock > 0)
                                                <p class="text-xs text-orange-600 mt-0.5">Only {{ $variant->available_stock }} left!</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-semibold text-gray-900">Rs. {{ number_format($variant->price, 2) }}</span>
                                        @if($variant->available_stock == 0)
                                            <p class="text-xs text-red-600 mt-0.5">Out of stock</p>
                                        @endif
                                    </div>
                                    <!-- Selected indicator -->
                                    <div class="absolute inset-0 border-2 border-primary-500 rounded-xl opacity-0 pointer-events-none transition-opacity duration-200"></div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Enhanced Quantity and Add to Cart -->
                <div class="space-y-4">
                    <!-- Quantity Selector -->
                    <div class="flex items-center justify-between">
                        <label class="text-base font-medium text-gray-900">Quantity</label>
                        <div class="flex items-center bg-gray-100 rounded-xl overflow-hidden">
                            <button type="button" 
                                    onclick="changeQuantity(-1)" 
                                    class="px-4 py-3 text-gray-600 hover:bg-gray-200 transition-colors focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <input type="number" 
                                   id="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="10" 
                                   class="w-16 text-center bg-transparent border-0 focus:ring-0 font-medium">
                            <button type="button" 
                                    onclick="changeQuantity(1)" 
                                    class="px-4 py-3 text-gray-600 hover:bg-gray-200 transition-colors focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button id="add-to-cart-btn" 
                                onclick="addToCart()"
                                class="flex-1 bg-gradient-to-r from-primary-600 to-primary-700 text-white py-4 px-6 rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 transform hover:scale-[1.02] transition-all duration-200 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed disabled:transform-none shadow-lg">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Add to Cart
                            </span>
                        </button>
                        
                        <button onclick="toggleWishlist({{ $product->id }})" 
                                class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-primary-400 hover:bg-primary-50 transition-all duration-200 group"
                                data-product-id="{{ $product->id }}">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Trust Badges -->
                    <div class="grid grid-cols-3 gap-3 pt-4">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-600">100% Authentic</p>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-600">Secure Payment</p>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-600">Fast Delivery</p>
                        </div>
                    </div>
                </div>

                <!-- Product Features Grid -->
                <div class="bg-gray-50 rounded-2xl p-6 space-y-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Product Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($product->texture)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Texture</p>
                                    <p class="text-sm text-gray-900">{{ $product->texture->name }}</p>
                                </div>
                            </div>
                        @endif

                        @if($product->suitable_for)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Suitable for</p>
                                    <p class="text-sm text-gray-900">{{ $product->suitable_for }}</p>
                                </div>
                            </div>
                        @endif

                        @if($product->fragrance)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Fragrance</p>
                                    <p class="text-sm text-gray-900">{{ $product->fragrance }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">SKU</p>
                                <p class="text-sm text-gray-900" id="product-sku">{{ $product->sku }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Product Tabs -->
        <div class="mt-12 md:mt-16">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 overflow-x-auto">
                <nav class="-mb-px flex space-x-4 md:space-x-8 min-w-max">
                    <button class="tab-button active py-3 px-1 border-b-2 border-primary-600 font-semibold text-sm md:text-base text-primary-600 whitespace-nowrap transition-colors" onclick="showTab('description')">
                        Description
                    </button>
                    @if($product->ingredients->isNotEmpty())
                        <button class="tab-button py-3 px-1 border-b-2 border-transparent font-medium text-sm md:text-base text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap transition-colors" onclick="showTab('ingredients')">
                            Ingredients
                        </button>
                    @endif
                    @if($product->how_to_use)
                        <button class="tab-button py-3 px-1 border-b-2 border-transparent font-medium text-sm md:text-base text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap transition-colors" onclick="showTab('how-to-use')">
                            How to Use
                        </button>
                    @endif
                    @if($product->reviews->isNotEmpty())
                        <button class="tab-button py-3 px-1 border-b-2 border-transparent font-medium text-sm md:text-base text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap transition-colors" onclick="showTab('reviews')">
                            Reviews ({{ $product->reviews->count() }})
                        </button>
                    @endif
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-8">
                <!-- Description Tab -->
                <div id="description-tab" class="tab-content">
                    <div class="prose prose-gray max-w-none">
                        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Ingredients Tab -->
                @if($product->ingredients->isNotEmpty())
                    <div id="ingredients-tab" class="tab-content hidden">
                        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Full Ingredient List</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($product->ingredients as $ingredient)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="w-2 h-2 bg-primary-500 rounded-full flex-shrink-0"></div>
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
                        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Application Instructions</h3>
                            <div class="prose prose-gray max-w-none">
                                {!! nl2br(e($product->how_to_use)) !!}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Reviews Tab -->
                @if($product->reviews->isNotEmpty())
                    <div id="reviews-tab" class="tab-content hidden">
                        <div class="space-y-6">
                            <!-- Review Summary Card -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 md:p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Rating Overview -->
                                    <div class="text-center md:text-left">
                                        <div class="inline-flex items-baseline gap-2">
                                            <span class="text-5xl font-bold text-gray-900">{{ number_format($product->reviews->avg('rating'), 1) }}</span>
                                            <span class="text-gray-600">out of 5</span>
                                        </div>
                                        <div class="flex justify-center md:justify-start mt-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-6 h-6 {{ $i <= round($product->reviews->avg('rating')) ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-gray-600 mt-2">Based on {{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}</p>
                                    </div>

                                    <!-- Rating Distribution -->
                                    <div class="space-y-2">
                                        @for($rating = 5; $rating >= 1; $rating--)
                                            @php
                                                $count = $product->reviews->where('rating', $rating)->count();
                                                $percentage = $product->reviews->count() > 0 ? ($count / $product->reviews->count()) * 100 : 0;
                                            @endphp
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm text-gray-600 w-4">{{ $rating }}</span>
                                                <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="text-sm text-gray-600 w-8 text-right">{{ $count }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            @if($product->has_variants)
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <p class="text-sm text-blue-800 flex items-start gap-2">
                                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span><strong>Note:</strong> Reviews are for the product overall and apply to all variants (sizes, colors, scents).</span>
                                    </p>
                                </div>
                            @endif

                            <!-- Individual Reviews -->
                            <div class="space-y-6">
                                @foreach($product->reviews->take(5) as $review)
                                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                        <div class="flex items-start justify-between mb-4">
                                            <div>
                                                <div class="flex items-center gap-3 mb-2">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                        {{ substr($review->user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                                                        <div class="flex items-center gap-2">
                                                            <div class="flex">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                    </svg>
                                                                @endfor
                                                            </div>
                                                            <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($review->is_verified_purchase)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Verified Purchase
                                                </span>
                                            @endif
                                        </div>
                                        @if($review->title)
                                            <h4 class="font-semibold text-gray-900 mb-2">{{ $review->title }}</h4>
                                        @endif
                                        <p class="text-gray-700 leading-relaxed">{{ $review->review }}</p>
                                    </div>
                                @endforeach

                                @if($product->reviews->count() > 5)
                                    <div class="text-center pt-4">
                                        <button class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-primary-600 text-primary-600 rounded-xl font-medium hover:bg-primary-50 transition-colors">
                                            View All {{ $product->reviews->count() }} Reviews
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-16 md:mt-20">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">You May Also Like</h2>
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}" 
                       class="text-primary-600 hover:text-primary-700 font-medium text-sm md:text-base flex items-center gap-1 transition-colors">
                        View More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        @include('components.shop.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Enhanced Image Modal -->
    <div id="image-modal" class="fixed inset-0 bg-black/90 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
        <div class="relative max-w-6xl max-h-full" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" 
                    class="absolute -top-12 right-0 text-white bg-white/10 backdrop-blur-sm rounded-full w-10 h-10 flex items-center justify-center hover:bg-white/20 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modal-image" src="" alt="" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let selectedVariant = null;

    function changeMainImage(src, thumbnail) {
        const mainImage = document.getElementById('main-image');
        mainImage.src = src;
        
        // Update thumbnail borders
        document.querySelectorAll('button[onclick^="changeMainImage"]').forEach(btn => {
            btn.classList.remove('border-primary-500');
            btn.classList.add('border-transparent');
        });
        thumbnail.classList.remove('border-transparent');
        thumbnail.classList.add('border-primary-500');
    }

    function openImageModal(src) {
        const modal = document.getElementById('image-modal');
        const modalImage = document.getElementById('modal-image');
        modalImage.src = src;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('image-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function changeQuantity(delta) {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value) + delta;
        const max = parseInt(input.max);
        const min = parseInt(input.min);
        
        if (value < min) value = min;
        if (value > max) value = max;
        
        input.value = value;
    }

    function selectProductVariant(button) {
        // Remove active state from all buttons
        document.querySelectorAll('.variant-option').forEach(btn => {
            btn.classList.remove('border-primary-500', 'bg-primary-50', 'shadow-md');
            btn.classList.add('border-gray-200', 'bg-white');
            btn.querySelector('.absolute').classList.add('opacity-0');
        });

        // Add active state
        button.classList.remove('border-gray-200', 'bg-white');
        button.classList.add('border-primary-500', 'bg-primary-50', 'shadow-md');
        button.querySelector('.absolute').classList.remove('opacity-0');

        // Update selected variant
        selectedVariant = {
            id: button.dataset.variantId,
            price: button.dataset.price,
            stock: parseInt(button.dataset.stock),
            sku: button.dataset.sku
        };

        // Update price display with animation
        const priceDisplay = document.getElementById('price-display');
        priceDisplay.classList.add('opacity-0');
        setTimeout(() => {
            priceDisplay.innerHTML = 
                `<span class="text-3xl md:text-4xl font-bold text-gray-900">Rs. ${parseFloat(selectedVariant.price).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>`;
            priceDisplay.classList.remove('opacity-0');
        }, 150);

        // Update stock status
        const stockStatusEl = document.getElementById('stock-status');
        if (selectedVariant.stock > 0) {
            stockStatusEl.innerHTML = `
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <p class="text-green-700 font-medium">In Stock</p>
                    <span class="text-gray-600">(${selectedVariant.stock} available)</span>
                </div>`;
            document.getElementById('add-to-cart-btn').disabled = false;
        } else {
            stockStatusEl.innerHTML = `
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    <p class="text-red-700 font-medium">Out of Stock</p>
                </div>`;
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
            showToast('Please select a variant', 'error');
            return;
        }

        const button = document.getElementById('add-to-cart-btn');
        const originalContent = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `
            <span class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Adding...
            </span>`;

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
                button.innerHTML = `
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Added to Cart!
                    </span>`;
                button.classList.add('from-green-600', 'to-green-700');
                showToast(data.message, 'success');
                
                // Dispatch event to update cart counter
                window.dispatchEvent(new Event('cart-updated'));
                
                setTimeout(() => {
                    button.innerHTML = originalContent;
                    button.classList.remove('from-green-600', 'to-green-700');
                    button.disabled = false;
                }, 2000);
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            button.innerHTML = originalContent;
            button.disabled = false;
            showToast(error.message || 'Error adding to cart', 'error');
        }
    }

    // Tab functionality
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Remove active class from all tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-primary-600', 'text-primary-600', 'font-semibold');
            button.classList.add('border-transparent', 'text-gray-500', 'font-medium');
        });
        
        // Show selected tab content
        const selectedTab = document.getElementById(tabName + '-tab');
        if (selectedTab) {
            selectedTab.classList.remove('hidden');
        }
        
        // Add active class to clicked button
        event.target.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
        event.target.classList.add('active', 'border-primary-600', 'text-primary-600', 'font-semibold');
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-select first variant
        const firstVariant = document.querySelector('.variant-option');
        if (firstVariant) {
            firstVariant.click();
        }

        // Keyboard navigation for image modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('image-modal').classList.contains('hidden')) {
                closeImageModal();
            }
        });
    });
</script>
@endpush

<style>
/* Smooth transitions for all interactive elements */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Hide scrollbar for thumbnail container */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Smooth fade transitions */
.opacity-0 {
    transition: opacity 150ms ease-in-out;
}
</style>