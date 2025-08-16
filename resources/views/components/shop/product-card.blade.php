<!-- Modern Product Card Component - Clickable Card Design -->
<div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
    <!-- Make the entire card clickable except for action buttons -->
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        <!-- Image Container with Actions -->
        <div class="relative">
            <!-- Badges - Clean and subtle -->
            <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
                @if($product->is_new)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">
                        NEW
                    </span>
                @endif
                
                @if($product->discount_price && $product->discount_price < $product->selling_price)
                    @php
                        $discountPercent = round((($product->selling_price - $product->discount_price) / $product->selling_price) * 100);
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">
                        -{{ $discountPercent }}%
                    </span>
                @endif
            </div>

            <!-- Action Buttons - Always visible for better UX -->
            <div class="absolute top-2 right-2 z-10 flex gap-2">
                <!-- Wishlist Button -->
                @php
                    $isInWishlist = auth()->check() ? $product->isInWishlist() : false;
                @endphp
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); window.toggleWishlist({{ $product->id }})" 
                        class="wishlist-btn p-2 bg-white rounded-lg shadow hover:bg-gray-50 transition-colors"
                        data-product-id="{{ $product->id }}"
                        title="{{ $isInWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                    <svg class="w-5 h-5 {{ $isInWishlist ? 'text-pink-500 fill-current' : 'text-gray-500' }}" 
                         fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <!-- Quick View Button -->
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); window.location.href='/products/{{ $product->slug }}'" 
                        class="p-2 bg-white rounded-lg shadow hover:bg-gray-50 transition-colors"
                        title="Quick view">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <!-- Product Image -->
            <div class="aspect-square overflow-hidden bg-gray-100">
                @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                         loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="p-4">
            <!-- Category -->
            @if($product->category)
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">
                    {{ $product->category->name }}
                </p>
            @endif

            <!-- Product Name -->
            <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1 line-clamp-2">
                {{ $product->name }}
            </h3>

            <!-- Brand -->
            @if($product->brand)
                <p class="text-sm text-gray-600 mb-2">{{ $product->brand->name }}</p>
            @endif

            <!-- Price -->
            <div class="flex items-baseline gap-2">
                @if($product->starting_discount_price)
                    <span class="text-lg sm:text-xl font-bold text-gray-900">
                        Rs. {{ number_format($product->starting_discount_price ?? $product->starting_price, 0) }}
                    </span>
                    @if($product->starting_discount_price)
                        <span class="text-sm text-gray-500 line-through">
                            Rs. {{ number_format($product->starting_price, 0) }}
                        </span>
                    @endif
                @else
                    <span class="text-lg sm:text-xl font-bold text-gray-900">
                        Rs. {{ number_format($product->starting_price, 0) }}
                    </span>
                @endif
            </div>

            <!-- Stock Status Text (Optional - only for out of stock) -->
            @if(!$product->hasStock())
                <p class="text-sm text-red-600 mt-2">Out of Stock</p>
            @endif
        </div>
    </a>
</div>