<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Handle search requests with advanced filtering
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('products.index')
                ->with('message', 'Please enter a search term.');
        }

        // Perform the search
        $results = $this->performSearch($query, $request);

        // Log search for analytics (optional)
        $this->logSearch($query, $results['products']->total());

        return view('search.results', [
            'query' => $query,
            'products' => $results['products'],
            'filters' => $results['filters'],
            'currentFilters' => $request->all(),
            'totalProducts' => $results['products']->total(),
            'searchSuggestions' => $this->getSearchSuggestions($query),
        ]);
    }

    /**
     * Get autocomplete suggestions for search
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'search_suggestions_' . md5(strtolower($query));
        
        $suggestions = Cache::remember($cacheKey, 300, function () use ($query) {
            $suggestions = collect();

            // Product name suggestions
            $productSuggestions = Product::active()
                ->where('name', 'like', "%{$query}%")
                ->select('name', 'slug', 'main_image')
                ->with('brand:id,name')
                ->limit(5)
                ->get()
                ->map(function ($product) {
                    return [
                        'type' => 'product',
                        'text' => $product->name,
                        'subtitle' => $product->brand->name ?? '',
                        'url' => route('products.show', $product->slug),
                        'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                    ];
                });

            // Brand suggestions
            $brandSuggestions = Brand::active()
                ->where('name', 'like', "%{$query}%")
                ->whereHas('products', function ($q) {
                    $q->active();
                })
                ->select('id', 'name', 'logo')
                ->limit(3)
                ->get()
                ->map(function ($brand) {
                    return [
                        'type' => 'brand',
                        'text' => $brand->name,
                        'subtitle' => 'Brand',
                        'url' => route('products.index', ['brands' => [$brand->id]]),
                        'image' => $brand->logo ? asset('storage/' . $brand->logo) : null,
                    ];
                });

            // Category suggestions
            $categorySuggestions = Category::active()
                ->where('name', 'like', "%{$query}%")
                ->whereHas('products', function ($q) {
                    $q->active();
                })
                ->select('id', 'name', 'image')
                ->limit(3)
                ->get()
                ->map(function ($category) {
                    return [
                        'type' => 'category',
                        'text' => $category->name,
                        'subtitle' => 'Category',
                        'url' => route('products.index', ['category' => $category->id]),
                        'image' => $category->image ? asset('storage/' . $category->image) : null,
                    ];
                });

            // Ingredient suggestions
            $ingredientSuggestions = DB::table('product_ingredients')
                ->join('products', 'product_ingredients.product_id', '=', 'products.id')
                ->where('products.is_active', true)
                ->where('ingredient_name', 'like', "%{$query}%")
                ->distinct()
                ->select('ingredient_name')
                ->limit(3)
                ->get()
                ->map(function ($ingredient) {
                    return [
                        'type' => 'ingredient',
                        'text' => $ingredient->ingredient_name,
                        'subtitle' => 'Ingredient',
                        'url' => route('search', ['q' => $ingredient->ingredient_name, 'ingredient_search' => 'include']),
                        'image' => null,
                    ];
                });

            return $suggestions
                ->concat($productSuggestions)
                ->concat($brandSuggestions)
                ->concat($categorySuggestions)
                ->concat($ingredientSuggestions)
                ->take(12);
        });

        return response()->json($suggestions);
    }

    /**
     * Ingredient-based search
     */
    public function ingredients(Request $request)
    {
        $request->validate([
            'ingredients' => 'required|array',
            'ingredients.*' => 'string|max:255',
            'search_type' => 'in:include,exclude',
        ]);

        $ingredients = $request->get('ingredients', []);
        $searchType = $request->get('search_type', 'include');

        $query = Product::active()
            ->with(['brand', 'category', 'images', 'inventory', 'reviews'])
            ->withAvg('reviews', 'rating');

        if ($searchType === 'exclude') {
            // Find products WITHOUT these ingredients
            $query->whereDoesntHave('ingredients', function ($ingredientQuery) use ($ingredients) {
                $ingredientQuery->whereIn('ingredient_name', $ingredients);
            });
        } else {
            // Find products WITH these ingredients
            $query->whereHas('ingredients', function ($ingredientQuery) use ($ingredients) {
                $ingredientQuery->whereIn('ingredient_name', $ingredients);
            });
        }

        // Apply additional filters
        $this->applyFilters($query, $request);

        $products = $query->paginate(20)->withQueryString();

        return view('search.ingredients', [
            'products' => $products,
            'ingredients' => $ingredients,
            'searchType' => $searchType,
            'totalProducts' => $products->total(),
        ]);
    }

    /**
     * Get trending search terms
     */
    public function trending()
    {
        $trendingSearches = Cache::remember('trending_searches', 3600, function () {
            // This would typically come from a search_logs table
            // For now, return some static popular searches
            return [
                'moisturizer',
                'vitamin c serum',
                'sunscreen',
                'cleanser',
                'retinol',
                'hyaluronic acid',
                'niacinamide',
                'face mask',
            ];
        });

        return response()->json($trendingSearches);
    }

    /**
     * Perform the actual search logic
     */
    private function performSearch($query, Request $request)
    {
        $productQuery = Product::active()
            ->with(['brand', 'category', 'images', 'inventory', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Main search logic
        $productQuery->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhere('sku', 'like', "%{$query}%")
              ->orWhereHas('brand', function ($brandQuery) use ($query) {
                  $brandQuery->where('name', 'like', "%{$query}%");
              })
              ->orWhereHas('category', function ($categoryQuery) use ($query) {
                  $categoryQuery->where('name', 'like', "%{$query}%");
              })
              ->orWhereHas('ingredients', function ($ingredientQuery) use ($query) {
                  $ingredientQuery->where('ingredient_name', 'like', "%{$query}%");
              });
        });

        // Apply filters and sorting
        $this->applyFilters($productQuery, $request);
        $this->applySorting($productQuery, $request);

        $products = $productQuery->paginate(20)->withQueryString();

        // Get filter data
        $filterData = $this->getFilterData();

        return [
            'products' => $products,
            'filters' => $filterData,
        ];
    }

    /**
     * Apply filters to product query
     */
    private function applyFilters($query, Request $request)
    {
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Brand filter
        if ($request->filled('brands')) {
            $brands = is_array($request->brands) ? $request->brands : [$request->brands];
            $query->whereIn('brand_id', $brands);
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('selling_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('selling_price', '<=', $request->max_price);
        }

        // Color filter
        if ($request->filled('colors')) {
            $colors = is_array($request->colors) ? $request->colors : [$request->colors];
            $query->whereHas('colors', function ($colorQuery) use ($colors) {
                $colorQuery->whereIn('colors.id', $colors);
            });
        }

        // Texture filter
        if ($request->filled('textures')) {
            $textures = is_array($request->textures) ? $request->textures : [$request->textures];
            $query->whereIn('texture_id', $textures);
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $query->having('reviews_avg_rating', '>=', $request->min_rating);
        }
    }

    /**
     * Apply sorting to product query
     */
    private function applySorting($query, Request $request)
    {
        $sortBy = $request->get('sort', 'relevance');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('selling_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('selling_price', 'desc');
                break;
            case 'rating':
                $query->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'newest':
                $query->latest('created_at');
                break;
            case 'popularity':
                $query->orderBy('views_count', 'desc');
                break;
            case 'relevance':
            default:
                // For relevance, we could implement a scoring system
                // For now, just use name match priority
                $query->orderByRaw("CASE 
                    WHEN name LIKE ? THEN 1 
                    WHEN description LIKE ? THEN 2 
                    ELSE 3 
                END", [
                    '%' . request('q') . '%',
                    '%' . request('q') . '%'
                ]);
                break;
        }
    }

    /**
     * Get filter data for search results
     */
    private function getFilterData()
    {
        return Cache::remember('search_filters', 600, function () {
            return [
                'categories' => Category::active()
                    ->whereHas('products', function ($query) {
                        $query->active();
                    })
                    ->withCount('products')
                    ->orderBy('name')
                    ->get(),

                'brands' => Brand::active()
                    ->whereHas('products', function ($query) {
                        $query->active();
                    })
                    ->withCount('products')
                    ->orderBy('name')
                    ->get(),

                'priceRange' => [
                    'min' => Product::active()->min('selling_price') ?? 0,
                    'max' => Product::active()->max('selling_price') ?? 1000,
                ],
            ];
        });
    }

    /**
     * Get search suggestions based on current query
     */
    private function getSearchSuggestions($query)
    {
        if (strlen($query) < 3) {
            return [];
        }

        return Cache::remember('related_searches_' . md5($query), 600, function () use ($query) {
            // Find related products and extract keywords
            $relatedProducts = Product::active()
                ->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->select('name', 'description')
                ->limit(10)
                ->get();

            // Simple keyword extraction (in a real app, you might use more sophisticated NLP)
            $keywords = collect();
            foreach ($relatedProducts as $product) {
                $words = str_word_count($product->name . ' ' . $product->description, 1);
                $keywords = $keywords->merge($words);
            }

            return $keywords
                ->map('strtolower')
                ->filter(function ($word) use ($query) {
                    return strlen($word) > 3 && stripos($word, $query) === false;
                })
                ->unique()
                ->values()
                ->take(5);
        });
    }

    /**
     * Log search for analytics
     */
    private function logSearch($query, $resultCount)
    {
        // In a production app, you might store this in a database
        // For now, we'll just use logs
        Log::info('Search performed', [
            'query' => $query,
            'result_count' => $resultCount,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }
}