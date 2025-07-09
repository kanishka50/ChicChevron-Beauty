@props(['product'])

<div class="group relative bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
    <!-- Product Image -->
    <div class="relative aspect-square overflow-hidden bg-gray-100">
        <a href="{{ route('products.show', $product->slug) }}" class="block">
            @if($product->main_image)
                <img src="{{ asset('storage/' . $product->main_image) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-100 to-purple-100">
                    <span class="text-gray-400 text-4xl">📷</span>
                </div>
            @endif
        </a>

        <!-- Badges -->
        <div class="absolute top-2 left-2 flex flex-col gap-1">
            @if($product->discount_price && $product->discount_price < $product->selling_price)
                @php
                    $discountPercent = round((($product->selling_price - $product->discount_price) / $product->selling_price) * 100);
                @endphp
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                    -{{ $discountPercent }}%
                </span>
            @endif

            @if($product->created_at->isAfter(now()->subDays(30)))
                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">
                    New
                </span>
            @endif

            @if($product->order_items_count > 0)
                <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                    Bestseller
                </span>
            @endif
        </div>

        <!-- Stock Status -->
        <div class="absolute top-2 right-2">
            @if($product->getStockLevel() <= 0)
                <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">
                    Out of Stock
                </span>
            @elseif($product->getStockLevel() <= 5)
                <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded">
                    Low Stock
                </span>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="absolute bottom-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <!-- Add to Wishlist -->
            <button 
                onclick="addToWishlist({{ $product->id }})" 
                class="p-2 bg-white rounded-full shadow-md hover:bg-pink-50 transition-colors"
                title="Add to Wishlist"
            >
                <svg class="w-4 h-4 text-gray-600 hover:text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>

            <!-- Quick View -->
            <button 
                onclick="quickView({{ $product->id }})" 
                class="p-2 bg-white rounded-full shadow-md hover:bg-pink-50 transition-colors"
                title="Quick View"
            >
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Product Details -->
    <div class="p-4">
        <!-- Brand -->
        @if($product->brand)
            <p class="text-xs text-gray-500 mb-1">{{ $product->brand->name }}</p>
        @endif

        <!-- Product Name -->
        <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-pink-600 transition-colors">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Rating -->
        @if($product->reviews_count > 0)
            <div class="flex items-center gap-1 mb-2">
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3 h-3 {{ $i <= round($product->reviews_avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                </div>
                <span class="text-xs text-gray-500">({{ $product->reviews_count }})</span>
            </div>
        @endif

        <!-- Price -->
        <div class="mb-3">
            @if($product->has_variants && $product->variantCombinations->isNotEmpty())
                @php
                    $minPrice = $product->variantCombinations->min('combination_price');
                    $maxPrice = $product->variantCombinations->max('combination_price');
                @endphp
                @if($minPrice != $maxPrice)
                    <span class="text-lg font-bold text-gray-900">
                        Rs. {{ number_format($minPrice, 2) }} - Rs. {{ number_format($maxPrice, 2) }}
                    </span>
                @else
                    <span class="text-lg font-bold text-gray-900">Rs. {{ number_format($minPrice, 2) }}</span>
                @endif
            @else
                <div class="flex items-center gap-2">
                    @if($product->discount_price && $product->discount_price < $product->selling_price)
                        <span class="text-lg font-bold text-gray-900">Rs. {{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-sm text-gray-500 line-through">Rs. {{ number_format($product->selling_price, 2) }}</span>
                    @else
                        <span class="text-lg font-bold text-gray-900">Rs. {{ number_format($product->selling_price, 2) }}</span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Variant Preview -->
        @if($product->has_variants)
            <div class="mb-3">
                <!-- Color Variants -->
                @if($product->colors->isNotEmpty())
                    <div class="flex items-center gap-1 mb-2">
                        <span class="text-xs text-gray-500 mr-1">Colors:</span>
                        @foreach($product->colors->take(5) as $color)
                            <div class="w-4 h-4 rounded-full border border-gray-300" 
                                 style="background-color: {{ $color->color_code }}"
                                 title="{{ $color->name }}">
                            </div>
                        @endforeach
                        @if($product->colors->count() > 5)
                            <span class="text-xs text-gray-500">+{{ $product->colors->count() - 5 }}</span>
                        @endif
                    </div>
                @endif

                <!-- Size Variants -->
                @if($product->variants->where('variant_type', 'size')->isNotEmpty())
                    <div class="flex items-center gap-1">
                        <span class="text-xs text-gray-500 mr-1">Sizes:</span>
                        @foreach($product->variants->where('variant_type', 'size')->take(3) as $size)
                            <span class="text-xs px-1 py-0.5 bg-gray-100 rounded">{{ $size->variant_value }}</span>
                        @endforeach
                        @if($product->variants->where('variant_type', 'size')->count() > 3)
                            <span class="text-xs text-gray-500">+more</span>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <!-- Add to Cart Button -->
        @if($product->getStockLevel() > 0)
            @if($product->has_variants)
                <a href="{{ route('products.show', $product->slug) }}" 
                   class="w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition-colors text-center text-sm font-medium block">
                    Select Options
                </a>
            @else
                <button 
                    onclick="addToCart({{ $product->id }}, null, 1)" 
                    class="w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition-colors text-sm font-medium">
                    Add to Cart
                </button>
            @endif
        @else
            <button disabled class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg text-sm font-medium cursor-not-allowed">
                Out of Stock
            </button>
        @endif
    </div>
</div>

<script>
    // Add to cart functionality
    function addToCart(productId, variantId = null, quantity = 1) {
        // This will be implemented when cart functionality is added
        console.log('Adding to cart:', { productId, variantId, quantity });
        
        // Show temporary feedback
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Added!';
        button.classList.add('bg-green-600');
        button.classList.remove('bg-pink-600');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-pink-600');
        }, 1500);
    }

    // Add to wishlist functionality
    function addToWishlist(productId) {
        // This will be implemented when wishlist functionality is added
        console.log('Adding to wishlist:', productId);
        
        // Show temporary feedback
        const button = event.target.closest('button');
        const svg = button.querySelector('svg');
        svg.classList.add('text-pink-600', 'fill-current');
        
        // You might want to show a toast notification here
        showToast('Added to wishlist!');
    }

    // Quick view functionality
    function quickView(productId) {
        // This will be implemented later for quick view modal
        console.log('Quick view:', productId);
        
        // For now, redirect to product page
        window.location.href = `/products/${productId}`;
    }

    // Toast notification helper
    function showToast(message) {
        // Simple toast implementation
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
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