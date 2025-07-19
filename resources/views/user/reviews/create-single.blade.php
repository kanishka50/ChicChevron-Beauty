<!-- SEPARATOR -->
---
<!-- WRITE REVIEW FOR SINGLE PRODUCT PAGE -->
@extends('layouts.app')

@section('title', 'Write a Review - ' . $product->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('user.orders.show', $order) }}" class="touch-target">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Write Review</h1>
            <div class="w-10"></div>
        </div>

        <!-- Desktop Breadcrumb -->
        <nav class="hidden lg:block mb-6 text-sm">
            <ol class="flex flex-wrap items-center space-x-1">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition-colors">Home</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('user.orders.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">My Orders</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('user.orders.show', $order) }}" class="text-gray-500 hover:text-gray-700 transition-colors">Order #{{ $order->order_number }}</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Write Review</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="hidden lg:block mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Write a Review</h1>
            <p class="text-gray-600">Share your experience with {{ $product->name }}</p>
        </div>

        <!-- Review Form -->
        <form action="{{ route('user.reviews.store.single', [$order, $product]) }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <!-- Product Header -->
                <div class="p-6 bg-gradient-to-r from-primary-50 to-white border-b border-gray-100">
                    <div class="flex items-start space-x-4">
                        <div class="relative overflow-hidden rounded-lg flex-shrink-0">
                            <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : '/placeholder.jpg' }}" 
                                 alt="{{ $product->name }}"
                                 class="w-20 h-20 object-cover">
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                            @if($orderItem->variant_details)
                                @php $variantDetails = json_decode($orderItem->variant_details, true); @endphp
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if(!empty($variantDetails['size']))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            Size: {{ $variantDetails['size'] }}
                                        </span>
                                    @endif
                                    @if(!empty($variantDetails['color']))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            <span class="w-3 h-3 mr-1 rounded-full border border-gray-300" style="background-color: {{ $variantDetails['color'] }}"></span>
                                            Color: {{ $variantDetails['color'] }}
                                        </span>
                                    @endif
                                    @if(!empty($variantDetails['scent']))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            Scent: {{ $variantDetails['scent'] }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Rating -->
                    <div>
                        <label class="form-label">
                            How would you rate this product? <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2 mt-3" id="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        class="star-btn text-4xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-all duration-200 transform hover:scale-110"
                                        data-rating="{{ $i }}">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" value="" required>
                        @error('rating')
                            <p class="mt-2 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Review Title -->
                    <div>
                        <label for="title" class="form-label">
                            Review Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="title"
                               name="title" 
                               placeholder="Summarize your experience in a few words"
                               class="form-input"
                               value="{{ old('title') }}"
                               required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Review Comment -->
                    <div>
                        <label for="comment" class="form-label">
                            Your Review <span class="text-red-500">*</span>
                        </label>
                        <textarea id="comment"
                                  name="comment" 
                                  rows="5"
                                  placeholder="Tell us about your experience with this product. What did you like or dislike?"
                                  class="form-input"
                                  required>{{ old('comment') }}</textarea>
                        <p class="mt-2 text-xs text-gray-500">Minimum 20 characters</p>
                        @error('comment')
                            <p class="mt-2 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tips Section -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Tips for writing helpful reviews:</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Describe what you liked or disliked about the product
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Mention how the product met your expectations
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Share any tips for other customers
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <a href="{{ route('user.orders.show', $order) }}" 
                       class="btn btn-secondary text-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="btn btn-primary group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Submit Review
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
    }
    
    .star-btn.filled {
        color: #facc15;
        transform: scale(1.1);
    }
</style>
@endpush

@push('scripts')
<script>
// Star rating functionality
document.querySelectorAll('.star-btn').forEach((button) => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const rating = parseInt(this.dataset.rating);
        
        // Update hidden input
        document.getElementById('rating-input').value = rating;
        
        // Update star display with animation
        document.querySelectorAll('.star-btn').forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400', 'filled');
            } else {
                star.classList.remove('text-yellow-400', 'filled');
                star.classList.add('text-gray-300');
            }
        });
        
        // Add pulse animation to selected star
        this.style.transform = 'scale(1.2)';
        setTimeout(() => {
            this.style.transform = 'scale(1.1)';
        }, 200);
    });
    
    // Hover effect
    button.addEventListener('mouseenter', function() {
        const rating = parseInt(this.dataset.rating);
        document.querySelectorAll('.star-btn').forEach((star, index) => {
            if (index < rating) {
                star.classList.add('text-yellow-300');
            }
        });
    });
    
    button.addEventListener('mouseleave', function() {
        document.querySelectorAll('.star-btn').forEach((star) => {
            if (!star.classList.contains('filled')) {
                star.classList.remove('text-yellow-300');
            }
        });
    });
});

// Character counter for comment
const commentField = document.getElementById('comment');
if (commentField) {
    commentField.addEventListener('input', function() {
        const length = this.value.length;
        const minLength = 20;
        
        if (length < minLength) {
            this.classList.add('border-red-300');
        } else {
            this.classList.remove('border-red-300');
        }
    });
}
</script>
@endpush
@endsection