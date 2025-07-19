@extends('layouts.app')

@section('title', 'My Wishlist - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <div class="container-responsive py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('user.account.index') }}" class="touch-target">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">My Wishlist</h1>
            <div class="w-10"></div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden lg:flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Wishlist</h1>
                <p class="mt-1 text-gray-600">{{ $wishlistItems->count() }} {{ Str::plural('item', $wishlistItems->count()) }} saved</p>
            </div>
            @if($wishlistItems->isNotEmpty())
                <button onclick="clearWishlist()" 
                        class="btn btn-outline group flex items-center">
                    <svg class="w-5 h-5 mr-2 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Clear All
                </button>
            @endif
        </div>

        @if($wishlistItems->isNotEmpty())
            <!-- Mobile Clear All Button -->
            <div class="lg:hidden mb-6 text-center">
                <button onclick="clearWishlist()" 
                        class="text-red-600 hover:text-red-700 text-sm font-medium">
                    Clear All Items
                </button>
            </div>

            <!-- Wishlist Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                @foreach($wishlistItems as $index => $item)
                    <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden"
                         style="animation: fadeInUp 0.5s ease-out {{ $index * 0.1 }}s backwards;">
                        
                        <!-- Product Image -->
                        <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="block h-full">
                                <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : '/placeholder.jpg' }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                     loading="lazy">
                            </a>
                            
                            <!-- Discount Badge -->
                            @if($item->product->discount_price && $item->product->discount_price < $item->product->selling_price)
                                @php
                                    $discountPercentage = round((($item->product->selling_price - $item->product->discount_price) / $item->product->selling_price) * 100);
                                @endphp
                                <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                                    -{{ $discountPercentage }}%
                                </div>
                            @endif

                            <!-- Quick Actions -->
                            <div class="absolute top-2 right-2 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <!-- Remove Button -->
                                <button onclick="removeFromWishlist({{ $item->product->id }})" 
                                        class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 flex items-center justify-center group/remove"
                                        title="Remove from wishlist">
                                    <svg class="w-5 h-5 group-hover/remove:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                
                                <!-- Quick View -->
                                <a href="{{ route('products.show', $item->product->slug) }}" 
                                   class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 flex items-center justify-center group/view"
                                   title="View product">
                                    <svg class="w-5 h-5 group-hover/view:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>

                            <!-- Out of Stock Overlay -->
                            @if(!$item->product->hasStock())
                                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center">
                                    <span class="bg-white/90 text-gray-900 px-3 py-1 rounded-full text-sm font-medium">
                                        Out of Stock
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <!-- Brand -->
                            @if($item->product->brand)
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">{{ $item->product->brand->name }}</p>
                            @endif

                            <!-- Product Name -->
                            <h3 class="mt-1 text-sm font-medium text-gray-900 line-clamp-2 min-h-[2.5rem]">
                                <a href="{{ route('products.show', $item->product->slug) }}" 
                                   class="hover:text-primary-600 transition-colors">
                                    {{ $item->product->name }}
                                </a>
                            </h3>

                            <!-- Rating -->
                            <div class="mt-2 flex items-center space-x-1">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $item->product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500">({{ $item->product->reviews_count }})</span>
                            </div>

                            <!-- Price -->
                            <div class="mt-3 flex items-center space-x-2">
                                @if($item->product->discount_price && $item->product->discount_price < $item->product->selling_price)
                                    <span class="text-lg font-bold text-primary-600">Rs {{ number_format($item->product->discount_price, 0) }}</span>
                                    <span class="text-sm text-gray-500 line-through">Rs {{ number_format($item->product->selling_price, 0) }}</span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">Rs {{ number_format($item->product->selling_price, 0) }}</span>
                                @endif
                            </div>

                            <!-- Add to Cart Button -->
                            <div class="mt-4">
                                @if($item->product->hasStock())
                                    <button onclick="addToCart({{ $item->product->id }})" 
                                            class="w-full btn btn-primary btn-sm group">
                                        <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Add to Cart
                                    </button>
                                @else
                                    <button disabled 
                                            class="w-full btn btn-secondary btn-sm opacity-50 cursor-not-allowed">
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Mobile Load More (if needed) -->
            @if($wishlistItems->count() > 10)
                <div class="mt-8 text-center lg:hidden">
                    <button class="btn btn-outline">
                        Load More Items
                    </button>
                </div>
            @endif
        @else
            <!-- Empty Wishlist State -->
            <div class="bg-white rounded-2xl shadow-sm p-8 sm:p-12 text-center max-w-2xl mx-auto">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-pink-100 to-pink-200 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">Save your favorite items here to keep track of them and get notified about price drops!</p>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('products.index') }}" 
                       class="btn btn-primary inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Browse Products
                    </a>
                    <a href="{{ route('home') }}" 
                       class="btn btn-outline inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Go Home
                    </a>
                </div>

                <!-- Features -->
                <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Price Alerts</h4>
                            <p class="text-xs text-gray-600 mt-0.5">Get notified when prices drop</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Save for Later</h4>
                            <p class="text-xs text-gray-600 mt-0.5">Keep track of items you love</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m9.032 4.026a9 9 0 10-7.432 0m7.432 0A9 9 0 1015.284 5.716a9 9 0 00-7.432 0m7.432 0a3 3 0 11-4.318-4.318A3 3 0 0112 3a3 3 0 013.284 2.716A9 9 0 0121 12a9 9 0 01-5.716 8.284z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Share Lists</h4>
                            <p class="text-xs text-gray-600 mt-0.5">Share your wishlist with friends</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .toast-notification {
        animation: slideInRight 0.3s ease-out;
    }
</style>
@endpush

@push('scripts')
<script>
// Toast notification function
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
    
    toast.className = `toast-notification flex items-center space-x-3 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg max-w-md`;
    toast.innerHTML = `
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${icon}
        </svg>
        <span class="text-sm font-medium">${message}</span>
    `;
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100px)';
        toast.style.transition = 'all 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Remove from wishlist
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
            showToast('Item removed from wishlist');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error removing item', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Something went wrong. Please try again.', 'error');
    }
}

// Clear wishlist
async function clearWishlist() {
    if (!confirm('Are you sure you want to clear your entire wishlist? This action cannot be undone.')) {
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
            showToast('Wishlist cleared successfully');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error clearing wishlist', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Something went wrong. Please try again.', 'error');
    }
}

// Add to cart
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
            showToast('Product added to cart!');
            // Update cart count if you have a cart counter in header
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
        } else {
            showToast(data.message || 'Error adding to cart', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Something went wrong. Please try again.', 'error');
    }
}

// Lazy load images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
});
</script>
@endpush
@endsection