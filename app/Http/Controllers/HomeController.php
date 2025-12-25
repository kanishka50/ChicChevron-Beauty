<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured products and categories.
     */
    public function index()
    {
        // Cache homepage data for better performance (cache for 30 minutes)
        $data = Cache::remember('homepage_data', 1800, function () {
            
            // Get featured products
            $featuredProducts = Product::active()
                ->with(['brand', 'category', 'variants.inventory'])
                ->featured()
                ->inStock()
                ->limit(8)
                ->get();

            // Get new arrivals
            $newArrivals = Product::active()
                ->with(['brand', 'category', 'variants.inventory'])
                ->latest('created_at')
                ->inStock()
                ->limit(8)
                ->get();

            // Get best sellers
            $bestSellers = Product::active()
                ->with(['brand', 'category', 'variants.inventory', 'orderItems'])
                ->inStock()
                ->limit(8)
                ->get();

            // Load order items and sort by count
            $bestSellers->each(function ($product) {
                $product->order_items_count = $product->orderItems->count();
            });

            // Sort best sellers by order count
            $bestSellers = $bestSellers->sortByDesc('order_items_count')->take(8);
            
            return [
                'featuredProducts' => $featuredProducts,
                'newArrivals' => $newArrivals,
                'bestSellers' => $bestSellers,
                
                'categories' => Category::active()
                    ->ordered()
                    ->limit(12)
                    ->get(),

                'mainCategories' => MainCategory::with(['categories' => function($query) {
                        $query->active()->ordered();
                    }])
                    ->get(),

                'brands' => Brand::active()
                    ->orderBy('name')
                    ->limit(12)
                    ->get(),
            ];
        });

        return view('home.index', $data);
    }

    /**
     * Clear homepage cache (for admin use)
     */
    public function clearCache()
    {
        Cache::forget('homepage_data');
        
        return response()->json([
            'success' => true,
            'message' => 'Homepage cache cleared successfully!'
        ]);
    }

    /**
     * Get quick search suggestions
     */
    /**
 * Get quick search suggestions
 */
public function searchSuggestions(Request $request)
{
    $query = $request->get('q', '');
    
    if (strlen($query) < 2) {
        return response()->json([]);
    }

    $suggestions = Cache::remember('search_suggestions_' . md5($query), 300, function () use ($query) {
        $suggestions = collect();
        
        // Product suggestions with variants
        $products = Product::active()
            ->where('name', 'like', "%{$query}%")
            ->with(['brand', 'category', 'variants' => function($q) {
                $q->where('is_active', true)
                  ->orderBy('price', 'asc')
                  ->limit(1);
            }])
            ->limit(5)
            ->get()
            ->map(function ($product) {
                $variant = $product->variants->first();
                $price = $variant ? ($variant->discount_price ?? $variant->price) : 0;
                
                return [
                    'type' => 'product',
                    'text' => $product->name,
                    'subtitle' => $product->brand->name ?? '',
                    'url' => route('products.show', $product->slug),
                    'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                    'price' => $price > 0 ? 'Rs. ' . number_format($price, 2) : null,
                ];
            });

        // Brand suggestions
        $brands = Brand::active()
            ->where('name', 'like', "%{$query}%")
            ->whereHas('products', function ($q) {
                $q->active();
            })
            ->withCount('products')
            ->limit(3)
            ->get()
            ->map(function ($brand) {
                return [
                    'type' => 'brand',
                    'text' => $brand->name,
                    'subtitle' => $brand->products_count . ' products',
                    'url' => route('products.index', ['brands' => [$brand->id]]),
                    'image' => null,
                ];
            });

        // Category suggestions
        $categories = Category::active()
            ->where('name', 'like', "%{$query}%")
            ->whereHas('products', function ($q) {
                $q->active();
            })
            ->withCount('products')
            ->limit(3)
            ->get()
            ->map(function ($category) {
                return [
                    'type' => 'category',
                    'text' => $category->name,
                    'subtitle' => $category->products_count . ' products',
                    'url' => route('products.index', ['category' => $category->id]),
                    'image' => null,
                ];
            });

        return $suggestions
            ->concat($products)
            ->concat($brands)
            ->concat($categories)
            ->take(10);
    });

    return response()->json($suggestions);
}
}