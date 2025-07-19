<!-- MY REVIEWS PAGE -->
@extends('layouts.app')

@section('title', 'My Reviews - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <div class="container-responsive py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('user.account.index') }}" class="touch-target">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">My Reviews</h1>
            <div class="w-10"></div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden lg:block mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Reviews</h1>
            <p class="mt-1 text-gray-600">Manage your product reviews and ratings</p>
        </div>

        <!-- Reviews Stats -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <!-- Total Reviews -->
            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-2xl font-bold text-gray-900">{{ $reviews->total() }}</p>
                        <p class="text-xs text-gray-600 truncate">Total Reviews</p>
                    </div>
                </div>
            </div>
            
            <!-- Verified Reviews -->
            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-2xl font-bold text-gray-900">{{ $reviews->where('is_verified_purchase', true)->count() }}</p>
                        <p class="text-xs text-gray-600 truncate">Verified</p>
                    </div>
                </div>
            </div>
            
            <!-- Average Rating - Full Width on Mobile -->
            <div class="bg-white rounded-xl shadow-sm p-4 col-span-2">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Average Rating</p>
                        <div class="flex items-center space-x-2">
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($reviews->avg('rating'), 1) }}</span>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($reviews->avg('rating')) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('user.orders.index') }}" class="btn btn-primary btn-sm w-full sm:w-auto text-center">
                        Write New Review
                    </a>
                </div>
            </div>
        </div>

        @if($reviews->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm p-8 sm:p-12 text-center max-w-2xl mx-auto">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No reviews yet</h3>
                <p class="text-gray-600 mb-8">Share your experience with products you've purchased</p>
                <a href="{{ route('user.orders.index') }}" class="btn btn-primary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    View Your Orders
                </a>
            </div>
        @else
            <!-- Reviews List -->
            <div class="space-y-4">
                @foreach($reviews as $index => $review)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden"
                         style="animation: fadeInUp 0.5s ease-out {{ $index * 0.1 }}s backwards;">
                        <div class="p-6">
                            <!-- Product Info -->
                            <div class="flex flex-col sm:flex-row sm:items-start space-y-4 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('products.show', $review->product->slug) }}" 
                                   class="flex-shrink-0 group">
                                    <div class="relative overflow-hidden rounded-lg">
                                        <img src="{{ $review->product->main_image ? asset('storage/' . $review->product->main_image) : '/placeholder.jpg' }}" 
                                             alt="{{ $review->product->name }}"
                                             class="w-20 h-20 sm:w-24 sm:h-24 object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                </a>
                                
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <a href="{{ route('products.show', $review->product->slug) }}" 
                                               class="text-lg font-semibold text-gray-900 hover:text-primary-600 transition-colors">
                                                {{ $review->product->name }}
                                            </a>
                                            <div class="flex items-center space-x-3 mt-1">
                                                <p class="text-sm text-gray-500">
                                                    {{ $review->created_at->format('F j, Y') }}
                                                </p>
                                                @if($review->is_verified_purchase)
                                                    <span class="inline-flex items-center text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Verified Purchase
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Actions Dropdown -->
                                        <div class="mt-3 sm:mt-0 flex items-center space-x-2">
                                            <button type="button" 
                                                    onclick="openEditModal({{ $review->id }})"
                                                    class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                                                Edit
                                            </button>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('user.reviews.destroy', $review) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Rating -->
                                    <div class="flex items-center mt-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>

                                    <!-- Review Content -->
                                    <h3 class="font-semibold text-gray-900 mt-3">{{ $review->title }}</h3>
                                    <p class="text-gray-700 mt-2 leading-relaxed">{{ $review->comment }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Edit Review Modal -->
<div id="editReviewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 bg-opacity-75"></div>
        </div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Review</h3>
                    <button type="button" onclick="closeEditModal()" class="touch-target">
                        <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="editReviewForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Rating -->
                    <div class="mb-6">
                        <label class="form-label">Rating</label>
                        <div class="flex items-center space-x-1" id="editRating">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        class="edit-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors"
                                        data-rating="{{ $i }}">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" id="editRatingInput" name="rating" required>
                    </div>

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="editTitle" class="form-label">Review Title</label>
                        <input type="text" 
                               id="editTitle"
                               name="title" 
                               class="form-input"
                               placeholder="Summarize your experience"
                               required>
                    </div>

                    <!-- Comment -->
                    <div class="mb-6">
                        <label for="editComment" class="form-label">Your Review</label>
                        <textarea id="editComment"
                                  name="comment" 
                                  rows="4"
                                  class="form-input"
                                  placeholder="Tell us about your experience..."
                                  required></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" 
                                onclick="closeEditModal()"
                                class="btn btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="btn btn-primary">
                            Update Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Store reviews data for editing
const reviewsData = @json($reviews->items());

function openEditModal(reviewId) {
    const review = reviewsData.find(r => r.id === reviewId);
    if (!review) return;
    
    // Update form action
    document.getElementById('editReviewForm').action = `/reviews/${reviewId}`;
    
    // Set current values
    document.getElementById('editTitle').value = review.title;
    document.getElementById('editComment').value = review.comment;
    document.getElementById('editRatingInput').value = review.rating;
    
    // Update star display
    updateEditStars(review.rating);
    
    // Show modal
    document.getElementById('editReviewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editReviewModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function updateEditStars(rating) {
    const stars = document.querySelectorAll('.edit-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

// Star rating interaction for edit modal
document.querySelectorAll('.edit-star').forEach(star => {
    star.addEventListener('click', function() {
        const rating = parseInt(this.dataset.rating);
        document.getElementById('editRatingInput').value = rating;
        updateEditStars(rating);
    });
});

// Close modal on background click
document.getElementById('editReviewModal').addEventListener('click', function(e) {
    if (e.target === this || e.target.classList.contains('bg-gray-500')) {
        closeEditModal();
    }
});

// Escape key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('editReviewModal').classList.contains('hidden')) {
        closeEditModal();
    }
});
</script>
@endpush
@endsection