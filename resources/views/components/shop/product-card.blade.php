<!-- Modern Product Card Component - Clickable Card Design -->
<div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer group">
    <!-- Make the entire card clickable except for action buttons -->
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        <!-- Image Container with Actions -->
        <div class="relative overflow-hidden rounded-t-lg">
            <!-- Badges - Clean and subtle -->
            <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
                @if($product->is_new)
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold bg-green-100 text-green-800 rounded">
                        NEW
                    </span>
                @endif

                @if($product->has_discount && $product->starting_discount_price && $product->starting_price > 0)
                    @php
                        $discountPercent = round((($product->starting_price - $product->starting_discount_price) / $product->starting_price) * 100);
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold bg-red-100 text-red-800 rounded">
                        -{{ $discountPercent }}%
                    </span>
                @endif
            </div>

            <!-- Action Buttons - Always visible for better UX -->
            <div class="absolute top-2 right-2 z-10 flex gap-1.5">
                <!-- Wishlist Button -->
                @php
                    $isInWishlist = $product->isInWishlist();
                @endphp
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }})"
                        class="p-1.5 bg-white/90 backdrop-blur-sm rounded-full shadow-sm hover:bg-white transition-colors"
                        data-product-id="{{ $product->id }}"
                        title="Add to wishlist">
                    <svg class="w-4 h-4 {{ $isInWishlist ? 'text-pink-500 fill-current' : 'text-gray-600' }}"
                         fill="{{ $isInWishlist ? 'currentColor' : 'none' }}"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            </div>

            <!-- Product Image - Full width, no padding -->
            <img class="w-full h-44 sm:h-52 object-cover transition-transform duration-300 group-hover:scale-105"
                 src="{{ $product->main_image ? asset('storage/' . $product->main_image) : '/placeholder.jpg' }}"
                 alt="{{ $product->name }}" />

            <!-- Out of Stock Overlay -->
            @if(!$product->hasStock())
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                    <span class="bg-white text-gray-900 px-3 py-1.5 rounded text-xs font-medium">
                        Out of Stock
                    </span>
                </div>
            @endif
        </div>

        <!-- Product Info - Tighter spacing -->
        <div class="px-3 pt-3 pb-3">
            <!-- Brand -->
            @if($product->brand)
                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">
                    {{ $product->brand->name }}
                </p>
            @endif

            <!-- Product Name -->
            <h5 class="text-xs sm:text-sm font-semibold tracking-tight text-gray-900 group-hover:text-primary-600 line-clamp-2 min-h-[2rem] sm:min-h-[2.5rem] mb-1.5 leading-tight transition-colors">
                {{ $product->name }}
            </h5>

            <!-- Price Section -->
            <div class="flex items-baseline gap-1.5">
                @if($product->has_discount)
                    <span class="text-base sm:text-lg font-bold text-gray-900">
                        Rs. {{ number_format($product->starting_discount_price ?? $product->starting_price, 0) }}
                    </span>
                    @if($product->starting_discount_price)
                        <span class="text-xs text-gray-400 line-through">
                            Rs. {{ number_format($product->starting_price, 0) }}
                        </span>
                    @endif
                @else
                    <span class="text-base sm:text-lg font-bold text-gray-900">
                        Rs. {{ number_format($product->starting_price, 0) }}
                    </span>
                @endif
            </div>

            <!-- Stock Status Text (Optional - only for out of stock) -->
            @if(!$product->hasStock())
                <p class="text-xs text-red-600 mt-1">Out of Stock</p>
            @endif
        </div>
    </a>
</div>
