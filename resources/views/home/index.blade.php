@extends('layouts.app')

@section('title', 'ChicChevron Beauty - Premium Beauty Products Sri Lanka')
@section('description', 'Discover premium beauty products at ChicChevron Beauty. Shop authentic skincare, cosmetics, and beauty essentials with fast delivery across Sri Lanka.')

@section('content')
    
    <!-- Hero Banner Section -->
    @include('home.banner-slider')

    <
<!-- Featured Categories - Modern Clean Design -->
@if($categories->isNotEmpty())
<section class="py-16 md:py-20 bg-gray-50">
    <div class="container-responsive">
        <!-- Section Header -->
        <div class="text-center mb-10 md:mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                Shop by Category
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Browse our curated collection of beauty essentials
            </p>
        </div>

        <!-- Category Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                   class="group">
                    <div class="flex flex-col items-center text-center">
                        <!-- Category Image/Icon Container -->
                        <div class="w-20 h-20 md:w-24 md:h-24 mb-3 relative overflow-hidden rounded-2xl bg-white border-2 border-gray-100 group-hover:border-primary-200 transition-all duration-300">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-full object-cover p-3 group-hover:scale-105 transition-transform duration-300">
                            @else
                                <!-- Simple Icon Fallback -->
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 group-hover:from-primary-50 group-hover:to-primary-100 transition-colors duration-300">
                                    <span class="text-2xl font-bold text-gray-400 group-hover:text-primary-500 transition-colors">
                                        {{ substr($category->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Category Name -->
                        <h3 class="font-medium text-gray-700 group-hover:text-primary-600 transition-colors duration-300 text-sm md:text-base">
                            {{ $category->name }}
                        </h3>
                        
                        <!-- Product Count -->
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

    <!-- Featured Products - Clean white background for contrast -->
    @if($featuredProducts->isNotEmpty())
        <section class="py-16 md:py-24 bg-white relative">
            <div class="container-responsive">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 md:mb-16">
                    <div class="mb-6 md:mb-0">
                        <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-3">Featured Products</h2>
                        <p class="text-gray-600 text-base md:text-lg">Handpicked favorites that our customers love</p>
                    </div>
                    <a href="{{ route('products.index', ['featured' => 1]) }}" 
                       class="inline-flex items-center gap-2 text-pink-600 hover:text-purple-600 font-semibold group bg-pink-50 hover:bg-purple-50 px-6 py-3 rounded-full transition-all duration-300">
                        <span>View All</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                    @foreach($featuredProducts as $product)
                        @include('components.shop.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Special Offer Banner - Keep existing gradient but enhance -->
    {{-- <section class="py-12 md:py-16 bg-gradient-to-r from-pink-600 via-purple-600 to-pink-700 relative overflow-hidden">
        <!-- Animated background pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-60 h-60 bg-white rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        </div>
        
        <div class="container-responsive relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-2">Limited Time Offer!</h3>
                    <p class="text-pink-100 text-base md:text-lg">Get 20% off on selected beauty products</p>
                </div>
                <a href="{{ route('products.index', ['sale' => 1]) }}" 
                   class="inline-flex items-center gap-2 bg-white text-pink-700 px-8 py-4 rounded-full font-bold hover:bg-pink-50 transition-all duration-300 hover:shadow-2xl transform hover:scale-105 group">
                    <span>Shop Sale</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section> --}}

    <!-- New Arrivals - Pink tinted background -->
    @if($newArrivals->isNotEmpty())
        <section class="py-16 md:py-24 bg-gradient-to-b from-pink-50/40 to-white relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-20 right-10 w-20 h-20 bg-pink-200/30 rounded-full blur-2xl"></div>
            <div class="absolute bottom-20 left-10 w-32 h-32 bg-purple-200/30 rounded-full blur-3xl"></div>
            
            <div class="container-responsive relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 md:mb-16">
                    <div class="mb-6 md:mb-0">
                        <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-3 flex items-center gap-3">
                            New Arrivals
                            <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-400 text-white text-sm font-bold rounded-full shadow-lg animate-pulse">
                                NEW
                            </span>
                        </h2>
                        <p class="text-gray-600 text-base md:text-lg">Fresh picks just for you</p>
                    </div>
                    <a href="{{ route('products.index', ['sort' => 'newest']) }}" 
                       class="inline-flex items-center gap-2 text-pink-600 hover:text-purple-600 font-semibold group bg-white hover:bg-pink-50 px-6 py-3 rounded-full transition-all duration-300 shadow-md hover:shadow-lg">
                        <span>View All</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                    @foreach($newArrivals as $product)
                        @include('components.shop.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Best Sellers - Clean white background -->
    @if($bestSellers->isNotEmpty())
        <section class="py-16 md:py-24 bg-white">
            <div class="container-responsive">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 md:mb-16">
                    <div class="mb-6 md:mb-0">
                        <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-3 flex items-center gap-3">
                            Best Sellers
                            <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </h2>
                        <p class="text-gray-600 text-base md:text-lg">Top-rated products loved by our customers</p>
                    </div>
                    <a href="{{ route('products.index', ['sort' => 'popular']) }}" 
                       class="inline-flex items-center gap-2 text-pink-600 hover:text-purple-600 font-semibold group bg-pink-50 hover:bg-purple-50 px-6 py-3 rounded-full transition-all duration-300">
                        <span>View All</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                    @foreach($bestSellers as $product)
                        @include('components.shop.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Brand Showcase - Enhanced with gradients -->
    @if($brands->isNotEmpty())
        <section class="py-16 md:py-24 bg-gradient-to-br from-gray-50 via-pink-50/20 to-white relative overflow-hidden">
            <!-- Decorative background -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-0 right-0 w-96 h-96 bg-pink-300 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-300 rounded-full blur-3xl"></div>
            </div>
            
            <div class="container-responsive relative z-10">
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-3xl md:text-5xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-4">Shop by Brand</h2>
                    <p class="text-gray-600 text-base md:text-lg">Discover products from your favorite beauty brands</p>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4 md:gap-6">
                    @foreach($brands as $brand)
                        <a href="{{ route('products.index', ['brands' => [$brand->id]]) }}" 
                           class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-6 md:p-8 rounded-2xl border border-pink-100 hover:border-pink-300 hover:shadow-xl transition-all duration-300 text-center relative overflow-hidden">
                                <!-- Hover gradient overlay -->
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <div class="relative z-10">
                                    @if($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" 
                                             alt="{{ $brand->name }}" 
                                             class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 object-contain group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 bg-gradient-to-br from-pink-100 to-purple-100 rounded-2xl flex items-center justify-center text-sm md:text-lg font-bold text-pink-700 group-hover:from-pink-200 group-hover:to-purple-200 transition-all duration-300">
                                            {{ substr($brand->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <h3 class="font-medium text-gray-900 group-hover:text-pink-600 transition-colors text-xs md:text-sm truncate">
                                        {{ $brand->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $brand->products_count }} items</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Newsletter Signup - Enhanced gradient and styling -->
    {{-- <section class="py-16 md:py-24 bg-gradient-to-br from-pink-600 via-purple-600 to-pink-700 relative overflow-hidden">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse animation-delay-2000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        </div>
        
        <div class="container-responsive relative z-10">
            <div class="max-w-2xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-8 shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Stay in the Loop</h2>
                <p class="text-pink-100 mb-10 text-base md:text-lg max-w-xl mx-auto">Subscribe to our newsletter for exclusive offers, beauty tips, and new product launches</p>
                
                <form id="newsletter-form" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    @csrf
                    <input 
                        type="email" 
                        name="email"
                        placeholder="Enter your email address" 
                        class="flex-1 px-6 py-4 rounded-full border-2 border-white/30 bg-white/20 backdrop-blur-sm text-white placeholder-white/70 focus:outline-none focus:border-white focus:bg-white/30 transition-all duration-300"
                        required
                    >
                    <button 
                        type="submit" 
                        class="px-8 py-4 bg-white text-pink-700 font-bold rounded-full hover:bg-pink-50 transition-all duration-300 hover:shadow-2xl transform hover:scale-105 group"
                    >
                        <span class="flex items-center gap-2">
                            Subscribe
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </section> --}}

    <!-- Trust Badges - Enhanced with gradient backgrounds -->
    <section class="py-16 md:py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="container-responsive">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <div class="group text-center transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-pink-100 to-pink-200 rounded-3xl flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform duration-300 shadow-lg group-hover:shadow-xl">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-base md:text-lg">Fast Delivery</h3>
                    <p class="text-sm md:text-base text-gray-600">Free shipping over Rs. 5,000</p>
                </div>
                
                <div class="group text-center transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-green-100 to-green-200 rounded-3xl flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform duration-300 shadow-lg group-hover:shadow-xl">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-base md:text-lg">Authentic Products</h3>
                    <p class="text-sm md:text-base text-gray-600">100% genuine beauty products</p>
                </div>
                
                <div class="group text-center transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform duration-300 shadow-lg group-hover:shadow-xl">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-base md:text-lg">Secure Payment</h3>
                    <p class="text-sm md:text-base text-gray-600">Safe & secure transactions</p>
                </div>
                
                <div class="group text-center transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-purple-100 to-purple-200 rounded-3xl flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform duration-300 shadow-lg group-hover:shadow-xl">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-base md:text-lg">24/7 Support</h3>
                    <p class="text-sm md:text-base text-gray-600">Expert beauty advice</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Recently Viewed Products (if user has viewed products) -->
    @auth
        @if(session('recently_viewed_products'))
            <section class="py-16 md:py-24 bg-white">
                <div class="container-responsive">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 md:mb-12">Recently Viewed</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                        @foreach(session('recently_viewed_products') as $productId)
                            @php
                                $recentProduct = \App\Models\Product::with(['brand', 'variants.inventory', 'reviews'])->find($productId);
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
    <style>
        /* Animation delays */
        .animation-delay-1000 {
            animation-delay: 1s;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        /* Custom animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
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