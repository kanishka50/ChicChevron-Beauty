@extends('layouts.app')

@section('title', 'My Wishlist - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Wishlist</h1>
            @if($wishlistItems->isNotEmpty())
                <button onclick="clearWishlist()" 
                        class="text-red-600 hover:text-red-700 text-sm font-medium">
                    Clear All
                </button>
            @endif
        </div>

        @if($wishlistItems->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlistItems as $item)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden group">
                        <!-- Product Image -->
                        <div class="aspect-square bg-gray-100 relative">
                            <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : '/placeholder.jpg' }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Remove from Wishlist Button -->
                            <button onclick="removeFromWishlist({{ $item->product->id }})" 
                                    class="absolute top-3 right-3 bg-white rounded-full p-2 shadow-md hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <!-- Brand -->
                            @if($item->product->brand)
                                <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $item->product->brand->name }}</p>
                            @endif

                            <!-- Product Name -->
                            <h3 class="mt-1 text-sm font-medium text-gray-900 line-clamp-2">
                                <a href="{{ route('products.show', $item->product->slug) }}" 
                                   class="hover:text-pink-600 transition-colors">
                                    {{ $item->product->name }}
                                </a>
                            </h3>

                            <!-- Price -->
                            <div class="mt-2 flex items-center space-x-2">
                                @if($item->product->discount_price && $item->product->discount_price < $item->product->selling_price)
                                    <span class="text-lg font-bold text-pink-600">Rs. {{ number_format($item->product->discount_price, 2) }}</span>
                                    <span class="text-sm text-gray-500 line-through">Rs. {{ number_format($item->product->selling_price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">Rs. {{ number_format($item->product->selling_price, 2) }}</span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-3 flex space-x-2">
                                @if($item->product->hasStock())
                                    <button onclick="addToCart({{ $item->product->id }})" 
                                            class="flex-1 bg-pink-600 text-white py-2 px-3 rounded-md hover:bg-pink-700 text-sm font-medium transition-colors">
                                        Add to Cart
                                    </button>
                                @else
                                    <button disabled 
                                            class="flex-1 bg-gray-300 text-gray-500 py-2 px-3 rounded-md text-sm font-medium cursor-not-allowed">
                                        Out of Stock
                                    </button>
                                @endif
                                
                                <a href="{{ route('products.show', $item->product->slug) }}" 
                                   class="bg-gray-200 text-gray-800 py-2 px-3 rounded-md hover:bg-gray-300 text-sm font-medium transition-colors">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty Wishlist -->
            <div class="text-center py-16">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Your wishlist is empty</h3>
                <p class="mt-2 text-gray-500">Start adding products you love to keep track of them!</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                        Browse Products
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
async function removeFromWishlist(productId) {
    try {
        const response = await fetch('/wishlist/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Remove item from DOM
            location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error removing from wishlist:', error);
        alert('Error removing from wishlist. Please try again.');
    }
}

async function clearWishlist() {
    if (!confirm('Are you sure you want to clear your entire wishlist?')) {
        return;
    }

    try {
        const response = await fetch('/wishlist/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error clearing wishlist:', error);
        alert('Error clearing wishlist. Please try again.');
    }
}

async function addToCart(productId) {
    try {
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });

        const data = await response.json();
        
        if (data.success) {
            alert('Product added to cart!');
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        alert('Error adding to cart. Please try again.');
    }
}
</script>
@endsection