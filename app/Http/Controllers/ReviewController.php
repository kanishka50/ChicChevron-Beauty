<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class ReviewController extends BaseController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's reviews
     */
    public function index()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with(['product' => function($query) {
                $query->select('id', 'name', 'slug', 'main_image');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.reviews.index', compact('reviews'));
    }

    /**
     * Show form to create reviews for purchased products
     */
    public function create(Order $order)
    {
        // Ensure user can only review their own orders
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Check if order is completed
        if ($order->status !== 'completed') {
            return redirect()->route('user.orders.show', $order)
                ->with('error', 'You can only review products from completed orders.');
        }

        // Get products from order that haven't been reviewed yet
        $unreviewedItems = $order->items()
            ->with('product')
            ->whereDoesntHave('product.reviews', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        if ($unreviewedItems->isEmpty()) {
            return redirect()->route('user.orders.show', $order)
                ->with('info', 'You have already reviewed all products from this order.');
        }

        return view('user.reviews.create', compact('order', 'unreviewedItems'));
    }

    /**
     * Store new reviews
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reviews' => 'required|array|min:1',
            'reviews.*.rating' => 'required|integer|between:1,5',
            'reviews.*.title' => 'required|string|max:100',
            'reviews.*.comment' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        // Ensure user can only review their own orders
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Check if order is completed
        if ($order->status !== 'completed') {
            return redirect()->route('user.orders.show', $order)
                ->with('error', 'You can only review products from completed orders.');
        }

        DB::beginTransaction();

        try {
            $reviewsCreated = 0;

            foreach ($validated['reviews'] as $productId => $reviewData) {
                // Verify product was in the order
                $orderItem = $order->items()
                    ->where('product_id', $productId)
                    ->first();

                if (!$orderItem) {
                    continue;
                }

                // Check if user hasn't already reviewed this product
                $existingReview = Review::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->first();

                if ($existingReview) {
                    continue;
                }

                // Create the review
                Review::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'order_id' => $order->id,
                    'rating' => $reviewData['rating'],
                    'title' => $reviewData['title'],
                    'comment' => $reviewData['comment'],
                    'is_verified_purchase' => true,
                    'is_approved' => true, // Auto-approve verified purchases
                    'helpful_count' => 0,
                    'not_helpful_count' => 0,
                ]);

                $reviewsCreated++;

                // Update product rating
                $this->updateProductRating($productId);
            }

            DB::commit();

            if ($reviewsCreated > 0) {
                return redirect()->route('user.orders.show', $order)
                    ->with('success', "Thank you! Your review{$this->pluralize($reviewsCreated)} been submitted successfully.");
            } else {
                return redirect()->route('user.orders.show', $order)
                    ->with('info', 'You have already reviewed these products.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'There was an error submitting your reviews. Please try again.');
        }
    }

    /**
     * Update a review
     */
    public function update(Request $request, Review $review)
    {
        // Ensure user can only update their own reviews
        if (Auth::id() !== $review->user_id) {
            abort(403, 'Unauthorized access to this review.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'required|string|max:100',
            'comment' => 'required|string|max:1000',
        ]);

        $review->update($validated);

        // Update product rating
        $this->updateProductRating($review->product_id);

        return redirect()->back()
            ->with('success', 'Your review has been updated successfully.');
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        // Ensure user can only delete their own reviews
        if (Auth::id() !== $review->user_id) {
            abort(403, 'Unauthorized access to this review.');
        }

        $productId = $review->product_id;
        $review->delete();

        // Update product rating
        $this->updateProductRating($productId);

        return redirect()->back()
            ->with('success', 'Your review has been deleted successfully.');
    }

    /**
     * Mark a review as helpful or not helpful
     */
    public function markHelpful(Request $request, Review $review)
    {
        $validated = $request->validate([
            'helpful' => 'required|boolean',
        ]);

        // Check if user has already voted on this review
        $existingVote = DB::table('review_votes')
            ->where('user_id', Auth::id())
            ->where('review_id', $review->id)
            ->first();

        if ($existingVote) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted on this review.',
            ], 400);
        }

        // Record the vote
        DB::table('review_votes')->insert([
            'user_id' => Auth::id(),
            'review_id' => $review->id,
            'is_helpful' => $validated['helpful'],
            'created_at' => now(),
        ]);

        // Update review counts
        if ($validated['helpful']) {
            $review->increment('helpful_count');
        } else {
            $review->increment('not_helpful_count');
        }

        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count,
            'not_helpful_count' => $review->not_helpful_count,
        ]);
    }

    /**
     * Update product's average rating
     */
    private function updateProductRating($productId)
    {
        $product = Product::find($productId);
        
        if ($product) {
            $stats = Review::where('product_id', $productId)
                ->where('is_approved', true)
                ->selectRaw('
                    COUNT(*) as total_reviews,
                    AVG(rating) as average_rating,
                    SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star,
                    SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star,
                    SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star,
                    SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star,
                    SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
                ')
                ->first();

            $product->update([
                'average_rating' => round($stats->average_rating, 1),
                'reviews_count' => $stats->total_reviews,
                'rating_breakdown' => [
                    5 => $stats->five_star,
                    4 => $stats->four_star,
                    3 => $stats->three_star,
                    2 => $stats->two_star,
                    1 => $stats->one_star,
                ],
            ]);
        }
    }

    /**
     * Helper function to pluralize
     */
    private function pluralize($count)
    {
        return $count == 1 ? ' has' : 's have';
    }
}