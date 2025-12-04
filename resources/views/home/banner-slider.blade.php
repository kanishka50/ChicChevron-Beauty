{{--
    HARDCODED BANNER SLIDER

    To update banners, edit the $banners array below.
    Each banner can have:
    - image: Path to image in public folder (e.g., 'images/banners/banner1.jpg')
    - image_mobile: Optional mobile-specific image
    - title: Optional title text
    - description: Optional description text
    - link: Optional URL to link to
--}}

@php
    $banners = [
        [
            'image' => 'images/banners/banner1.jpg',
            'image_mobile' => null,
            'title' => 'Welcome to ChicChevron Beauty',
            'description' => 'Discover premium beauty products for every skin type',
            'link' => route('products.index'),
        ],
        // Add more banners as needed:
        // [
        //     'image' => 'images/banners/banner2.jpg',
        //     'image_mobile' => 'images/banners/banner2-mobile.jpg',
        //     'title' => 'New Arrivals',
        //     'description' => 'Check out our latest products',
        //     'link' => route('products.index', ['sort' => 'newest']),
        // ],
    ];
@endphp

@if(count($banners) > 0)
    <!-- Responsive Banner Section -->
    <section class="container-responsive mt-4 lg:mt-6">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Carousel Container -->
            <div class="relative h-[250px] sm:h-[350px] md:h-[400px] lg:h-[450px] xl:h-[500px] overflow-hidden" id="banner-container">
                <!-- Carousel Wrapper for Sliding Effect -->
                <div class="carousel-wrapper relative h-full">
                    @foreach($banners as $index => $banner)
                        <div class="carousel-item absolute inset-0 w-full h-full transition-transform duration-700 ease-in-out
                                    {{ $index === 0 ? 'translate-x-0' : 'translate-x-full' }}"
                             data-index="{{ $index }}">
                            @if(!empty($banner['link']))
                                <a href="{{ $banner['link'] }}" class="block relative h-full">
                            @else
                                <div class="relative h-full">
                            @endif

                                <!-- Responsive Images -->
                                <picture class="absolute inset-0">
                                    <!-- Mobile Image (if exists) -->
                                    @if(!empty($banner['image_mobile']))
                                        <source media="(max-width: 640px)" srcset="{{ asset($banner['image_mobile']) }}">
                                    @endif
                                    <!-- Desktop Image (default) -->
                                    <img src="{{ asset($banner['image']) }}"
                                         alt="{{ $banner['title'] ?? 'Banner' }}"
                                         class="w-full h-full object-cover">
                                </picture>

                                <!-- Responsive Text Overlay -->
                                @if(!empty($banner['title']) || !empty($banner['description']))
                                    <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-8 md:p-12 bg-gradient-to-t from-black/50 to-transparent">
                                        <div class="max-w-4xl mx-auto text-center">
                                            @if(!empty($banner['title']))
                                                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-white mb-2 sm:mb-3"
                                                    style="font-family: 'Playfair Display', serif; letter-spacing: -0.02em;">
                                                    {{ $banner['title'] }}
                                                </h1>
                                            @endif

                                            @if(!empty($banner['description']))
                                                <p class="text-white/90 text-base sm:text-lg md:text-xl max-w-2xl mx-auto font-medium">
                                                    {{ $banner['description'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                            @if(!empty($banner['link']))
                                </a>
                            @else
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Dots (only if multiple banners) -->
                @if(count($banners) > 1)
                    <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                        @foreach($banners as $index => $banner)
                            <button type="button"
                                    class="pagination-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-300 {{ $index === 0 ? 'bg-white' : '' }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $index + 1 }}"
                                    data-slide-to="{{ $index }}"></button>
                        @endforeach
                    </div>

                    <!-- Previous/Next Controls (Hidden on Mobile) -->
                    <button type="button"
                            class="hidden md:flex absolute top-0 left-0 z-30 items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                            id="carousel-prev">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 transition-colors duration-300">
                            <svg class="w-4 h-4 text-white rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 6 10">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>

                    <button type="button"
                            class="hidden md:flex absolute top-0 right-0 z-30 items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                            id="carousel-next">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 transition-colors duration-300">
                            <svg class="w-4 h-4 text-white rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 6 10">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="sr-only">Next</span>
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </section>
@else
    <!-- Default Banner Section - No Banners Configured -->
    <section class="container-responsive mt-4 lg:mt-6">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="relative h-[250px] sm:h-[350px] md:h-[400px] lg:h-[450px] xl:h-[500px] bg-gradient-to-br from-primary-100 to-pink-100">
                <div class="absolute inset-0 flex items-center justify-center p-6 sm:p-8">
                    <div class="text-center max-w-2xl">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-3 sm:mb-4">
                            Welcome to ChicChevron Beauty
                        </h1>
                        <p class="text-sm sm:text-base md:text-lg text-gray-600 px-4">
                            Discover premium beauty products for every skin type. Authentic brands, competitive prices, and fast delivery across Sri Lanka.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

<style>
/* Carousel sliding animation styles */
.carousel-item {
    will-change: transform;
}

.translate-x-0 {
    transform: translateX(0);
}

.translate-x-full {
    transform: translateX(100%);
}

.-translate-x-full {
    transform: translateX(-100%);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

img {
    object-fit: cover;
    object-position: center;
}

.carousel-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
}
</style>

@if(count($banners) > 1)
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.carousel-item');
            const dots = document.querySelectorAll('.pagination-dot');
            const prevButton = document.getElementById('carousel-prev');
            const nextButton = document.getElementById('carousel-next');

            if (slides.length <= 1) return;

            let currentIndex = 0;
            let slideInterval;
            let isTransitioning = false;

            function initializeSlides() {
                slides.forEach((slide, index) => {
                    if (index === 0) {
                        slide.classList.add('translate-x-0');
                        slide.classList.remove('translate-x-full', '-translate-x-full');
                    } else {
                        slide.classList.add('translate-x-full');
                        slide.classList.remove('translate-x-0', '-translate-x-full');
                    }
                });
            }

            function showSlide(newIndex, direction = 'next') {
                if (isTransitioning || newIndex === currentIndex) return;

                isTransitioning = true;
                const currentSlide = slides[currentIndex];
                const newSlide = slides[newIndex];

                if (direction === 'next') {
                    newSlide.classList.add('translate-x-full');
                    newSlide.classList.remove('translate-x-0', '-translate-x-full');
                } else {
                    newSlide.classList.add('-translate-x-full');
                    newSlide.classList.remove('translate-x-0', 'translate-x-full');
                }

                newSlide.offsetHeight;

                requestAnimationFrame(() => {
                    if (direction === 'next') {
                        currentSlide.classList.add('-translate-x-full');
                        currentSlide.classList.remove('translate-x-0', 'translate-x-full');
                    } else {
                        currentSlide.classList.add('translate-x-full');
                        currentSlide.classList.remove('translate-x-0', '-translate-x-full');
                    }

                    newSlide.classList.add('translate-x-0');
                    newSlide.classList.remove('translate-x-full', '-translate-x-full');
                });

                dots.forEach((dot, index) => {
                    if (index === newIndex) {
                        dot.classList.add('bg-white');
                        dot.classList.remove('bg-white/50');
                        dot.setAttribute('aria-current', 'true');
                    } else {
                        dot.classList.remove('bg-white');
                        dot.classList.add('bg-white/50');
                        dot.setAttribute('aria-current', 'false');
                    }
                });

                currentIndex = newIndex;

                setTimeout(() => {
                    isTransitioning = false;
                }, 700);
            }

            function nextSlide() {
                const nextIndex = (currentIndex + 1) % slides.length;
                showSlide(nextIndex, 'next');
            }

            function prevSlide() {
                const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
                showSlide(prevIndex, 'prev');
            }

            function startSlideshow() {
                slideInterval = setInterval(nextSlide, 5000);
            }

            function stopSlideshow() {
                clearInterval(slideInterval);
            }

            initializeSlides();
            startSlideshow();

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    stopSlideshow();
                    const direction = index > currentIndex ? 'next' : 'prev';
                    showSlide(index, direction);
                    startSlideshow();
                });
            });

            if (prevButton) {
                prevButton.addEventListener('click', () => {
                    stopSlideshow();
                    prevSlide();
                    startSlideshow();
                });
            }

            if (nextButton) {
                nextButton.addEventListener('click', () => {
                    stopSlideshow();
                    nextSlide();
                    startSlideshow();
                });
            }

            const container = document.getElementById('banner-container');
            if (container && window.innerWidth >= 768) {
                container.addEventListener('mouseenter', stopSlideshow);
                container.addEventListener('mouseleave', startSlideshow);
            }

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    stopSlideshow();
                } else {
                    startSlideshow();
                }
            });
        });
    </script>
@endpush
@endif
