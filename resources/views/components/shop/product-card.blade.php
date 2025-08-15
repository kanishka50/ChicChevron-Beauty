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
                    $isInWishlist = $product->isInWishlist();
                @endphp
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }})" 
                        class="p-2 bg-white rounded-lg shadow hover:bg-gray-50 transition-colors"
                        data-product-id="{{ $product->id }}"
                        title="Add to wishlist">
                    <svg class="w-5 h-5 {{ $isInWishlist ? 'text-pink-500 fill-current' : 'text-gray-500' }}" 
                         fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>

                <!-- Quick View Button - Desktop only -->
                {{-- <button onclick="event.preventDefault(); event.stopPropagation(); quickView({{ $product->id }})" 
                        class="hidden sm:block p-2 bg-white rounded-lg shadow hover:bg-gray-50 transition-colors"
                        title="Quick view">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button> --}}
            </div>

            <!-- Product Image -->
            <img class="p-3 sm:p-4 rounded-t-lg w-full h-40 sm:h-48 object-contain" 
                 src="{{ $product->main_image ? asset('storage/' . $product->main_image) : '/placeholder.jpg' }}" 
                 alt="{{ $product->name }}" />

            <!-- Out of Stock Overlay -->
            @if(!$product->hasStock())
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center rounded-t-lg">
                    <span class="bg-white text-gray-900 px-4 py-2 rounded-lg text-sm font-medium">
                        Out of Stock
                    </span>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="px-3 sm:px-4 pb-4">
            <!-- Brand -->
            @if($product->brand)
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                    {{ $product->brand->name }}
                </p>
            @endif

            <!-- Product Name -->
            <h5 class="text-sm sm:text-base font-semibold tracking-tight text-gray-900 group-hover:text-primary-600 line-clamp-2 min-h-[2.5rem] sm:min-h-[3rem] mb-2 transition-colors">
                {{ $product->name }}
            </h5>

            <!-- Rating -->
            @if($product->reviews_count > 0)
                <div class="flex items-center mb-3">
                    <div class="flex items-center space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="bg-primary-100 text-primary-800 text-xs font-semibold px-2.5 py-0.5 rounded ms-3">
                        {{ number_format($product->average_rating, 1) }}
                    </span>
                    <span class="text-xs text-gray-500 ms-1">({{ $product->reviews_count }})</span>
                </div>
            @else
                <div class="mb-3">
                    <span class="text-xs text-gray-500">No reviews yet</span>
                </div>
            @endif

            <!-- Price Section -->
            <div class="flex items-baseline gap-2">
                @if($product->has_discount)
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

<script>
// Keep existing wishlist functionality
async function toggleWishlist(productId) {
    const button = event.target.closest('button');
    const svg = button.querySelector('svg');
    
    try {
        const response = await fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.success) {
            if (data.added) {
                svg.classList.add('text-pink-500', 'fill-current');
                svg.classList.remove('text-gray-500');
                svg.setAttribute('fill', 'currentColor');
            } else {
                svg.classList.remove('text-pink-500', 'fill-current');
                svg.classList.add('text-gray-500');
                svg.setAttribute('fill', 'none');
            }
            
            window.showToast?.(data.message, 'success');
        } else {
            window.showToast?.(data.message || 'Error updating wishlist', 'error');
        }
    } catch (error) {
        window.showToast?.('Error updating wishlist', 'error');
    }
}

// Quick view functionality
function quickView(productId) {
    // Redirect to product page
    window.location.href = `/products/${productId}`;
}
</script>