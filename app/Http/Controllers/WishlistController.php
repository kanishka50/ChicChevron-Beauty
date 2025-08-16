<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Display user's wishlist
     */
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with(['product.brand', 'product.images'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Toggle product in wishlist
     */
    public function toggle($productId)
    {
        try {
            // Validate product exists
            $product = Product::findOrFail($productId);
            
            // Check if already in wishlist
            $existingItem = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($existingItem) {
                // Remove from wishlist
                $existingItem->delete();
                
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
                
                return response()->json([
                    'success' => true,
                    'added' => false,
                    'message' => 'Product removed from wishlist',
                    'wishlist_count' => $wishlistCount
                ]);
            } else {
                // Add to wishlist
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId
                ]);
                
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
                
                return response()->json([
                    'success' => true,
                    'added' => true,
                    'message' => 'Product added to wishlist',
                    'wishlist_count' => $wishlistCount
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Wishlist toggle error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating wishlist. Please try again.'
            ], 500);
        }
    }

    /**
     * Add product to wishlist
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            
            // Check if already in wishlist
            $existingItem = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is already in your wishlist.'
                ], 400);
            }

            // Add to wishlist
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);

            // Get updated wishlist count
            $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist successfully!',
                'wishlist_count' => $wishlistCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding to wishlist. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        try {
            $deleted = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->delete();

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in wishlist.'
                ], 404);
            }

            // Get updated wishlist count
            $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist.',
                'wishlist_count' => $wishlistCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing from wishlist. Please try again.'
            ], 500);
        }
    }

    /**
     * Clear entire wishlist
     */
    public function clear()
    {
        try {
            Wishlist::where('user_id', Auth::id())->delete();

            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared successfully!',
                'wishlist_count' => 0
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing wishlist. Please try again.'
            ], 500);
        }
    }

    /**
     * Get wishlist count for header
     */
    public function getCount()
    {
        $count = Wishlist::where('user_id', Auth::id())->count();
        
        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Check if product is in wishlist
     */
    public function check(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $inWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        return response()->json([
            'in_wishlist' => $inWishlist
        ]);
    }
}