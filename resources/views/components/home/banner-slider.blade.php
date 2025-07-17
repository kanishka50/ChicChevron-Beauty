@php
    $banners = \App\Models\Banner::active()->ordered()->get();
@endphp

@if($banners->count() > 0)
<div class="relative overflow-hidden bg-gray-100" x-data="bannerSlider()">
    <!-- Banner Container -->
    <div class="relative h-[400px] md:h-[500px] lg:h-[600px]">
        @foreach($banners as $index => $banner)
            <div x-show="currentSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0">
                
                @if($banner->link_url)
                    <a href="{{ $banner->link_url }}" class="block relative h-full">
                @else
                    <div class="relative h-full">
                @endif
                    
                    <img src="{{ $banner->image_url }}" 
                         alt="{{ $banner->title }}"
                         class="w-full h-full object-cover">
                    
                    <!-- Overlay Content (if needed) -->
                    @if($banner->link_text)
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                            <div class="text-center">
                                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">{{ $banner->title }}</h2>
                                <span class="inline-block bg-white text-black px-6 py-3 rounded-full font-medium hover:bg-gray-100 transition">
                                    {{ $banner->link_text }}
                                </span>
                            </div>
                        </div>
                    @endif
                    
                @if($banner->link_url)
                    </a>
                @else
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    
    <!-- Navigation Arrows -->
    @if($banners->count() > 1)
        <button @click="previousSlide()" 
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button @click="nextSlide()" 
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- Dots Indicator -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
            @foreach($banners as $index => $banner)
                <button @click="currentSlide = {{ $index }}"
                        :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white bg-opacity-50'"
                        class="w-3 h-3 rounded-full transition"></button>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
function bannerSlider() {
    return {
        currentSlide: 0,
        totalSlides: {{ $banners->count() }},
        
        init() {
            // Auto-play slider
            if (this.totalSlides > 1) {
                setInterval(() => {
                    this.nextSlide();
                }, 5000); // Change slide every 5 seconds
            }
        },
        
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        },
        
        previousSlide() {
            this.currentSlide = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
        }
    }
}
</script>
@endpush
@endif