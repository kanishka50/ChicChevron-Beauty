<!-- Enhanced Product Card Component -->
<div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group relative overflow-hidden">
    <!-- Sale/New Badge -->
    @if($product->is_new)
        <div class="absolute top-3 left-3 z-10 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full">
            NEW
        </div>
    @elseif($product->discount_price && $product->discount_price < $product->selling_price)
        @php
            $discountPercent = round((($product->selling_price - $product->discount_price) / $product->selling_price) * 100);
        @endphp
        <div class="absolute top-3 left-3 z-10 bg-gradient-to-r from-red-500 to-rose-600 text-white text-xs font-bold px-3 py-1 rounded-full">
            -{{ $discountPercent }}%
        </div>
    @endif

    <!-- Product Image -->
    <div class="relative aspect-square overflow-hidden bg-gray-50">
        <a href="{{ route('products.show', $product->slug) }}" class="block">
            <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : '/placeholder.jpg' }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        </a>
        
        <!-- Overlay on hover -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        
        <!-- Action Buttons -->
        <div class="absolute top-3 right-3 flex flex-col gap-2">
            <!-- Wishlist Button -->
            @php
                $isInWishlist = $product->isInWishlist();
            @endphp
            <button type="button"
                    onclick="toggleWishlist({{ $product->id }})" 
                    class="p-2.5 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition-all duration-200 transform hover:scale-110 wishlist-btn"
                    data-product-id="{{ $product->id }}">
                <svg class="w-5 h-5 {{ $isInWishlist ? 'text-primary-600 fill-current' : 'text-gray-600' }}" 
                     fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>

            <!-- Quick View Button -->
            <button onclick="quickView({{ $product->id }})" 
                    class="p-2.5 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition-all duration-200 transform hover:scale-110 opacity-0 group-hover:opacity-100">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>

        <!-- Stock Status Overlay -->
        @if(!$product->hasStock())
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center">
                <span class="bg-white text-gray-900 px-4 py-2 rounded-full text-sm font-semibold">Out of Stock</span>
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-4 md:p-5">
        <!-- Brand -->
        @if($product->brand)
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">{{ $product->brand->name }}</p>
        @endif

        <!-- Product Name -->
        <h3 class="mt-1 font-medium text-gray-900 line-clamp-2 group-hover:text-primary-600 transition-colors">
            <a href="{{ route('products.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Rating -->
        @if($product->reviews_count > 0)
            <div class="mt-2 flex items-center gap-1">
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $product->average_rating ? 'fill-current' : 'fill-gray-200' }}" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-xs text-gray-500">({{ $product->reviews_count }})</span>
            </div>
        @endif

        <!-- Price -->
        <div class="mt-3 flex items-baseline gap-2">
            @if($product->has_discount)
                <span class="text-lg font-bold text-primary-600">
                    Rs. {{ number_format($product->starting_discount_price ?? $product->starting_price, 2) }}
                </span>
                @if($product->starting_discount_price)
                    <span class="text-sm text-gray-500 line-through">
                        Rs. {{ number_format($product->starting_price, 2) }}
                    </span>
                @endif
            @else
                <span class="text-lg font-bold text-gray-900">
                    Rs. {{ number_format($product->starting_price, 2) }}
                </span>
            @endif
        </div>

        <!-- Variants Preview -->
        @if($product->has_variants && $product->variants->isNotEmpty())
            <div class="mt-3 space-y-2">
                @php
                    $colorVariants = $product->variants->where('variant_type', 'color')->take(5);
                    $sizeVariants = $product->variants->where('variant_type', 'size')->take(3);
                @endphp

                @if($colorVariants->isNotEmpty())
                    <div class="flex items-center gap-1">
                        @foreach($colorVariants as $variant)
                            @php
                                $color = $variant->color ?? null;
                                $colorCode = $color ? $color->color_code : '#ccc';
                            @endphp
                            <div class="w-6 h-6 rounded-full border-2 border-white shadow-md transition-transform hover:scale-110" 
                                 style="background-color: {{ $colorCode }}"
                                 title="{{ $variant->variant_value }}"></div>
                        @endforeach
                        @if($product->variants->where('variant_type', 'color')->count() > 5)
                            <span class="text-xs text-gray-500 ml-1">+{{ $product->variants->where('variant_type', 'color')->count() - 5 }}</span>
                        @endif
                    </div>
                @endif

                @if($sizeVariants->isNotEmpty())
                    <div class="flex items-center gap-1 flex-wrap">
                        @foreach($sizeVariants as $variant)
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-md">{{ $variant->variant_value }}</span>
                        @endforeach
                        @if($product->variants->where('variant_type', 'size')->count() > 3)
                            <span class="text-xs text-gray-500">+{{ $product->variants->where('variant_type', 'size')->count() - 3 }}</span>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <!-- Add to Cart Button -->
        <div class="mt-4">
            @if($product->hasStock())
                @if($product->has_variants)
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-700 py-2.5 px-4 rounded-xl hover:bg-gray-200 transition-all font-medium group">
                        <span>Select Options</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <button onclick="addToCart({{ $product->id }}, null, 1)" 
                            class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white py-2.5 px-4 rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all font-medium add-to-cart-btn group shadow-md hover:shadow-lg"
                            data-product-id="{{ $product->id }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Add to Cart</span>
                    </button>
                @endif
            @else
                <button disabled 
                        class="w-full bg-gray-200 text-gray-400 py-2.5 px-4 rounded-xl font-medium cursor-not-allowed">
                    Out of Stock
                </button>
            @endif
        </div>
    </div>
</div>

<script>
// Enhanced Add to Cart function
async function addToCart(productId, variantId = null, quantity = 1) {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    `;
    
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
            // Success animation
            button.classList.remove('from-primary-600', 'to-primary-700', 'hover:from-primary-700', 'hover:to-primary-800');
            button.classList.add('from-green-600', 'to-green-700');
            button.innerHTML = `
                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
            
            // Update cart
            window.dispatchEvent(new CustomEvent('cart-updated'));
            window.showToast(data.message, 'success');
            
            // Reset button after delay
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.classList.remove('from-green-600', 'to-green-700');
                button.classList.add('from-primary-600', 'to-primary-700', 'hover:from-primary-700', 'hover:to-primary-800');
                button.disabled = false;
            }, 2000);
            
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        // Error state
        button.classList.remove('from-primary-600', 'to-primary-700');
        button.classList.add('from-red-600', 'to-red-700');
        button.innerHTML = `
            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        `;
        
        window.showToast(error.message || 'Error adding to cart', 'error');
        
        // Reset button
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('from-red-600', 'to-red-700');
            button.classList.add('from-primary-600', 'to-primary-700', 'hover:from-primary-700', 'hover:to-primary-800');
            button.disabled = false;
        }, 2000);
    }
}

// Quick view functionality
function quickView(productId) {
    // For now, redirect to product page
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
</style>