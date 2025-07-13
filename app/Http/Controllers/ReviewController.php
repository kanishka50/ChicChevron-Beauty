<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
 * Update product's average rating
 */
private function updateProductRating($productId)
{
    $product = Product::find($productId);
    
    if ($product) {
        $stats = Review::where('product_id', $productId)
            ->where('is_approved', true)
            ->selectRaw('COUNT(*) as total_reviews, AVG(rating) as average_rating')
            ->first();

        $product->update([
            'average_rating' => round($stats->average_rating ?? 0, 1),
            'reviews_count' => $stats->total_reviews ?? 0,
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


    /**
 * Show form to create review for a single product
 */
public function createSingle(Order $order, Product $product)
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

    // Verify product was in the order
    $orderItem = $order->items()
        ->where('product_id', $product->id)
        ->first();

    if (!$orderItem) {
        abort(404, 'Product not found in this order.');
    }

    // Check if already reviewed
    $existingReview = Review::where('user_id', Auth::id())
        ->where('product_id', $product->id)
        ->first();

    if ($existingReview) {
        return redirect()->route('user.orders.show', $order)
            ->with('info', 'You have already reviewed this product.');
    }

    return view('user.reviews.create-single', compact('order', 'product', 'orderItem'));
}

/**
 * Store a single product review
 */
public function storeSingle(Request $request, Order $order, Product $product)
{
    // Validation
    $validated = $request->validate([
        'rating' => 'required|integer|between:1,5',
        'title' => 'required|string|max:100',
        'comment' => 'required|string|max:1000',
    ]);

    // Authorization checks
    if (Auth::id() !== $order->user_id) {
        abort(403, 'Unauthorized access to this order.');
    }

    if ($order->status !== 'completed') {
        return redirect()->route('user.orders.show', $order)
            ->with('error', 'You can only review products from completed orders.');
    }

    // Verify product was in the order
    $orderItem = $order->items()->where('product_id', $product->id)->first();
    if (!$orderItem) {
        abort(404, 'Product not found in this order.');
    }

    // Check if already reviewed
    $existingReview = Review::where('user_id', Auth::id())
        ->where('product_id', $product->id)
        ->first();

    if ($existingReview) {
        return redirect()->route('user.orders.show', $order)
            ->with('info', 'You have already reviewed this product.');
    }

    DB::beginTransaction();
    try {
        // Create the review
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Auto-approve verified purchases
        ]);

        // Update product rating
        $this->updateProductRating($product->id);

        DB::commit();

        return redirect()->route('user.orders.show', $order)
            ->with('success', 'Thank you! Your review has been submitted successfully.');

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Review submission error', [
            'error' => $e->getMessage(),
            'order_id' => $order->id,
            'product_id' => $product->id
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'There was an error submitting your review. Please try again.');
    }
}
}