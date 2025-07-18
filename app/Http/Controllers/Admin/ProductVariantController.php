<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\ProductVariantRequest;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()
            ->with('inventory')
            ->orderBy('name')
            ->get();

        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('admin.products.variants.create', compact('product'));
    }

    public function store(ProductVariantRequest $request, Product $product)
    {
        $request->validate([
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'scent' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:150|unique:product_variants,sku',
        ]);

        // Ensure at least one attribute is provided
        if (!$request->size && !$request->color && !$request->scent) {
            return back()->withErrors(['variant' => 'Please provide at least one variant attribute.']);
        }

        DB::beginTransaction();

        try {
            // Generate variant name
            $nameParts = array_filter([
                $request->size,
                $request->color,
                $request->scent
            ]);
            $variantName = implode(' - ', $nameParts);

            // Generate SKU if not provided
            $sku = $request->sku ?: $this->generateSku($product, $request);

            // Create variant
            $variant = $product->variants()->create([
                'size' => $request->size,
                'color' => $request->color,
                'scent' => $request->scent,
                'name' => $variantName,
                'sku' => $sku,
                'price' => $request->price,
                'cost_price' => $request->cost_price,
                'discount_price' => $request->discount_price,
                'is_active' => true,
            ]);

            // Create inventory record
            Inventory::create([
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'current_stock' => 0,
                'reserved_stock' => 0,
                'low_stock_threshold' => 10,
            ]);

            // Update product has_variants flag only if it was initially false
            if (!$product->has_variants && $product->variants()->count() > 1) {
                $product->update(['has_variants' => true]);
            }

            DB::commit();

            return redirect()->route('admin.products.variants.index', $product)
                ->with('success', 'Variant created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->withErrors(['error' => 'Error creating variant: ' . $e->getMessage()]);
        }
    }

    public function edit(ProductVariant $variant)
    {
        return view('admin.products.variants.edit', compact('variant'));
    }

    public function update(ProductVariantRequest $request, ProductVariant $variant)
    {
        $request->validate([
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'scent' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:150|unique:product_variants,sku,' . $variant->id,
        ]);

        DB::beginTransaction();

        try {
            // Generate variant name
            $nameParts = array_filter([
                $request->size,
                $request->color,
                $request->scent
            ]);
            $variantName = implode(' - ', $nameParts);

            $variant->update([
                'size' => $request->size,
                'color' => $request->color,
                'scent' => $request->scent,
                'name' => $variantName,
                'sku' => $request->sku,
                'price' => $request->price,
                'cost_price' => $request->cost_price,
                'discount_price' => $request->discount_price,
            ]);

            DB::commit();

            return redirect()->route('admin.products.variants.index', $variant->product)
                ->with('success', 'Variant updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->withErrors(['error' => 'Error updating variant: ' . $e->getMessage()]);
        }
    }

    public function show(ProductVariant $variant)
{
    $variant->load(['product', 'inventory', 'orderItems']);
    
    // Get stock movement history
    $movements = $variant->inventory
        ? $variant->inventory->movements()->latest()->paginate(20)
        : collect();
    
    return view('admin.products.variants.show', compact('variant', 'movements'));
}

    public function toggleStatus(ProductVariant $variant)
    {
        $variant->update(['is_active' => !$variant->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Variant status updated.',
            'is_active' => $variant->is_active
        ]);
    }

    public function destroy(ProductVariant $variant)
    {
        if ($variant->product->variants()->count() === 1) {
            return back()->withErrors(['error' => 'Cannot delete the last variant.']);
        }

        if ($variant->orderItems()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete variant that has been ordered.']);
        }

        DB::beginTransaction();

        try {
            $product = $variant->product;
            $variant->delete();
            
            // Check if we should update has_variants flag
            if ($product->variants()->count() === 1) {
                // Only one variant left (should be the standard one)
                $product->update(['has_variants' => false]);
            }
            DB::commit();

            return redirect()->route('admin.products.variants.index', $product)
                ->with('success', 'Variant deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'Error deleting variant: ' . $e->getMessage()]);
        }
    }

    /**
 * Generate SKU for variant
 */
private function generateSku(Product $product, Request $request)
{
    $parts = [$product->sku];
    
    if ($request->size) {
        $parts[] = Str::slug($request->size);
    }
    if ($request->color) {
        $parts[] = Str::slug($request->color);
    }
    if ($request->scent) {
        $parts[] = Str::slug($request->scent);
    }
    
    return implode('-', $parts);
}


    
}