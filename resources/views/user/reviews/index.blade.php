@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        

        <!-- Main Content -->
        <div class="lg:w-3/4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-2xl font-bold mb-6">My Reviews</h2>

                @if($reviews->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <p class="mt-4 text-gray-600">You haven't written any reviews yet.</p>
                        <a href="{{ route('user.orders.index') }}" class="mt-4 inline-block bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700">
                            View Your Orders
                        </a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="border border-gray-200 rounded-lg p-6">
                                <!-- Product Info -->
                                <div class="flex items-start space-x-4 mb-4">
                                    <a href="{{ route('products.show', $review->product->slug) }}" class="flex-shrink-0">
                                        <img src="{{ $review->product->main_image }}" 
                                             alt="{{ $review->product->name }}"
                                             class="w-20 h-20 object-cover rounded">
                                    </a>
                                    <div class="flex-1">
                                        <a href="{{ route('products.show', $review->product->slug) }}" 
                                           class="text-lg font-semibold hover:text-pink-600">
                                            {{ $review->product->name }}
                                        </a>
                                        <p class="text-sm text-gray-500">
                                            Reviewed on {{ $review->created_at->format('F j, Y') }}
                                        </p>
                                        @if($review->is_verified_purchase)
                                            <span class="inline-flex items-center text-xs text-green-600 mt-1">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Verified Purchase
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Rating -->
                                <div class="flex items-center mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>

                                <!-- Review Title -->
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $review->title }}</h3>

                                <!-- Review Comment -->
                                <p class="text-gray-700 mb-4">{{ $review->comment }}</p>

                                <!-- Actions -->
                                <div class="flex items-center space-x-4">
                                    <button type="button" 
                                            onclick="openEditModal({{ $review->id }})"
                                            class="text-sm text-blue-600 hover:text-blue-800">
                                        Edit
                                    </button>
                                    <form action="{{ route('user.reviews.destroy', $review) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Review Modal -->
<div id="editReviewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white rounded-lg max-w-lg w-full p-6">
            <h3 class="text-lg font-semibold mb-4">Edit Review</h3>
            
            <form id="editReviewForm" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex items-center space-x-1" id="editRating">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    class="edit-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none"
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
                    <label for="editTitle" class="block text-sm font-medium text-gray-700 mb-1">
                        Review Title
                    </label>
                    <input type="text" 
                           id="editTitle"
                           name="title" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                           required>
                </div>

                <!-- Comment -->
                <div class="mb-4">
                    <label for="editComment" class="block text-sm font-medium text-gray-700 mb-1">
                        Your Review
                    </label>
                    <textarea id="editComment"
                              name="comment" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                              required></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeEditModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                        Update Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
}

function closeEditModal() {
    document.getElementById('editReviewModal').classList.add('hidden');
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
</script>
@endsection