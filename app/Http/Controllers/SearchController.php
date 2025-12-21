<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
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
    $query = Product::active()
        ->with(['brand', 'category', 'variants.inventory']);

    // Check if this is a search request
    $searchQuery = $request->get('q');
    $isSearchResult = !empty($searchQuery);
    
    if ($isSearchResult) {
        // Apply search logic
        $this->applySearch($query, $searchQuery);
    }

    // Apply filters (works for both search and regular browsing)
    $this->applyFilters($query, $request);

    // Apply sorting
    $this->applySorting($query, $request);

    // Paginate results
    $products = $query->paginate(20)->withQueryString();

    // Load reviews with proper aggregation
    $products->getCollection()->load([
        'reviews' => function($query) {
            $query->where('is_approved', true);
        }
    ]);

    // Calculate averages manually to avoid GROUP BY issues
    $products->getCollection()->each(function ($product) {
        $product->reviews_avg_rating = $product->reviews->avg('rating') ?: 0;
        $product->reviews_count = $product->reviews->count();
    });

    // Get filter data
    $filterData = $this->getFilterData($request);

    return view('products.index', [
        'products' => $products,
        'filters' => $filterData,
        'currentFilters' => $request->all(),
        'totalProducts' => $products->total(),
        'searchQuery' => $searchQuery,  // Add this
        'isSearchResult' => $isSearchResult  // Add this
    ]);
}


/**
 * Apply search logic to the query
 * NEW METHOD - Add this after the index method
 */
private function applySearch($query, $searchTerm)
{
    $query->where(function ($q) use ($searchTerm) {
        // Search in product name (highest priority)
        $q->where('name', 'LIKE', "%{$searchTerm}%")
          // Search in description
          ->orWhere('description', 'LIKE', "%{$searchTerm}%")
          // Search in SKU
          ->orWhere('sku', 'LIKE', "%{$searchTerm}%")
          // Search in brand name
          ->orWhereHas('brand', function ($brandQuery) use ($searchTerm) {
              $brandQuery->where('name', 'LIKE', "%{$searchTerm}%");
          })
          // Search in category name
          ->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
              $categoryQuery->where('name', 'LIKE', "%{$searchTerm}%");
          })
          // Search in ingredients (stored as TEXT column)
          ->orWhere('ingredients', 'LIKE', "%{$searchTerm}%")
          // Search in variant SKUs
          ->orWhereHas('variants', function ($variantQuery) use ($searchTerm) {
              $variantQuery->where('sku', 'LIKE', "%{$searchTerm}%")
                          ->where('is_active', true);
          });
    });

    // Add relevance scoring for better search results
    $query->selectRaw("*, 
        CASE 
            WHEN name LIKE ? THEN 100
            WHEN name LIKE ? THEN 50
            WHEN description LIKE ? THEN 20
            WHEN sku LIKE ? THEN 30
            ELSE 10
        END as search_relevance", 
        [
            $searchTerm . '%',  // Starts with (highest priority)
            '%' . $searchTerm . '%',  // Contains
            '%' . $searchTerm . '%',  // In description
            '%' . $searchTerm . '%'   // In SKU
        ]
    )->orderBy('search_relevance', 'DESC');
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

            // Product name suggestions with price from variants
            $productSuggestions = Product::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->select('id', 'name', 'slug', 'main_image')
                ->with(['brand:id,name', 'variants' => function($q) {
                    $q->where('is_active', true)
                      ->select('product_id', 'price', 'discount_price')
                      ->orderBy('price', 'asc')
                      ->limit(1);
                }])
                ->limit(5)
                ->get()
                ->map(function ($product) {
                    $variant = $product->variants->first();
                    $price = $variant ? ($variant->discount_price ?? $variant->price) : null;
                    
                    return [
                        'type' => 'product',
                        'text' => $product->name,
                        'subtitle' => $product->brand->name ?? '',
                        'price' => $price ? 'Rs. ' . number_format($price, 2) : '',
                        'url' => route('products.show', $product->slug),
                        'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                    ];
                });

            // Brand suggestions
            $brandSuggestions = Brand::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->whereHas('products', function ($q) {
                    $q->where('is_active', true);
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
            $categorySuggestions = Category::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->whereHas('products', function ($q) {
                    $q->where('is_active', true);
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

            // Ingredient suggestions removed - ingredients now stored as TEXT column

            return $suggestions
                ->concat($productSuggestions)
                ->concat($brandSuggestions)
                ->concat($categorySuggestions)
                ->take(12);
        });

        return response()->json($suggestions);
    }

    /**
     * Get trending search terms
     */
    public function trending()
    {
        $trendingSearches = Cache::remember('trending_searches', 3600, function () {
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
        $productQuery = Product::where('is_active', true)
            ->with(['brand', 'category', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Add subquery to get minimum variant price for each product
        $productQuery->addSelect([
            'min_price' => ProductVariant::select('price')
                ->whereColumn('product_id', 'products.id')
                ->where('is_active', true)
                ->orderBy('price', 'asc')
                ->limit(1),
            'min_discount_price' => ProductVariant::select('discount_price')
                ->whereColumn('product_id', 'products.id')
                ->where('is_active', true)
                ->whereNotNull('discount_price')
                ->orderBy('discount_price', 'asc')
                ->limit(1)
        ]);

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
              ->orWhere('ingredients', 'like', "%{$query}%");
        });

        // Apply filters and sorting
        $this->applyFilters($productQuery, $request);
        $this->applySorting($productQuery, $request);

        $products = $productQuery->paginate(20)->withQueryString();

        // Load variants for each product to display price ranges
        $products->load(['variants' => function($query) {
            $query->where('is_active', true)
                  ->select('product_id', 'price', 'discount_price', 'size', 'color', 'scent')
                  ->orderBy('price', 'asc');
        }]);

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

        // Price range filter based on variant prices
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('variants', function($variantQuery) use ($request) {
                $variantQuery->where('is_active', true);
                
                if ($request->filled('min_price')) {
                    $variantQuery->where(function($q) use ($request) {
                        $q->where('price', '>=', $request->min_price)
                          ->orWhere('discount_price', '>=', $request->min_price);
                    });
                }
                
                if ($request->filled('max_price')) {
                    $variantQuery->where(function($q) use ($request) {
                        $q->where('price', '<=', $request->max_price)
                          ->orWhere(function($subQ) use ($request) {
                              $subQ->whereNotNull('discount_price')
                                   ->where('discount_price', '<=', $request->max_price);
                          });
                    });
                }
            });
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $query->having('reviews_avg_rating', '>=', $request->min_rating);
        }

        // Size filter (from variants)
        if ($request->filled('sizes')) {
            $sizes = is_array($request->sizes) ? $request->sizes : [$request->sizes];
            $query->whereHas('variants', function($variantQuery) use ($sizes) {
                $variantQuery->whereIn('size', $sizes)
                            ->where('is_active', true);
            });
        }

        // Scent filter (from variants)
        if ($request->filled('scents')) {
            $scents = is_array($request->scents) ? $request->scents : [$request->scents];
            $query->whereHas('variants', function($variantQuery) use ($scents) {
                $variantQuery->whereIn('scent', $scents)
                            ->where('is_active', true);
            });
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
                // Sort by minimum variant price
                $query->orderByRaw('COALESCE(min_discount_price, min_price) ASC');
                break;
            case 'price_high':
                // Sort by maximum variant price
                $query->orderByRaw('COALESCE(min_discount_price, min_price) DESC');
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
                // For relevance, prioritize name matches
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
            // Get categories
            $categories = Category::where('is_active', true)
                ->whereHas('products', function ($query) {
                    $query->where('is_active', true);
                })
                ->withCount(['products' => function ($query) {
                    $query->where('is_active', true);
                }])
                ->orderBy('name')
                ->get();

            // Get brands
            $brands = Brand::where('is_active', true)
                ->whereHas('products', function ($query) {
                    $query->where('is_active', true);
                })
                ->withCount(['products' => function ($query) {
                    $query->where('is_active', true);
                }])
                ->orderBy('name')
                ->get();

            // Get price range from variants
            $minPrice = ProductVariant::whereHas('product', function($q) {
                $q->where('is_active', true);
            })
            ->where('is_active', true)
            ->min(DB::raw('COALESCE(discount_price, price)')) ?? 0;
            
            $maxPrice = ProductVariant::whereHas('product', function($q) {
                $q->where('is_active', true);
            })
            ->where('is_active', true)
            ->max('price') ?? 10000;

            // Get available sizes from variants
            $sizes = ProductVariant::whereHas('product', function($q) {
                    $q->where('is_active', true);
                })
                ->where('is_active', true)
                ->whereNotNull('size')
                ->distinct()
                ->pluck('size');

            // Get available scents from variants
            $scents = ProductVariant::whereHas('product', function($q) {
                    $q->where('is_active', true);
                })
                ->where('is_active', true)
                ->whereNotNull('scent')
                ->distinct()
                ->pluck('scent');

            return [
                'categories' => $categories,
                'brands' => $brands,
                'priceRange' => [
                    'min' => $minPrice,
                    'max' => $maxPrice,
                ],
                'sizes' => $sizes,
                'scents' => $scents,
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
            $relatedProducts = Product::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->select('name', 'description')
                ->limit(10)
                ->get();

            // Simple keyword extraction
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
                ->take(5)
                ->toArray();
        });
    }

    /**
     * Log search for analytics
     */
    private function logSearch($query, $resultCount)
    {
        Log::info('Search performed', [
            'query' => $query,
            'result_count' => $resultCount,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
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

        $query = Product::where('is_active', true)
            ->with(['brand', 'category', 'reviews', 'variants'])
            ->withAvg('reviews', 'rating');

        // Add variant price subqueries
        $query->addSelect([
            'min_price' => ProductVariant::select('price')
                ->whereColumn('product_id', 'products.id')
                ->where('is_active', true)
                ->orderBy('price', 'asc')
                ->limit(1),
            'min_discount_price' => ProductVariant::select('discount_price')
                ->whereColumn('product_id', 'products.id')
                ->where('is_active', true)
                ->whereNotNull('discount_price')
                ->orderBy('discount_price', 'asc')
                ->limit(1)
        ]);

        if ($searchType === 'exclude') {
            // Find products WITHOUT these ingredients (search in TEXT column)
            foreach ($ingredients as $ingredient) {
                $query->where('ingredients', 'NOT LIKE', "%{$ingredient}%");
            }
        } else {
            // Find products WITH these ingredients (search in TEXT column)
            $query->where(function ($q) use ($ingredients) {
                foreach ($ingredients as $ingredient) {
                    $q->orWhere('ingredients', 'LIKE', "%{$ingredient}%");
                }
            });
        }

        // Apply additional filters
        $this->applyFilters($query, $request);

        $products = $query->paginate(20)->withQueryString();

        // Check if view exists
        if (view()->exists('search.ingredients')) {
            return view('search.ingredients', [
                'products' => $products,
                'ingredients' => $ingredients,
                'searchType' => $searchType,
                'totalProducts' => $products->total(),
            ]);
        } else {
            // Fall back to search results or products index
            return view('search.results', [
                'query' => implode(', ', $ingredients),
                'products' => $products,
                'filters' => $this->getFilterData(),
                'currentFilters' => $request->all(),
                'totalProducts' => $products->total(),
                'searchSuggestions' => [],
            ]);
        }
    }
}