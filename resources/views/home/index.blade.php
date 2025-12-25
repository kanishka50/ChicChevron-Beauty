@extends('layouts.app')

@section('title', 'ChicChevron Beauty - Premium Beauty Products Sri Lanka')
@section('description', 'Discover premium beauty products at ChicChevron Beauty. Shop authentic skincare, cosmetics, and beauty essentials with fast delivery across Sri Lanka.')

@section('content')

    <!-- Hero Section - Premium Modern Design -->
    <section class="relative min-h-[90vh] lg:min-h-screen flex items-center overflow-hidden">
        <!-- Background Image Slideshow -->
        <div class="absolute inset-0">
            <!-- Image 1 -->
            <div class="hero-slide absolute inset-0" style="animation-delay: 0s;">
                <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1920&q=80"
                     alt="Beauty Background"
                     class="w-full h-full object-cover object-center">
            </div>
            <!-- Image 2 -->
            <div class="hero-slide absolute inset-0" style="animation-delay: 5s;">
                <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1920&q=80"
                     alt="Beauty Products"
                     class="w-full h-full object-cover object-center">
            </div>
            <!-- Image 3 -->
            <div class="hero-slide absolute inset-0" style="animation-delay: 10s;">
                <img src="https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=1920&q=80"
                     alt="Skincare Collection"
                     class="w-full h-full object-cover object-center">
            </div>
            <!-- Image 4 -->
            <div class="hero-slide absolute inset-0" style="animation-delay: 15s;">
                <img src="https://images.unsplash.com/photo-1571781926291-c477ebfd024b?auto=format&fit=crop&w=1920&q=80"
                     alt="Makeup Collection"
                     class="w-full h-full object-cover object-center">
            </div>
            <!-- Image 5 -->
            <div class="hero-slide absolute inset-0" style="animation-delay: 20s;">
                <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1920&q=80"
                     alt="Beauty Essentials"
                     class="w-full h-full object-cover object-center">
            </div>
        </div>

        <!-- Gradient Overlay - Reduced Opacity to Show Images -->
        <div class="absolute inset-0 bg-gradient-to-r from-plum-950/80 via-plum-900/70 to-plum-900/50"></div>

        <!-- Secondary Gradient for Depth -->
        <div class="absolute inset-0 bg-gradient-to-t from-plum-950/60 via-transparent to-plum-900/30"></div>

        <!-- Subtle Grain Texture -->
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 256 256%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noise%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noise)%22/%3E%3C/svg%3E');"></div>

        <!-- Elegant Accent Lines -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-gold-500/50 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-gold-500/30 to-transparent"></div>

        <!-- Content Container -->
        <div class="container-responsive relative z-10 py-20 lg:py-0 flex items-center justify-center min-h-[80vh]">
            <div class="max-w-4xl mx-auto">
                <!-- Hero Content -->
                <div class="text-center" data-aos="fade-up">
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-white mb-6 leading-[1.1]">
                        Elevate Your
                        <span class="block mt-2">
                            <span class="relative inline-block">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-300 via-gold-400 to-gold-500">Beauty</span>
                                <svg class="absolute -bottom-2 left-0 w-full h-3 text-gold-500/30" viewBox="0 0 100 12" preserveAspectRatio="none">
                                    <path d="M0,8 Q25,0 50,8 T100,8" stroke="currentColor" stroke-width="3" fill="none"/>
                                </svg>
                            </span>
                            <span class="text-white"> Routine</span>
                        </span>
                    </h1>

                    <p class="text-lilac-200/90 text-lg lg:text-xl mb-10 max-w-2xl mx-auto leading-relaxed font-light">
                        Discover curated skincare and cosmetics from world-renowned brands. Experience luxury beauty, delivered to your doorstep.
                    </p>

                    <!-- CTA Button -->
                    <a href="{{ route('products.index') }}"
                       class="group relative inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-gold-400 to-gold-500 text-plum-900 font-bold rounded-full overflow-hidden transition-all duration-500 hover:shadow-[0_0_40px_rgba(201,169,98,0.4)] hover:scale-105">
                        <span class="relative z-10">Shop Collection</span>
                        <svg class="w-5 h-5 relative z-10 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                        <!-- Shine Effect -->
                        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-700 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                    </a>

                </div>

            </div>
        </div>
    </section>

    <!-- Main Categories - Compact Section -->
    @if($mainCategories->isNotEmpty())
    <section id="categories" class="py-10 lg:py-14 bg-white">
        <div class="container-responsive">
            <!-- Section Header - Compact -->
            <div class="text-center mb-8" data-aos="fade-up">
                <h2 class="font-display text-2xl md:text-3xl font-bold text-plum-900 mb-2">
                    Shop by Category
                </h2>
                <p class="text-plum-600 text-sm md:text-base">
                    Explore our beauty collections
                </p>
            </div>

            <!-- Category Images -->
            @php
                $categoryImages = [
                    'Skincare' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=400&q=80',
                    'Makeup' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=400&q=80',
                    'Haircare' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?auto=format&fit=crop&w=400&q=80',
                    'Fragrance' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=400&q=80',
                    'Body Care' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=400&q=80',
                    'Tools & Accessories' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=400&q=80',
                ];
                $defaultImage = 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=400&q=80';
            @endphp

            <!-- Mobile: Horizontal Swiper -->
            <div class="swiper categories-swiper md:hidden" data-aos="fade-up">
                <div class="swiper-wrapper">
                    @foreach($mainCategories as $mainCategory)
                        @php
                            $image = $categoryImages[$mainCategory->name] ?? $defaultImage;
                            $firstCategory = $mainCategory->categories->first();
                        @endphp
                        @if($firstCategory)
                        <div class="swiper-slide" style="width: 130px;">
                            <a href="{{ route('products.index', ['main_category' => $mainCategory->id]) }}" class="block">
                                <div class="relative rounded-xl overflow-hidden aspect-square">
                                    <img src="{{ $image }}"
                                         alt="{{ $mainCategory->name }}"
                                         class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-2.5 text-center">
                                        <span class="text-white text-xs font-medium">{{ $mainCategory->name }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Desktop: Centered Grid -->
            <div class="hidden md:flex justify-center" data-aos="fade-up">
                <div class="inline-flex gap-4">
                    @foreach($mainCategories as $index => $mainCategory)
                        @php
                            $image = $categoryImages[$mainCategory->name] ?? $defaultImage;
                            $firstCategory = $mainCategory->categories->first();
                        @endphp
                        @if($firstCategory)
                        <a href="{{ route('products.index', ['main_category' => $mainCategory->id]) }}"
                           class="group block">
                            <div class="relative rounded-xl overflow-hidden w-32 lg:w-36 aspect-square">
                                <img src="{{ $image }}"
                                     alt="{{ $mainCategory->name }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-3 text-center">
                                    <span class="text-white text-xs font-medium">{{ $mainCategory->name }}</span>
                                </div>
                            </div>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Products -->
    @if($featuredProducts->isNotEmpty())
    <section class="py-20 lg:py-28 bg-gradient-to-b from-lilac-100 to-white relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-plum-200/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-gold-200/30 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

        <div class="container-responsive relative z-10">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-14" data-aos="fade-up">
                <div class="mb-6 md:mb-0">
                    <span class="inline-block px-4 py-1 bg-plum-100 text-plum-700 text-sm font-medium rounded-full mb-4">FEATURED</span>
                    <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-plum-900 mb-3">
                        Our Best Picks
                    </h2>
                    <p class="text-plum-600 text-lg">Handpicked favorites that our customers love</p>
                </div>
                <a href="{{ route('products.index', ['featured' => 1]) }}"
                   class="group inline-flex items-center gap-2 px-6 py-3 bg-plum-800 text-white font-semibold rounded-full hover:bg-plum-900 transition-all duration-300 hover:shadow-elegant">
                    <span>View All</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <!-- Products Swiper -->
            <div class="swiper featured-products-swiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper pb-4">
                    @foreach($featuredProducts as $product)
                        <div class="swiper-slide">
                            @include('components.shop.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination !relative mt-8"></div>
            </div>
        </div>
    </section>
    @endif

    <!-- New Arrivals -->
    @if($newArrivals->isNotEmpty())
    <section class="py-20 lg:py-28 bg-white relative overflow-hidden">
        <div class="container-responsive">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-14" data-aos="fade-up">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-block px-4 py-1 bg-gold-100 text-gold-700 text-sm font-medium rounded-full">NEW ARRIVALS</span>
                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-gold-500 to-gold-600 text-white text-xs font-bold rounded-full shadow-gold animate-pulse-slow">
                            JUST IN
                        </span>
                    </div>
                    <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-plum-900 mb-3">
                        Fresh Arrivals
                    </h2>
                    <p class="text-plum-600 text-lg">The latest additions to our collection</p>
                </div>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}"
                   class="group inline-flex items-center gap-2 px-6 py-3 border-2 border-plum-800 text-plum-800 font-semibold rounded-full hover:bg-plum-800 hover:text-white transition-all duration-300">
                    <span>View All</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
                @foreach($newArrivals as $index => $product)
                    <div data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                        @include('components.shop.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Best Sellers -->
    @if($bestSellers->isNotEmpty())
    <section class="py-20 lg:py-28 bg-lilac-50 relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-20 left-10 w-64 h-64 bg-plum-200/40 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 bg-gold-200/30 rounded-full blur-3xl"></div>

        <div class="container-responsive relative z-10">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-14" data-aos="fade-up">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-block px-4 py-1 bg-plum-200 text-plum-700 text-sm font-medium rounded-full">BEST SELLERS</span>
                        <svg class="w-6 h-6 text-gold-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-plum-900 mb-3">
                        Customer Favorites
                    </h2>
                    <p class="text-plum-600 text-lg">Top-rated products loved by our customers</p>
                </div>
                <a href="{{ route('products.index', ['sort' => 'popular']) }}"
                   class="group inline-flex items-center gap-2 px-6 py-3 bg-plum-800 text-white font-semibold rounded-full hover:bg-plum-900 transition-all duration-300 hover:shadow-elegant">
                    <span>View All</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
                @foreach($bestSellers as $index => $product)
                    <div data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                        @include('components.shop.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Brand Showcase - Compact -->
    @if($brands->isNotEmpty())
    <section class="py-10 lg:py-14 bg-white">
        <div class="container-responsive">
            <!-- Section Header - Compact -->
            <div class="text-center mb-8" data-aos="fade-up">
                <h2 class="font-display text-2xl md:text-3xl font-bold text-plum-900 mb-2">
                    Shop by Brand
                </h2>
                <p class="text-plum-600 text-sm md:text-base">
                    Trusted beauty brands we carry
                </p>
            </div>

            <!-- Brands Swiper -->
            <div class="swiper brands-swiper" data-aos="fade-up" data-aos-delay="50">
                <div class="swiper-wrapper items-center">
                    @foreach($brands as $brand)
                        <div class="swiper-slide">
                            <a href="{{ route('products.index', ['brands' => [$brand->id]]) }}"
                               class="group block">
                                <div class="bg-gray-50 border border-gray-100 p-4 rounded-xl transition-all duration-300 text-center hover:bg-lilac-50 hover:border-plum-200 hover:shadow-sm">
                                    <div class="w-12 h-12 mx-auto mb-2 bg-white rounded-lg flex items-center justify-center text-sm font-bold text-plum-600 group-hover:bg-plum-50 transition-colors duration-300 shadow-sm">
                                        {{ strtoupper(substr($brand->name, 0, 2)) }}
                                    </div>
                                    <h3 class="font-medium text-gray-700 group-hover:text-plum-700 transition-colors duration-300 text-xs truncate">
                                        {{ $brand->name }}
                                    </h3>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Recently Viewed Products -->
    @auth
        @if(session('recently_viewed_products'))
            <section class="py-20 lg:py-24 bg-white">
                <div class="container-responsive">
                    <div class="flex justify-between items-center mb-10" data-aos="fade-up">
                        <h2 class="font-display text-2xl md:text-3xl font-bold text-plum-900">Recently Viewed</h2>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                        @foreach(session('recently_viewed_products') as $productId)
                            @php
                                $recentProduct = \App\Models\Product::with(['brand', 'variants.inventory'])->find($productId);
                            @endphp
                            @if($recentProduct)
                                <div data-aos="fade-up">
                                    @include('components.shop.product-card', ['product' => $recentProduct])
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endauth

@endsection

@push('styles')
<style>
    /* Hero Background Slideshow - Crossfade Animation */
    .hero-slide {
        opacity: 0;
        animation: heroFade 25s infinite;
    }

    @keyframes heroFade {
        0% { opacity: 0; }
        4% { opacity: 1; }
        20% { opacity: 1; }
        24% { opacity: 0; }
        100% { opacity: 0; }
    }

    /* Swiper custom styles */
    .swiper-pagination-bullet {
        background-color: #D4BDD9;
        opacity: 1;
    }
    .swiper-pagination-bullet-active {
        background-color: #4A2D4F;
    }

    /* Custom scrollbar for swiper */
    .swiper::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Featured Products Swiper
        new Swiper('.featured-products-swiper', {
            slidesPerView: 1,
            spaceBetween: 16,
            grabCursor: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 24,
                },
            },
        });

        // Categories Swiper (Mobile)
        new Swiper('.categories-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 12,
            grabCursor: true,
            freeMode: true,
        });

        // Brands Swiper
        new Swiper('.brands-swiper', {
            slidesPerView: 3,
            spaceBetween: 16,
            grabCursor: true,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 5,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 6,
                    spaceBetween: 24,
                },
                1280: {
                    slidesPerView: 8,
                    spaceBetween: 24,
                },
            },
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
@endpush
