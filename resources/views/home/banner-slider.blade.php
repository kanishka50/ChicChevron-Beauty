@php
    $banners = \App\Models\Banner::active()->ordered()->get();
@endphp

@if($banners->isNotEmpty())
    <section class="relative overflow-hidden">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                    <div class="swiper-slide">
                        <div class="relative h-96 md:h-[500px] bg-gradient-to-r from-pink-500 to-purple-600">
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

                            <!-- Banner Content Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                    <div class="max-w-lg text-white">
                                        @if($banner->title)
                                            <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ $banner->title }}</h1>
                                        @endif
                                        
                                        @if($banner->link_type !== 'none' && $banner->link_value)
                                            <a href="{{ $banner->full_url }}" 
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
            @if($banners->count() > 1)
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next text-white"></div>
                <div class="swiper-button-prev text-white"></div>
            @endif
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

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
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