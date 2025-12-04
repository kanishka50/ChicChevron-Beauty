<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductIngredient;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'variants'])
            ->withCount(['reviews', 'variants'])
            ->addSelect([
            'stock_level' => Inventory::selectRaw('SUM(current_stock - reserved_stock)')
                ->whereColumn('product_id', 'products.id')
            ]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('brand', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Filter by stock status
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

        $products = $query->latest()->paginate(20);
        
        // Get filter data
        $categories = Category::active()->ordered()->get();
        $brands = Brand::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $brands = Brand::active()->orderBy('name')->get();
        $categories = Category::active()->ordered()->get();

        return view('admin.products.create', compact(
            'brands',
            'categories'
        ));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        
        try {
            // Create product
            $data = $request->validated();
            
            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $data['main_image'] = $request->file('main_image')->store('products', 'public');
            }
            
            // Generate unique SKU if not provided
            if (empty($data['sku'])) {
                $data['sku'] = $this->generateSku($data['name']);
            }
            
            // Set is_active
            $data['is_active'] = $request->has('is_active');
            $data['has_variants'] = $request->has('has_variants'); // Get from checkbox
            
            $product = Product::create($data);
            
            // Handle additional images
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'sort_order' => $index + 1
                    ]);
                }
            }
            
            // Handle ingredients
            if ($request->filled('ingredients')) {
                foreach ($request->ingredients as $ingredient) {
                    if (!empty($ingredient)) {
                        ProductIngredient::create([
                            'product_id' => $product->id,
                            'ingredient_name' => $ingredient
                        ]);
                    }
                }
            }
            
            // Only create default variant if product doesn't have variants
        if (!$product->has_variants) {
            $variant = $product->variants()->create([
                'name' => 'Standard',
                'sku' => $product->sku,
                'price' => 0,
                'cost_price' => 0,
                'is_active' => true,
            ]);
            
            // Create inventory record
            Inventory::create([
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'current_stock' => 0,
                'reserved_stock' => 0,
                'low_stock_threshold' => 10
            ]);
        }
            
            DB::commit();
            
            // Redirect to variant management to set prices
            return redirect()->route('admin.products.variants.index', $product)
                ->with('success', 'Product created successfully! Now set variant prices and stock.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load([
            'brand',
            'category.mainCategory',
            'images',
            'ingredients',
            'variants.inventory',
            'reviews.user'
        ]);
        
        // Calculate total stock across all variants
        $totalStock = $product->variants->sum(function ($variant) {
            return $variant->inventory ? $variant->inventory->available_stock : 0;
        });
        
        return view('admin.products.show', compact('product', 'totalStock'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $brands = Brand::active()->orderBy('name')->get();
        $categories = Category::active()->ordered()->get();

        $product->load(['images', 'ingredients']);

        return view('admin.products.edit', compact(
            'product',
            'brands',
            'categories'
        ));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        
        try {
            $data = $request->validated();
            
            // Handle main image upload
            if ($request->hasFile('main_image')) {
                // Delete old image
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $data['main_image'] = $request->file('main_image')->store('products', 'public');
            }
            
            // Set boolean fields
            $data['is_active'] = $request->has('is_active');
            // Note: has_variants is managed automatically based on variant count
            
            $product->update($data);
            
            // Handle additional images
            if ($request->hasFile('additional_images')) {
                $maxOrder = $product->images()->max('sort_order') ?? 0;
                foreach ($request->file('additional_images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'sort_order' => $maxOrder + $index + 1
                    ]);
                }
            }
            
            // Handle ingredients (replace all)
            if ($request->has('ingredients')) {
                $product->ingredients()->delete();
                foreach ($request->ingredients as $ingredient) {
                    if (!empty($ingredient)) {
                        ProductIngredient::create([
                            'product_id' => $product->id,
                            'ingredient_name' => $ingredient
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.show', $product)
                ->with('success', 'Product updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        
        try {
            // Check for existing orders
            if ($product->orderItems()->exists()) {
                return redirect()->route('admin.products.index')
                    ->with('error', 'Cannot delete product with existing orders.');
            }
            
            // Delete images
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // Note: Variants and inventory will be cascade deleted
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        // Also deactivate all variants if product is deactivated
        if (!$product->is_active) {
            $product->variants()->update(['is_active' => false]);
        }
        
        return response()->json([
            'success' => true,
            'is_active' => $product->is_active,
            'message' => $product->is_active ? 'Product activated.' : 'Product and all variants deactivated.'
        ]);
    }
    
    /**
     * Delete product image
     */
    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Generate unique SKU
     */
    private function generateSku($productName)
    {
        $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 3));
        $number = Product::where('sku', 'like', $base . '%')->count() + 1;
        return $base . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Duplicate a product
     */
    public function duplicate(Product $product)
    {
        DB::beginTransaction();
        
        try {
            // Clone the product
            $newProduct = $product->replicate();
            $newProduct->name = $product->name . ' (Copy)';
            $newProduct->sku = $this->generateSku($newProduct->name);
            $newProduct->is_active = false;
            $newProduct->views_count = 0;
            $newProduct->average_rating = 0;
            $newProduct->reviews_count = 0;
            $newProduct->save();
            
            // Clone ingredients
            foreach ($product->ingredients as $ingredient) {
                $newProduct->ingredients()->create([
                    'ingredient_name' => $ingredient->ingredient_name
                ]);
            }
            
            // Clone variants
            foreach ($product->variants as $variant) {
                $newVariant = $variant->replicate();
                $newVariant->product_id = $newProduct->id;
                $newVariant->sku = $newProduct->sku . '-' . Str::random(4);
                $newVariant->save();
                
                // Create inventory for new variant
                Inventory::create([
                    'product_id' => $newProduct->id,
                    'product_variant_id' => $newVariant->id,
                    'current_stock' => 0,
                    'reserved_stock' => 0,
                    'low_stock_threshold' => $variant->inventory->low_stock_threshold ?? 10
                ]);
            }
            
            // Update has_variants flag
            $newProduct->update(['has_variants' => $newProduct->variants()->count() > 1]);
            
            DB::commit();
            
            return redirect()->route('admin.products.edit', $newProduct)
                ->with('success', 'Product duplicated successfully. Please update the details.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error duplicating product: ' . $e->getMessage());
        }
    }
}