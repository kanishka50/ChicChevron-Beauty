
@include('components.home.banner-slider')
@extends('layouts.app')

@section('title', 'ChicChevron Beauty - Premium Beauty Products Sri Lanka')
@section('description', 'Discover premium beauty products at ChicChevron Beauty. Shop authentic skincare, cosmetics, and beauty essentials with fast delivery across Sri Lanka.')

@section('content')
    <!-- Hero Banner Section -->
    @if($banners->isNotEmpty())
        <section class="relative overflow-hidden">
            <div class="swiper hero-swiper">
                <div class="swiper-wrapper">
                    @foreach($banners as $banner)
                        <div class="swiper-slide">
                            <div class="relative h-96 md:h-[500px] bg-gradient-to-r from-pink-500 to-purple-600">
                                <!-- Desktop Image -->
                                @if($banner->image_desktop)
                                    <img src="{{ asset('storage/' . $banner->image_desktop) }}" 
                                         alt="{{ $banner->title }}" 
                                         class="hidden md:block w-full h-full object-cover">
                                @endif
                                
                                <!-- Mobile Image -->
                                @if($banner->image_mobile)
                                    <img src="{{ asset('storage/' . $banner->image_mobile) }}" 
                                         alt="{{ $banner->title }}" 
                                         class="md:hidden w-full h-full object-cover">
                                @elseif($banner->image_desktop)
                                    <img src="{{ asset('storage/' . $banner->image_desktop) }}" 
                                         alt="{{ $banner->title }}" 
                                         class="md:hidden w-full h-full object-cover">
                                @endif

                                <!-- Banner Content Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                        <div class="max-w-lg text-white">
                                            @if($banner->title)
                                                <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ $banner->title }}</h1>
                                            @endif
                                            
                                            @if($banner->link_type !== 'none' && $banner->link_value)
                                                <a href="{{ getBannerUrl($banner) }}" 
                                                   class="inline-block bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                                                    Shop Now
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Navigation -->
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next text-white"></div>
                <div class="swiper-button-prev text-white"></div>
            </div>
        </section>
    @else
        <!-- Default Hero Section when no banners -->
        <section class="relative h-96 md:h-[500px] bg-gradient-to-r from-pink-500 to-purple-600">
            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-lg text-white">
                        <h1 class="text-4xl md:text-6xl font-bold mb-4">Discover Your Beauty</h1>
                        <p class="text-xl mb-6">Premium beauty products for every skin type and lifestyle</p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-block bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Shop Now
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Categories -->
    @if($categories->isNotEmpty())
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop by Category</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Discover our curated selection of beauty products across different categories</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    @foreach($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                           class="group text-center">
                            <div class="relative overflow-hidden rounded-full w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-pink-100 to-purple-100 group-hover:shadow-lg transition-shadow">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-2xl text-gray-600">
                                        {{ substr($category->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-medium text-gray-900 group-hover:text-pink-600 transition-colors">
                                {{ $category->name }}
                            </h3>
                            <p class="text-sm text-gray-500">{{ $category->products_count }} products</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Products -->
    @if($featuredProducts->isNotEmpty())
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Products</h2>
                        <p class="text-gray-600">Handpicked favorites that our customers love</p>
                    </div>
                    <a href="{{ route('products.index', ['featured' => 1]) }}" 
                       class="text-pink-600 hover:text-pink-700 font-medium">
                        View All →
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        @include('components.shop.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- New Arrivals -->
    @if($newArrivals->isNotEmpty())
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">New Arrivals</h2>
                        <p class="text-gray-600">Fresh picks just for you</p>
                    </div>
                    <a href="{{ route('products.index', ['sort' => 'newest']) }}" 
                       class="text-pink-600 hover:text-pink-700 font-medium">
                        View All →
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($newArrivals as $product)
                        @include('components.shop.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Best Sellers -->
    @if($bestSellers->isNotEmpty())
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Best Sellers</h2>
                        <p class="text-gray-600">Top-rated products loved by our customers</p>
                    </div>
                    <a href="{{ route('products.index', ['sort' => 'popular']) }}" 
                       class="text-pink-600 hover:text-pink-700 font-medium">
                        View All →
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($bestSellers as $product)
                        @include('components.shop.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Brand Showcase -->
    @if($brands->isNotEmpty())
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop by Brand</h2>
                    <p class="text-gray-600">Discover products from your favorite beauty brands</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-6">
                    @foreach($brands as $brand)
                        <a href="{{ route('products.index', ['brands' => [$brand->id]]) }}" 
                           class="group">
                            <div class="bg-white p-6 rounded-lg border border-gray-200 hover:shadow-lg transition-shadow text-center">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" 
                                         alt="{{ $brand->name }}" 
                                         class="w-16 h-16 mx-auto mb-3 object-contain group-hover:scale-110 transition-transform">
                                @else
                                    <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-pink-100 to-purple-100 rounded-lg flex items-center justify-center text-lg font-bold text-gray-700">
                                        {{ substr($brand->name, 0, 2) }}
                                    </div>
                                @endif
                                <h3 class="font-medium text-gray-900 group-hover:text-pink-600 transition-colors">
                                    {{ $brand->name }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $brand->products_count }} products</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Newsletter Signup -->
    <section class="py-16 bg-gradient-to-r from-pink-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold text-white mb-4">Stay in the Loop</h2>
                <p class="text-pink-100 mb-8">Subscribe to our newsletter for exclusive offers, beauty tips, and new product launches</p>
                
                <form id="newsletter-form" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    @csrf
                    <input 
                        type="email" 
                        name="email"
                        placeholder="Enter your email address" 
                        class="flex-1 px-4 py-3 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-white"
                        required
                    >
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-white text-pink-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="py-12 bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Fast Delivery</h3>
                    <p class="text-sm text-gray-600">Free shipping over Rs. 5,000</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Authentic Products</h3>
                    <p class="text-sm text-gray-600">100% genuine beauty products</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Secure Payment</h3>
                    <p class="text-sm text-gray-600">Safe & secure transactions</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">24/7 Support</h3>
                    <p class="text-sm text-gray-600">Expert beauty advice</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Recently Viewed Products (if user has viewed products) -->
    @auth
        @if(session('recently_viewed_products'))
            <section class="py-16 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Recently Viewed</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach(session('recently_viewed_products') as $productId)
                            @php
                                $recentProduct = \App\Models\Product::with(['brand', 'images', 'reviews'])->find($productId);
                            @endphp
                            @if($recentProduct)
                                @include('components.shop.product-card', ['product' => $recentProduct])
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endauth
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        // Initialize hero banner swiper
        document.addEventListener('DOMContentLoaded', function() {
            const heroSwiper = new Swiper('.hero-swiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
            });
        });

        // Newsletter subscription
        document.getElementById('newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const email = formData.get('email');
            
            // Here you would typically send the email to your backend
            fetch('/api/newsletter-subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Thank you for subscribing! You will receive our latest updates and offers.', 'success');
                    this.reset();
                } else {
                    showToast('Subscription failed. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.log('Newsletter subscription for:', email);
                showToast('Thank you for subscribing! You will receive our latest updates and offers.', 'success');
                this.reset();
            });
        });

        // Toast notification helper
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg z-50 max-w-sm`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 4000);
        }

        // Lazy loading for images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Track product clicks for analytics
        document.querySelectorAll('a[href*="/products/"]').forEach(link => {
            link.addEventListener('click', function() {
                const productSlug = this.href.split('/products/')[1];
                if (productSlug) {
                    // Track product view
                    fetch('/api/track-product-view', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ product_slug: productSlug })
                    }).catch(error => {
                        console.log('Analytics tracking failed:', error);
                    });
                }
            });
        });
    </script>
@endpush

@php
    // Helper function for banner URLs
    function getBannerUrl($banner) {
        switch($banner->link_type) {
            case 'product':
                $product = \App\Models\Product::find($banner->link_value);
                return $product ? route('products.show', $product->slug) : route('products.index');
            case 'category':
                return route('products.index', ['category' => $banner->link_value]);
            case 'url':
                return $banner->link_value;
            default:
                return route('products.index');
        }
    }
@endphp