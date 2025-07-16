<!-- Enhanced Product Card Component with Cart Integration -->
<div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 group relative">
    <!-- Product Image -->
    <div class="relative aspect-square overflow-hidden rounded-t-lg bg-gray-100">
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : '/placeholder.jpg' }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        </a>
        
        <!-- Wishlist Button -->
        @php
            $isInWishlist = $product->isInWishlist();
        @endphp


        <!-- Wishlist Button -->
        <button type="button"
            onclick="toggleWishlist({{ $product->id }})" 
            class="absolute top-2 right-2 p-2 bg-white rounded-full shadow-md hover:bg-pink-50 transition-colors wishlist-btn"
            data-product-id="{{ $product->id }}">
        <svg class="w-4 h-4 {{ $isInWishlist ? 'text-pink-600 fill-current' : 'text-gray-600' }}" 
            fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" 
            stroke="currentColor" 
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
    </button>

        <!-- Quick View Button -->
        <button onclick="quickView({{ $product->id }})" 
                class="absolute top-2 left-2 p-2 bg-white rounded-full shadow-md hover:bg-gray-50 transition-colors opacity-0 group-hover:opacity-100">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
        </button>

        <!-- Discount Badge -->
        @if($product->discount_price && $product->discount_price < $product->selling_price)
            @php
                $discountPercent = round((($product->selling_price - $product->discount_price) / $product->selling_price) * 100);
            @endphp
            <div class="absolute bottom-2 left-2 bg-red-500 text-white px-2 py-1 rounded-md text-xs font-medium">
                -{{ $discountPercent }}%
            </div>
        @endif

        <!-- Stock Status -->
        @if(!$product->hasStock())
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-t-lg">
                <span class="bg-white text-gray-900 px-3 py-1 rounded-full text-sm font-medium">Out of Stock</span>
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-4">
        <!-- Brand -->
        @if($product->brand)
            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $product->brand->name }}</p>
        @endif

        <!-- Product Name -->
        <h3 class="mt-1 text-sm font-medium text-gray-900 line-clamp-2">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-pink-600 transition-colors">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Price -->
        <div class="mt-2 flex items-center space-x-2">
            @if($product->has_discount)
                <span class="text-lg font-bold text-pink-600">
                    From Rs. {{ number_format($product->starting_discount_price ?? $product->starting_price, 2) }}
                </span>
                @if($product->starting_discount_price)
                    <span class="text-sm text-gray-500 line-through">
                        Rs. {{ number_format($product->starting_price, 2) }}
                    </span>
                @endif
            @else
                <span class="text-lg font-bold text-gray-900">
                    From Rs. {{ number_format($product->starting_price, 2) }}
                </span>
            @endif
        </div>

        <!-- Variants Preview -->
        @if($product->has_variants && $product->variants->isNotEmpty())
            <div class="mt-2">
                <!-- Size Variants -->
                @php
                    $sizeVariants = $product->variants->where('variant_type', 'size')->take(3);
                    $colorVariants = $product->variants->where('variant_type', 'color')->take(4);
                @endphp

                @if($sizeVariants->isNotEmpty())
                    <div class="flex items-center space-x-1 mb-1">
                        <span class="text-xs text-gray-500">Sizes:</span>
                        @foreach($sizeVariants as $variant)
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $variant->variant_value }}</span>
                        @endforeach
                        @if($product->variants->where('variant_type', 'size')->count() > 3)
                            <span class="text-xs text-gray-500">+{{ $product->variants->where('variant_type', 'size')->count() - 3 }} more</span>
                        @endif
                    </div>
                @endif

                @if($colorVariants->isNotEmpty())
                    <div class="flex items-center space-x-1">
                        <span class="text-xs text-gray-500">Colors:</span>
                        <div class="flex space-x-1">
                            @foreach($colorVariants as $variant)
                                @php
                                    $color = $variant->color ?? null;
                                    $colorCode = $color ? $color->color_code : '#ccc';
                                @endphp
                                <div class="w-4 h-4 rounded-full border border-gray-300" 
                                     style="background-color: {{ $colorCode }}"
                                     title="{{ $variant->variant_value }}"></div>
                            @endforeach
                            @if($colorVariants->count() > 4)
                                <span class="text-xs text-gray-500">+{{ $product->variants->where('variant_type', 'color')->count() - 4 }}</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Rating (if you have reviews) -->
        @if($product->reviews_count > 0)
            <div class="mt-2 flex items-center space-x-1">
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $product->average_rating)
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @else
                            <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-xs text-gray-500">({{ $product->reviews_count }})</span>
            </div>
        @endif

        <!-- Add to Cart Button -->
        @if($product->hasStock())
            @if($product->has_variants)
                <a href="{{ route('products.show', $product->slug) }}" 
                class="mt-3 w-full bg-gray-100 text-gray-900 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors text-center text-sm font-medium block">
                    Select Options
                </a>
            @else
                <button onclick="addToCart({{ $product->id }}, null, 1)" 
                        class="mt-3 w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition-colors text-sm font-medium add-to-cart-btn"
                        data-product-id="{{ $product->id }}">
                    Add to Cart
                </button>
            @endif
        @else
            <button disabled 
                    class="mt-3 w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg text-sm font-medium cursor-not-allowed">
                Out of Stock
            </button>
        @endif
    </div>
</div>

<script>
// Enhanced Add to Cart function with better UX
async function addToCart(productId, variantId = null, quantity = 1) {
    const button = event.target;
    const originalText = button.textContent;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<div class="flex items-center justify-center"><div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>Adding...</div>';
    
    try {
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId,
                variant_combination_id: variantId,
                quantity: quantity
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Success state
            button.textContent = 'Added!';
            button.classList.remove('bg-pink-600', 'hover:bg-pink-700');
            button.classList.add('bg-green-600');
            
            // Update cart count in header
            updateCartCounter(data.cart_count);
            
            // Dispatch cart updated event
            window.dispatchEvent(new CustomEvent('cart-updated'));
            
            // Show success message
            showToast(data.message, 'success');
            
            // Reset button after 2 seconds
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-pink-600', 'hover:bg-pink-700');
                button.disabled = false;
            }, 2000);
            
        } else {
            // Error state
            button.textContent = 'Error';
            button.classList.remove('bg-pink-600');
            button.classList.add('bg-red-600');
            
            showToast(data.message, 'error');
            
            // Reset button after 2 seconds
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-red-600');
                button.classList.add('bg-pink-600', 'hover:bg-pink-700');
                button.disabled = false;
            }, 2000);
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        
        // Error state
        button.textContent = 'Error';
        button.classList.remove('bg-pink-600');
        button.classList.add('bg-red-600');
        
        showToast('Error adding item to cart. Please try again.', 'error');
        
        // Reset button after 2 seconds
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-red-600');
            button.classList.add('bg-pink-600', 'hover:bg-pink-700');
            button.disabled = false;
        }, 2000);
    }
}


// Quick view functionality
function quickView(productId) {
    // For now, redirect to product page
    // Later you can implement a modal
    window.location.href = `/products/${productId}`;
}



</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.toast-notification {
    transform: translateY(100%);
    opacity: 0;
}

.add-to-cart-btn:disabled {
    cursor: not-allowed;
    opacity: 0.7;
}
</style>