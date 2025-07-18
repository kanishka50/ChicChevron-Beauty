@php
    $banners = \App\Models\Banner::active()->ordered()->get();
@endphp

@if($banners->isNotEmpty())
    <section class="relative overflow-hidden">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                    <div class="swiper-slide">
                        <div class="relative h-[400px] md:h-[600px] lg:h-[700px]">
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-transparent to-transparent z-10"></div>
                            
                            <!-- Desktop Image -->
                            @if($banner->image_desktop)
                                <img src="{{ $banner->desktop_image_url }}" 
                                     alt="{{ $banner->title }}" 
                                     class="hidden md:block w-full h-full object-cover">
                            @endif
                            
                            <!-- Mobile Image -->
                            @if($banner->image_mobile)
                                <img src="{{ $banner->mobile_image_url }}" 
                                     alt="{{ $banner->title }}" 
                                     class="md:hidden w-full h-full object-cover">
                            @elseif($banner->image_desktop)
                                <img src="{{ $banner->desktop_image_url }}" 
                                     alt="{{ $banner->title }}" 
                                     class="md:hidden w-full h-full object-cover">
                            @endif

                            <!-- Banner Content -->
                            <div class="absolute inset-0 flex items-center z-20">
                                <div class="container-responsive">
                                    <div class="max-w-xl">
                                        @if($banner->subtitle)
                                            <p class="text-primary-300 text-sm md:text-base font-medium mb-2 animate-fadeInUp">
                                                {{ $banner->subtitle }}
                                            </p>
                                        @endif
                                        
                                        @if($banner->title)
                                            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 animate-fadeInUp animation-delay-200">
                                                {{ $banner->title }}
                                            </h1>
                                        @endif
                                        
                                        @if($banner->description)
                                            <p class="text-gray-100 text-sm md:text-lg mb-6 animate-fadeInUp animation-delay-400">
                                                {{ $banner->description }}
                                            </p>
                                        @endif
                                        
                                        @if($banner->link_type !== 'none' && $banner->link_value)
                                            <a href="{{ $banner->full_url }}" 
                                               class="inline-flex items-center gap-2 bg-white text-gray-900 px-6 md:px-8 py-3 md:py-4 rounded-full font-semibold hover:bg-primary-50 transition-all duration-300 hover:shadow-2xl transform hover:scale-105 group animate-fadeInUp animation-delay-600">
                                                <span>Shop Now</span>
                                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
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
            @if($banners->count() > 1)
                <div class="swiper-pagination !bottom-6"></div>
                <div class="swiper-button-next !text-white !w-12 !h-12 !bg-white/20 !backdrop-blur-sm !rounded-full after:!text-base"></div>
                <div class="swiper-button-prev !text-white !w-12 !h-12 !bg-white/20 !backdrop-blur-sm !rounded-full after:!text-base"></div>
            @endif
        </div>
    </section>
@else
    <!-- Default Hero Section -->
    <section class="relative h-[400px] md:h-[600px] lg:h-[700px] bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative h-full flex items-center">
            <div class="container-responsive">
                <div class="max-w-xl">
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 animate-fadeInUp">
                        Discover Your Beauty
                    </h1>
                    <p class="text-lg md:text-xl text-primary-100 mb-8 animate-fadeInUp animation-delay-200">
                        Premium beauty products for every skin type and lifestyle
                    </p>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center gap-2 bg-white text-primary-700 px-6 md:px-8 py-3 md:py-4 rounded-full font-semibold hover:bg-primary-50 transition-all duration-300 hover:shadow-2xl transform hover:scale-105 group animate-fadeInUp animation-delay-400">
                        <span>Shop Now</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    .animation-delay-200 {
        animation-delay: 0.2s;
    }
    
    .animation-delay-400 {
        animation-delay: 0.4s;
    }
    
    .animation-delay-600 {
        animation-delay: 0.6s;
    }
    
    .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: white;
        opacity: 0.5;
    }
    
    .swiper-pagination-bullet-active {
        opacity: 1;
        background: white;
    }
</style>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.hero-swiper')) {
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
            }
        });
    </script>
@endpush