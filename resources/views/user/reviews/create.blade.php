@extends('layouts.app')

@section('title', 'Write a Review - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('user.orders.index') }}" class="text-gray-500 hover:text-gray-700">My Orders</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('user.orders.show', $order) }}" class="text-gray-500 hover:text-gray-700">Order #{{ $order->order_number }}</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900">Write Review</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Write a Review</h1>
            <p class="mt-2 text-gray-600">Share your experience with other customers</p>
        </div>

        <!-- Review Form -->
        <form action="{{ route('user.reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <!-- Products to Review -->
            <div class="space-y-6">
                @foreach($order->items as $item)
                    @if(!$item->product->reviews()->where('user_id', auth()->id())->exists())
                        <div class="bg-white rounded-lg shadow p-6">
                            <!-- Product Info -->
                            <div class="flex items-start space-x-4 mb-6">
                                <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : '/placeholder.jpg' }}" 
                                     alt="{{ $item->product->name }}"
                                     class="w-20 h-20 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                    @if($item->variant_details)
                                        @php $variantDetails = json_decode($item->variant_details, true); @endphp
                                        <div class="flex space-x-2 mt-1">
                                            @if(!empty($variantDetails['size']))
                                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded">
                                                    Size: {{ $variantDetails['size'] }}
                                                </span>
                                            @endif
                                            @if(!empty($variantDetails['color']))
                                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded">
                                                    Color: {{ $variantDetails['color'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    How would you rate this product?
                                </label>
                                <div class="flex items-center space-x-1" id="rating-{{ $item->product->id }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" 
                                                class="star-btn text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors"
                                                data-rating="{{ $i }}"
                                                data-product-id="{{ $item->product->id }}">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" 
                                       name="reviews[{{ $item->product->id }}][rating]" 
                                       id="rating-input-{{ $item->product->id }}" 
                                       value=""
                                       required>
                                @error('reviews.' . $item->product->id . '.rating')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Review Title -->
                            <div class="mb-4">
                                <label for="title-{{ $item->product->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                    Review Title
                                </label>
                                <input type="text" 
                                       id="title-{{ $item->product->id }}"
                                       name="reviews[{{ $item->product->id }}][title]" 
                                       placeholder="Summarize your experience"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                       required>
                                @error('reviews.' . $item->product->id . '.title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Review Comment -->
                            <div>
                                <label for="comment-{{ $item->product->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                    Your Review
                                </label>
                                <textarea id="comment-{{ $item->product->id }}"
                                          name="reviews[{{ $item->product->id }}][comment]" 
                                          rows="4"
                                          placeholder="Tell us about your experience with this product..."
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                          required></textarea>
                                @error('reviews.' . $item->product->id . '.comment')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-between items-center">
                <a href="{{ route('user.orders.show', $order) }}" 
                   class="text-gray-600 hover:text-gray-900">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Star rating functionality
document.querySelectorAll('.star-btn').forEach(button => {
    button.addEventListener('click', function() {
        const rating = parseInt(this.dataset.rating);
        const productId = this.dataset.productId;
        const ratingContainer = document.getElementById(`rating-${productId}`);
        const ratingInput = document.getElementById(`rating-input-${productId}`);
        
        // Update hidden input
        ratingInput.value = rating;
        
        // Update star colors
        ratingContainer.querySelectorAll('.star-btn').forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    });
});
</script>
@endsection