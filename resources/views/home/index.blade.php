@extends('layouts.app')

@section('title', 'ChicChevron Beauty - Premium Beauty Products Sri Lanka')
@section('description', 'Discover premium beauty products at ChicChevron Beauty. Shop authentic skincare, cosmetics, and beauty essentials with fast delivery across Sri Lanka.')

@section('content')
    
    <!-- Hero Banner Section -->
    @include('home.banner-slider')

    <!-- Featured Categories -->
    @if($categories->isNotEmpty())
    <section class="py-12 md:py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="container-responsive">
            <div class="text-center mb-8 md:mb-12">
                <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-3">
                    Shop by Category
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base">
                    Discover our curated selection of beauty products across different categories
                </p>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4 md:gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                       class="group text-center">
                        <!-- Modern Category Card Container -->
                        <div class="relative mb-3 transition-all duration-300 transform group-hover:-translate-y-2">
                            <!-- Background Shape with Gradient -->
                            <div class="relative w-20 h-20 md:w-24 md:h-24 mx-auto">
                                <!-- Animated Background Circle -->
                                <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl opacity-0 group-hover:opacity-100 transform group-hover:scale-110 transition-all duration-300"></div>
                                
                                <!-- Main Container -->
                                <div class="relative w-full h-full bg-white rounded-2xl shadow-md group-hover:shadow-xl transition-all duration-300 overflow-hidden">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" 
                                             alt="{{ $category->name }}" 
                                             class="w-full h-full object-contain p-3 group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <!-- Improved Default Icon -->
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 group-hover:from-primary-50 group-hover:to-primary-100 transition-all duration-300">
                                            <span class="text-2xl md:text-3xl font-bold text-gray-400 group-hover:text-primary-600 transition-colors duration-300">
                                                {{ substr($category->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Floating Badge for Item Count -->
                                @if($category->products_count > 0)
                                    <div class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs w-6 h-6 rounded-full flex items-center justify-center font-bold shadow-md transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                        {{ $category->products_count > 99 ? '99+' : $category->products_count }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Category Name -->
                        <h3 class="font-medium text-gray-800 group-hover:text-primary-600 transition-colors text-sm md:text-base">
                            {{ $category->name }}
                        </h3>
                        
                        <!-- Subtle Item Count (Alternative to floating badge) -->
                        <p class="text-xs text-gray-500 mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            {{ $category->products_count }} {{ Str::plural('item', $category->products_count) }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

    <!-- Featured Products -->
    @if($featuredProducts->isNotEmpty())
        <section class="py-12 md:py-20 bg-white">
            <div class="container-responsive">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 md:mb-12">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2">Featured Products</h2>
                        <p class="text-gray-600 text-sm md:text-base">Handpicked favorites that our customers love</p>
                    </div>
                    <a href="{{ route('products.index', ['featured' => 1]) }}" 
                       class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium group">
                        <span>View All</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach($featuredProducts as $product)
                        @include('components.shop.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Special Offer Banner -->
    <section class="py-8 md:py-12 bg-gradient-to-r from-primary-600 via-primary-700 to-primary-800">
        <div class="container-responsive">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <h3 class="text-xl md:text-2xl font-bold text-white mb-2">Limited Time Offer!</h3>
                    <p class="text-primary-100 text-sm md:text-base">Get 20% off on selected beauty products</p>
                </div>
                <a href="{{ route('products.index', ['sale' => 1]) }}" 
                   class="inline-flex items-center gap-2 bg-white text-primary-700 px-6 py-3 rounded-full font-semibold hover:bg-primary-50 transition-all duration-300 hover:shadow-xl group">
                    <span>Shop Sale</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- New Arrivals -->
    @if($newArrivals->isNotEmpty())
        <section class="py-12 md:py-20 bg-gray-50">
            <div class="container-responsive">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 md:mb-12">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2">New Arrivals</h2>
                        <p class="text-gray-600 text-sm md:text-base">Fresh picks just for you</p>
                    </div>
                    <a href="{{ route('products.index', ['sort' => 'newest']) }}" 
                       class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium group">
                        <span>View All</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="relative">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                        @foreach($newArrivals as $product)
                            @include('components.shop.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    <!-- New Badge -->
                    <div class="absolute -top-4 -right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg transform rotate-12 hidden md:block">
                        NEW
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Best Sellers -->
    @if($bestSellers->isNotEmpty())
        <section class="py-12 md:py-20 bg-white">
            <div class="container-responsive">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 md:mb-12">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2">Best Sellers</h2>
                        <p class="text-gray-600 text-sm md:text-base">Top-rated products loved by our customers</p>
                    </div>
                    <a href="{{ route('products.index', ['sort' => 'popular']) }}" 
                       class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium group">
                        <span>View All</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach($bestSellers as $product)
                        @include('components.shop.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Brand Showcase -->
    @if($brands->isNotEmpty())
        <section class="py-12 md:py-20 bg-gradient-to-b from-gray-50 to-white overflow-hidden">
            <div class="container-responsive">
                <div class="text-center mb-8 md:mb-12">
                    <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-3">Shop by Brand</h2>
                    <p class="text-gray-600 text-sm md:text-base">Discover products from your favorite beauty brands</p>
                </div>

                <div class="relative">
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3 md:gap-4">
                        @foreach($brands as $brand)
                            <a href="{{ route('products.index', ['brands' => [$brand->id]]) }}" 
                               class="group transform hover:scale-105 transition-all duration-300">
                                <div class="bg-white p-4 md:p-6 rounded-2xl border-2 border-gray-100 hover:border-primary-200 hover:shadow-xl transition-all duration-300 text-center">
                                    @if($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" 
                                             alt="{{ $brand->name }}" 
                                             class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-2 object-contain group-hover:scale-110 transition-transform">
                                    @else
                                        <div class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-2 bg-gradient-to-br from-primary-100 to-primary-200 rounded-xl flex items-center justify-center text-sm md:text-lg font-bold text-primary-700 group-hover:from-primary-200 group-hover:to-primary-300 transition-all duration-300">
                                            {{ substr($brand->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <h3 class="font-medium text-gray-900 group-hover:text-primary-600 transition-colors text-xs md:text-sm truncate">
                                        {{ $brand->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $brand->products_count }} items</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Newsletter Signup -->
    <section class="py-12 md:py-20 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white rounded-full"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white rounded-full"></div>
        </div>
        
        <div class="container-responsive relative z-10">
            <div class="max-w-2xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl md:text-4xl font-bold text-white mb-4">Stay in the Loop</h2>
                <p class="text-primary-100 mb-8 text-sm md:text-base">Subscribe to our newsletter for exclusive offers, beauty tips, and new product launches</p>
                
                <form id="newsletter-form" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    @csrf
                    <input 
                        type="email" 
                        name="email"
                        placeholder="Enter your email address" 
                        class="flex-1 px-5 py-3 rounded-full border-0 focus:outline-none focus:ring-4 focus:ring-white/30 text-gray-900 text-sm md:text-base"
                        required
                    >
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-white text-primary-700 font-semibold rounded-full hover:bg-primary-50 transition-all duration-300 hover:shadow-xl transform hover:scale-105"
                    >
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="py-12 md:py-16 bg-white border-t border-gray-100">
        <div class="container-responsive">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <div class="group text-center">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl flex items-center justify-center mb-3 mx-auto group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1 text-sm md:text-base">Fast Delivery</h3>
                    <p class="text-xs md:text-sm text-gray-600">Free shipping over Rs. 5,000</p>
                </div>
                
                <div class="group text-center">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl flex items-center justify-center mb-3 mx-auto group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1 text-sm md:text-base">Authentic Products</h3>
                    <p class="text-xs md:text-sm text-gray-600">100% genuine beauty products</p>
                </div>
                
                <div class="group text-center">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mb-3 mx-auto group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1 text-sm md:text-base">Secure Payment</h3>
                    <p class="text-xs md:text-sm text-gray-600">Safe & secure transactions</p>
                </div>
                
                <div class="group text-center">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl flex items-center justify-center mb-3 mx-auto group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1 text-sm md:text-base">24/7 Support</h3>
                    <p class="text-xs md:text-sm text-gray-600">Expert beauty advice</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Recently Viewed Products (if user has viewed products) -->
    @auth
        @if(session('recently_viewed_products'))
            <section class="py-12 md:py-20 bg-gray-50">
                <div class="container-responsive">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 md:mb-8">Recently Viewed</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-6">
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