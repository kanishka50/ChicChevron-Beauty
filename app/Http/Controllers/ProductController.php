<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Texture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\MainCategory;

class ProductController extends Controller
{
    /**
     * Display product catalog with filtering and sorting
     */
   /**
 * Display product catalog with filtering and sorting
 * Also handles search functionality
 */
public function index(Request $request)
{
    $query = Product::active()
        ->with(['brand', 'category', 'images', 'variants.inventory', 'colors', 'texture']);

    // Check if this is a search request
    $searchQuery = $request->get('q', '');
    $isSearchResult = !empty($searchQuery);
    
    if ($isSearchResult) {
        // Apply search logic
        $query->where(function ($q) use ($searchQuery) {
            $q->where('name', 'LIKE', "%{$searchQuery}%")
              ->orWhere('description', 'LIKE', "%{$searchQuery}%")
              ->orWhere('sku', 'LIKE', "%{$searchQuery}%")
              ->orWhereHas('brand', function ($brandQuery) use ($searchQuery) {
                  $brandQuery->where('name', 'LIKE', "%{$searchQuery}%");
              })
              ->orWhereHas('category', function ($categoryQuery) use ($searchQuery) {
                  $categoryQuery->where('name', 'LIKE', "%{$searchQuery}%");
              })
              ->orWhereHas('ingredients', function ($ingredientQuery) use ($searchQuery) {
                  $ingredientQuery->where('ingredient_name', 'LIKE', "%{$searchQuery}%");
              });
        });
    }

    // Apply filters
    $this->applyFilters($query, $request);

    // Apply sorting
    $this->applySorting($query, $request);

    // Paginate results FIRST
    $products = $query->paginate(20)->withQueryString();

    // THEN load reviews with proper aggregation
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
        'searchQuery' => $searchQuery,        // ADD THIS
        'isSearchResult' => $isSearchResult   // ADD THIS
    ]);
}

    /**
     * Display the specified product
     */
    public function show(Product $product)
{
    // Load necessary relationships 
    $product->load([
        'brand',
        'category.mainCategory',  // Include main category
        'texture',
        'images' => function ($query) {
            $query->orderBy('sort_order');
        },
        'ingredients',
        'colors',
        'variants' => function ($query) {
            $query->where('is_active', true)->with('inventory');
        },
        'reviews' => function ($query) {
            $query->where('is_approved', true)->latest();
        },
        'reviews.user'
    ]);

    // Get related products (same category, excluding current product)
    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('is_active', true)
        ->with(['brand', 'images', 'variants'])
        ->inRandomOrder()
        ->limit(4)
        ->get();

    return view('products.show', compact(
        'product',
        'relatedProducts'
    ));
}



/**
 * Display products for a specific category
 */
public function categoryProducts(Request $request, Category $category)
{
    $query = Product::active()
        ->where('category_id', $category->id)
        ->with(['brand', 'images', 'variants.inventory', 'colors', 'texture']);

    // Apply other filters
    $this->applyFilters($query, $request);
    
    // Apply sorting
    $this->applySorting($query, $request);

    // Paginate results
    $products = $query->paginate(20)->withQueryString();

    // Load reviews
    $products->getCollection()->load([
        'reviews' => function($query) {
            $query->where('is_approved', true);
        }
    ]);

    // Calculate averages
    $products->getCollection()->each(function ($product) {
        $product->reviews_avg_rating = $product->reviews->avg('rating') ?: 0;
        $product->reviews_count = $product->reviews->count();
    });

    // Get filter data
    $filterData = $this->getFilterData($request);

    return view('products.category', [
        'category' => $category,
        'products' => $products,
        'filters' => $filterData,
        'currentFilters' => $request->all(),
        'totalProducts' => $products->total(),
    ]);
}

    /**
     * Search products with advanced filtering
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $ingredientSearch = $request->get('ingredient_search', 'include'); // include or exclude

        if (empty($query)) {
            return redirect()->route('products.index');
        }

        $productQuery = Product::active()
            ->with(['brand', 'category', 'images', 'variants.inventory']);

        // Search in product name, description, and brand
        $productQuery->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhereHas('brand', function ($brandQuery) use ($query) {
                  $brandQuery->where('name', 'like', "%{$query}%");
              });
        });

        // Ingredient-based search
        if ($request->filled('ingredients')) {
            $ingredients = is_array($request->ingredients) 
                ? $request->ingredients 
                : explode(',', $request->ingredients);

            if ($ingredientSearch === 'exclude') {
                // Find products WITHOUT these ingredients
                $productQuery->whereDoesntHave('ingredients', function ($ingredientQuery) use ($ingredients) {
                    $ingredientQuery->whereIn('ingredient_name', $ingredients);
                });
            } else {
                // Find products WITH these ingredients
                $productQuery->whereHas('ingredients', function ($ingredientQuery) use ($ingredients) {
                    $ingredientQuery->whereIn('ingredient_name', $ingredients);
                });
            }
        }

        // Apply other filters
        $this->applyFilters($productQuery, $request);

        // Apply sorting
        $this->applySorting($productQuery, $request);

        // Paginate results
        $products = $productQuery->paginate(20)->withQueryString();

        // Load reviews separately and calculate averages
        $products->getCollection()->load('reviews');
        $products->getCollection()->each(function ($product) {
            $product->reviews_avg_rating = $product->reviews->avg('rating') ?: 0;
            $product->reviews_count = $product->reviews->count();
        });

        // Get filter data
        $filterData = $this->getFilterData($request);

        return view('search.results', [
            'products' => $products,
            'query' => $query,
            'filters' => $filterData,
            'currentFilters' => $request->all(),
            'totalProducts' => $products->total(),
        ]);
    }

    /**
     * Get variant details for AJAX requests - UPDATED FOR NEW SYSTEM
     */
    public function getVariantDetails(Request $request, Product $product)
    {
        $variantId = $request->get('variant_id');

        if (!$variantId) {
            return response()->json([
                'success' => false,
                'message' => 'Variant ID is required'
            ], 400);
        }

        $variant = $product->variants()
            ->where('id', $variantId)
            ->where('is_active', true)
            ->with('inventory')
            ->first();

        if (!$variant) {
            return response()->json([
                'success' => false,
                'message' => 'Variant not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'variant_id' => $variant->id,
                'sku' => $variant->sku,
                'price' => number_format($variant->price, 2),
                'stock_level' => $variant->available_stock,
                'in_stock' => $variant->available_stock > 0,
                'variant_details' => $variant->display_name,
            ]
        ]);
    }

    /**
     * Apply filters to the product query
     */
    private function applyFilters($query, Request $request)
{
    // Main category filter (NEW)
    if ($request->filled('main_category')) {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('main_category_id', $request->main_category);
        });
    }
    
    // Category filter
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Brand filter (multiple)
    if ($request->filled('brands')) {
        $brands = is_array($request->brands) ? $request->brands : [$request->brands];
        $query->whereIn('brand_id', $brands);
    }

    // Price range filter - updated to check variant prices
    if ($request->filled('min_price') || $request->filled('max_price')) {
        $query->whereHas('variants', function ($variantQuery) use ($request) {
            if ($request->filled('min_price')) {
                $variantQuery->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $variantQuery->where('price', '<=', $request->max_price);
            }
        });
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

    // REMOVE product_type_id filter since we're removing product types

    // Rating filter
    if ($request->filled('min_rating')) {
        $query->whereHas('reviews', function ($reviewQuery) use ($request) {
            $reviewQuery->where('is_approved', true)
                ->groupBy('product_id')
                ->havingRaw('AVG(rating) >= ?', [$request->min_rating]);
        });
    }

    // Stock status filter - updated for variants
    if ($request->filled('stock_status')) {
        if ($request->stock_status === 'in_stock') {
            $query->whereHas('variants.inventory', function ($q) {
                $q->whereRaw('(current_stock - reserved_stock) > 0');
            });
        } elseif ($request->stock_status === 'out_of_stock') {
            $query->whereDoesntHave('variants.inventory', function ($q) {
                $q->whereRaw('(current_stock - reserved_stock) > 0');
            });
        }
    }
}
    /**
     * Apply sorting to the product query
     */
    /**
 * Apply sorting to the product query
 */
private function applySorting($query, Request $request)
{
    $sortBy = $request->get('sort', 'newest');

    // Import ProductVariant at the top of your controller
    // use App\Models\ProductVariant;

    switch ($sortBy) {
        case 'price_low':
            // Sort by minimum variant price using subquery
            $query->addSelect(['min_variant_price' => \App\Models\ProductVariant::selectRaw('COALESCE(MIN(NULLIF(discount_price, 0)), MIN(price))')
                ->whereColumn('product_id', 'products.id')
                ->where('is_active', true)
            ])->orderBy('min_variant_price', 'asc');
            break;
            
        case 'price_high':
            // Sort by maximum variant price using subquery
            $query->addSelect(['max_variant_price' => \App\Models\ProductVariant::selectRaw('COALESCE(MAX(NULLIF(discount_price, 0)), MAX(price))')
                ->whereColumn('product_id', 'products.id')
                ->where('is_active', true)
            ])->orderBy('max_variant_price', 'desc');
            break;
            
        case 'name_asc':
            $query->orderBy('name', 'asc');
            break;
            
        case 'name_desc':
            $query->orderBy('name', 'desc');
            break;
            
        case 'rating':
            $query->orderBy('average_rating', 'desc');
            break;
            
        case 'popular':
            $query->orderBy('views_count', 'desc');
            break;
            
        case 'newest':
        default:
            $query->latest('created_at');
            break;
    }
}

    /**
     * Get filter data for the sidebar
     */
    private function getFilterData(Request $request)
{
    return Cache::remember('product_filters_' . md5(serialize($request->except(['page']))), 300, function () {
        return [
            // NEW: Main categories
            'mainCategories' => MainCategory::with(['categories' => function($query) {
                $query->active()
                    ->ordered()
                    ->whereHas('products', function ($q) {
                        $q->active();
                    });
            }])->get(),
            
            'categories' => Category::active()
                ->with('mainCategory')  // Include main category
                ->ordered()
                ->whereHas('products', function ($query) {
                    $query->active();
                })
                ->get()
                ->map(function ($category) {
                    $category->products_count = Product::active()
                        ->where('category_id', $category->id)
                        ->count();
                    return $category;
                }),

            'brands' => Brand::active()
                ->whereHas('products', function ($query) {
                    $query->active();
                })
                ->orderBy('name')
                ->get()
                ->map(function ($brand) {
                    $brand->products_count = Product::active()
                        ->where('brand_id', $brand->id)
                        ->count();
                    return $brand;
                }),

            'colors' => Color::whereHas('products', function ($query) {
                    $query->active();
                })
                ->orderBy('name')
                ->get(),

            'textures' => Texture::whereHas('products', function ($query) {
                    $query->active();
                })
                ->orderBy('name')
                ->get(),

            

            'priceRange' => [
                'min' => DB::table('product_variants')
                    ->join('products', 'product_variants.product_id', '=', 'products.id')
                    ->where('products.is_active', true)
                    ->where('product_variants.is_active', true)
                    ->min('product_variants.price') ?? 0,
                'max' => DB::table('product_variants')
                    ->join('products', 'product_variants.product_id', '=', 'products.id')
                    ->where('products.is_active', true)
                    ->where('product_variants.is_active', true)
                    ->max('product_variants.price') ?? 10000,
            ],

            'ingredients' => DB::table('product_ingredients')
                ->join('products', 'product_ingredients.product_id', '=', 'products.id')
                ->where('products.is_active', true)
                ->distinct()
                ->pluck('ingredient_name')
                ->sort()
                ->values(),
        ];
    });
}
}