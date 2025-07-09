<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the homepage with banners, featured products, and categories.
     */
    public function index()
    {
        // Cache homepage data for better performance (cache for 30 minutes)
        $data = Cache::remember('homepage_data', 1800, function () {
            return [
                'banners' => Banner::active()
                    ->orderBy('sort_order')
                    ->get(),
                
                'featuredProducts' => Product::active()
                    ->with(['brand', 'category', 'images', 'inventory', 'reviews'])
                    ->withAvg('reviews', 'rating')
                    ->featured()
                    ->inStock()
                    ->limit(8)
                    ->get(),
                
                'newArrivals' => Product::active()
                    ->with(['brand', 'category', 'images', 'inventory', 'reviews'])
                    ->withAvg('reviews', 'rating')
                    ->latest('created_at')
                    ->inStock()
                    ->limit(8)
                    ->get(),
                
                'bestSellers' => Product::active()
                    ->with(['brand', 'category', 'images', 'inventory', 'reviews'])
                    ->withAvg('reviews', 'rating')
                    ->withCount(['orderItems'])
                    ->having('order_items_count', '>', 0)
                    ->orderBy('order_items_count', 'desc')
                    ->inStock()
                    ->limit(8)
                    ->get(),
                
                'categories' => Category::active()
                    ->ordered()
                    ->whereHas('products', function ($query) {
                        $query->active()->inStock();
                    })
                    ->withCount(['products' => function ($query) {
                        $query->active()->inStock();
                    }])
                    ->limit(6)
                    ->get(),
                
                'brands' => Brand::active()
                    ->whereHas('products', function ($query) {
                        $query->active()->inStock();
                    })
                    ->withCount(['products' => function ($query) {
                        $query->active()->inStock();
                    }])
                    ->orderBy('products_count', 'desc')
                    ->limit(8)
                    ->get(),
            ];
        });

        return view('home.index', $data);
    }

    /**
     * Clear homepage cache (useful for admin after updating products/banners)
     */
    public function clearCache()
    {
        Cache::forget('homepage_data');
        return response()->json(['message' => 'Homepage cache cleared successfully']);
    }

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
            // Product suggestions
            $products = Product::active()
                ->where('name', 'like', "%{$query}%")
                ->with(['brand', 'category'])
                ->limit(5)
                ->get()
                ->map(function ($product) {
                    return [
                        'type' => 'product',
                        'title' => $product->name,
                        'subtitle' => $product->brand->name ?? '',
                        'url' => route('products.show', $product->slug),
                        'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                        'price' => $product->display_price,
                    ];
                });

            // Brand suggestions
            $brands = Brand::active()
                ->where('name', 'like', "%{$query}%")
                ->whereHas('products', function ($q) {
                    $q->active()->inStock();
                })
                ->limit(3)
                ->get()
                ->map(function ($brand) {
                    return [
                        'type' => 'brand',
                        'title' => $brand->name,
                        'subtitle' => $brand->products_count . ' products',
                        'url' => route('products.index', ['brand' => $brand->id]),
                        'image' => $brand->logo ? asset('storage/' . $brand->logo) : null,
                    ];
                });

            // Category suggestions
            $categories = Category::active()
                ->where('name', 'like', "%{$query}%")
                ->whereHas('products', function ($q) {
                    $q->active()->inStock();
                })
                ->limit(3)
                ->get()
                ->map(function ($category) {
                    return [
                        'type' => 'category',
                        'title' => $category->name,
                        'subtitle' => $category->products_count . ' products',
                        'url' => route('products.index', ['category' => $category->id]),
                        'image' => $category->image ? asset('storage/' . $category->image) : null,
                    ];
                });

            return $products->concat($brands)->concat($categories)->take(10);
        });

        return response()->json($suggestions);
    }
}