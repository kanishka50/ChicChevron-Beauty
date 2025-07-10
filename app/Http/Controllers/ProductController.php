<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Texture;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display product catalog with filtering and sorting
     */
    public function index(Request $request)
    {
        $query = Product::active()
            ->with(['brand', 'category', 'images', 'inventory', 'colors', 'texture']);

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
        ]);
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        // Load necessary relationships - FIXED: Removed 'ingredients.ingredient'
        $product->load([
            'brand',
            'category',
            'productType',
            'texture',
            'images' => function ($query) {
                $query->orderBy('sort_order');
            },
            'ingredients', // Just load ingredients, not ingredients.ingredient
            'colors',
            'variants' => function ($query) {
                $query->where('is_active', true)->orderBy('variant_type')->orderBy('variant_value');
            },
            'variantCombinations' => function ($query) {
                $query->with(['sizeVariant', 'colorVariant', 'scentVariant', 'inventory']);
            },
            'reviews' => function ($query) {
                $query->where('is_approved', true)->latest();
            },
            'reviews.user'
        ]);

        // Get available variants grouped by type
        $availableVariants = null;
        if ($product->has_variants) {
            $availableVariants = [
                'sizes' => $product->variants()->ofType('size')->active()->get(),
                'colors' => $product->variants()->ofType('color')->active()->get(),
                'scents' => $product->variants()->ofType('scent')->active()->get(),
            ];
        }

        // Calculate price range for products with variants
        $priceRange = null;
        if ($product->has_variants && $product->variantCombinations->isNotEmpty()) {
            $prices = $product->variantCombinations->pluck('combination_price');
            $priceRange = [
                'min' => $prices->min(),
                'max' => $prices->max()
            ];
        }

        // Get related products (same category, excluding current product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['brand', 'images'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('products.show', compact(
            'product',
            'availableVariants',
            'priceRange',
            'relatedProducts'
        ));
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
            ->with(['brand', 'category', 'images', 'inventory']);

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
     * Get variant details for AJAX requests
     */
    public function getVariantDetails(Request $request, Product $product)
    {
        $sizeId = $request->get('size_id');
        $colorId = $request->get('color_id');
        $scentId = $request->get('scent_id');

        // Handle null values properly
        if ($sizeId === 'null') $sizeId = null;
        if ($colorId === 'null') $colorId = null;
        if ($scentId === 'null') $scentId = null;

        // Find the specific variant combination
        $combination = $product->variantCombinations()
            ->where(function ($query) use ($sizeId, $colorId, $scentId) {
                if ($sizeId) $query->where('size_variant_id', $sizeId);
                else $query->whereNull('size_variant_id');
                
                if ($colorId) $query->where('color_variant_id', $colorId);
                else $query->whereNull('color_variant_id');
                
                if ($scentId) $query->where('scent_variant_id', $scentId);
                else $query->whereNull('scent_variant_id');
            })
            ->with(['inventory', 'sizeVariant', 'colorVariant', 'scentVariant'])
            ->first();

        if (!$combination) {
            return response()->json([
                'success' => false,
                'message' => 'Variant combination not found'
            ], 404);
        }

        $stockLevel = $combination->inventory 
            ? $combination->inventory->current_stock - $combination->inventory->reserved_stock 
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'combination_id' => $combination->id,
                'sku' => $combination->combination_sku,
                'price' => number_format($combination->combination_price, 2),
                'stock_level' => $stockLevel,
                'in_stock' => $stockLevel > 0,
                'variant_details' => $combination->variant_details ?? 'No variant details',
            ]
        ]);
    }

    /**
     * Apply filters to the product query
     */
    private function applyFilters($query, Request $request)
    {
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Brand filter (multiple)
        if ($request->filled('brands')) {
            $brands = is_array($request->brands) ? $request->brands : [$request->brands];
            $query->whereIn('brand_id', $brands);
        }

        // Price range filter
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

        // Product type filter
        if ($request->filled('product_types')) {
            $types = is_array($request->product_types) ? $request->product_types : [$request->product_types];
            $query->whereIn('product_type_id', $types);
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $query->whereHas('reviews', function ($reviewQuery) use ($request) {
                $reviewQuery->havingRaw('AVG(rating) >= ?', [$request->min_rating]);
            });
        }

        // Stock status filter
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->inStock();
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->outOfStock();
            }
        }
    }

    /**
     * Apply sorting to the product query
     */
    private function applySorting($query, Request $request)
    {
        $sortBy = $request->get('sort', 'newest');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('selling_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('selling_price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('reviews_avg_rating', 'desc');
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

    private function getFilterData(Request $request)
    {
        return Cache::remember('product_filters_' . md5(serialize($request->except(['page']))), 300, function () {
            return [
                'categories' => Category::active()
                    ->ordered()
                    ->whereHas('products', function ($query) {
                        $query->active()->inStock();
                    })
                    ->get()
                    ->map(function ($category) {
                        // Count products manually to avoid GROUP BY issues
                        $category->products_count = Product::active()
                            ->inStock()
                            ->where('category_id', $category->id)
                            ->count();
                        return $category;
                    }),

                'brands' => Brand::active()
                    ->whereHas('products', function ($query) {
                        $query->active()->inStock();
                    })
                    ->orderBy('name')
                    ->get()
                    ->map(function ($brand) {
                        // Count products manually to avoid GROUP BY issues
                        $brand->products_count = Product::active()
                            ->inStock()
                            ->where('brand_id', $brand->id)
                            ->count();
                        return $brand;
                    }),

                'colors' => Color::whereHas('products', function ($query) {
                        $query->active()->inStock();
                    })
                    ->orderBy('name')
                    ->get(),

                'textures' => Texture::whereHas('products', function ($query) {
                        $query->active()->inStock();
                    })
                    ->orderBy('name')
                    ->get(),

                'productTypes' => ProductType::whereHas('products', function ($query) {
                        $query->active()->inStock();
                    })
                    ->orderBy('name')
                    ->get(),

                'priceRange' => [
                    'min' => Product::active()->inStock()->min('selling_price') ?? 0,
                    'max' => Product::active()->inStock()->max('selling_price') ?? 1000,
                ],

                'ingredients' => DB::table('product_ingredients')
                    ->join('products', 'product_ingredients.product_id', '=', 'products.id')
                    ->where('products.is_active', true)
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('inventory')
                            ->whereRaw('inventory.product_id = products.id')
                            ->whereRaw('(inventory.current_stock - inventory.reserved_stock) > 0');
                    })
                    ->distinct()
                    ->pluck('ingredient_name')
                    ->sort()
                    ->values(),
            ];
        });
    }

    /**
     * Get available variants for a product
     */
    private function getAvailableVariants(Product $product)
    {
        if (!$product->has_variants) {
            return [];
        }

        return [
            'sizes' => $product->variants()->where('variant_type', 'size')->get(),
            'colors' => $product->variants()->where('variant_type', 'color')->get(),
            'scents' => $product->variants()->where('variant_type', 'scent')->get(),
        ];
    }

    /**
     * Get price range for a product considering variants
     */
    private function getPriceRange(Product $product)
    {
        if (!$product->has_variants || $product->variantCombinations->isEmpty()) {
            return [
                'min' => $product->display_price,
                'max' => $product->display_price,
            ];
        }

        $prices = $product->variantCombinations->pluck('combination_price');

        return [
            'min' => $prices->min(),
            'max' => $prices->max(),
        ];
    }
}