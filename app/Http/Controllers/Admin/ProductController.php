<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductType;
use App\Models\Texture;
use App\Models\Color;
use App\Models\ProductImage;
use App\Models\ProductIngredient;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ProductVariant;      // ADD THIS
use App\Models\VariantCombination;  // ADD THIS

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category'])
        ->withCount(['reviews', 'variantCombinations'])
        ->addSelect([
            'stock_level' => VariantCombination::selectRaw('SUM(inventory.current_stock - inventory.reserved_stock)')
                ->join('inventory', 'variant_combinations.id', '=', 'inventory.variant_combination_id')
                ->whereColumn('variant_combinations.product_id', 'products.id')
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
    $productTypes = ProductType::orderBy('name')->get();
    $textures = Texture::orderBy('name')->get();
    // REMOVED: $colors = Color::all();
    
    return view('admin.products.create', compact(
        'brands', 
        'categories', 
        'productTypes', 
        'textures'
        // REMOVED: 'colors'
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
        
        // Create default "Standard" variant
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'variant_type' => 'size',
            'variant_value' => 'Standard',
            'sku_suffix' => 'STD',
            'is_active' => true,
        ]);
        
        // Create variant combination with zero price (to be set in variant management)
        $combination = VariantCombination::create([
            'product_id' => $product->id,
            'size_variant_id' => $variant->id,
            'combination_sku' => $product->sku . '-STD',
            'combination_price' => 0,
            'combination_cost_price' => 0,
        ]);
        
        // Create inventory record
        Inventory::create([
            'product_id' => $product->id,
            'variant_combination_id' => $combination->id,
            'current_stock' => 0,
            'low_stock_threshold' => 10
        ]);
        
        DB::commit();
        
        // Redirect to variant management to set prices
        return redirect()->route('admin.products.variants', $product)
            ->with('success', 'Product created! Now set variant prices and stock.');
            
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
            'category',
            'productType',
            'texture',
            'images',
            'ingredients',
            'colors',
            'variants',
            'inventory',
            'reviews'
        ]);
        
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
{
    $brands = Brand::active()->orderBy('name')->get();
    $categories = Category::active()->ordered()->get();
    $productTypes = ProductType::orderBy('name')->get();
    $textures = Texture::orderBy('name')->get();
    // REMOVE: $colors = Color::all();
    
    $product->load(['images', 'ingredients']); // REMOVED 'colors'
    
    return view('admin.products.edit', compact(
        'product',
        'brands', 
        'categories', 
        'productTypes', 
        'textures'
        // REMOVED: 'colors'
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
        // REMOVED: has_variants handling
        
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
        
        // REMOVED: Color syncing
        
        DB::commit();
        
        return redirect()->route('admin.products.index')
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
        
        return response()->json([
            'success' => true,
            'is_active' => $product->is_active
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
}