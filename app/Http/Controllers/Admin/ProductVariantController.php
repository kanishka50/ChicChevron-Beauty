<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantCombination;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    /**
     * Display product variants.
     */
    public function index(Product $product)
    {
        if (!$product->has_variants) {
            return redirect()->route('admin.products.edit', $product)
                ->with('error', 'This product does not have variants enabled.');
        }

        $product->load(['variants' => function ($query) {
            $query->orderBy('variant_type')->orderBy('variant_value');
        }, 'variantCombinations.inventory']);

        $sizeVariants = $product->variants()->ofType('size')->active()->get();
        $colorVariants = $product->variants()->ofType('color')->active()->get();
        $scentVariants = $product->variants()->ofType('scent')->active()->get();

        return view('admin.products.variants.index', compact(
            'product',
            'sizeVariants',
            'colorVariants',
            'scentVariants'
        ));
    }

    /**
     * Store a new variant.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'variant_type' => 'required|in:size,color,scent',
            'variant_value' => 'required|string|max:100',
            'sku_suffix' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Check if variant already exists
            $exists = $product->variants()
                ->where('variant_type', $request->variant_type)
                ->where('variant_value', $request->variant_value)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'This variant already exists.'
                ], 422);
            }

            // Create the variant
            $variant = $product->variants()->create([
                'variant_type' => $request->variant_type,
                'variant_value' => $request->variant_value,
                'sku_suffix' => $request->sku_suffix,
                'price' => $request->price,
                'cost_price' => $request->cost_price,
                'is_active' => true,
            ]);

            // Update or create variant combinations
            $this->updateVariantCombinations($product);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Variant created successfully.',
                'variant' => $variant
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating variant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a variant.
     */
    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'variant_value' => 'required|string|max:100',
            'sku_suffix' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            $variant->update($request->only([
                'variant_value',
                'sku_suffix',
                'price',
                'cost_price',
                'is_active'
            ]));

            // Update variant combinations if needed
            if ($request->has('is_active') && !$request->is_active) {
                // Deactivate related combinations
                $this->deactivateRelatedCombinations($variant);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Variant updated successfully.',
                'variant' => $variant
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating variant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a variant.
     */
    public function destroy(ProductVariant $variant)
    {
        DB::beginTransaction();

        try {
            // Check if variant is used in any orders
            $hasOrders = DB::table('order_items')
                ->join('variant_combinations', 'order_items.variant_combination_id', '=', 'variant_combinations.id')
                ->where(function ($query) use ($variant) {
                    $query->where('variant_combinations.size_variant_id', $variant->id)
                          ->orWhere('variant_combinations.color_variant_id', $variant->id)
                          ->orWhere('variant_combinations.scent_variant_id', $variant->id);
                })
                ->exists();

            if ($hasOrders) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete variant with existing orders.'
                ], 422);
            }

            $variant->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Variant deleted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting variant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update variant combinations for a product.
     */
    private function updateVariantCombinations(Product $product)
    {
        $sizeVariants = $product->variants()->ofType('size')->active()->get();
        $colorVariants = $product->variants()->ofType('color')->active()->get();
        $scentVariants = $product->variants()->ofType('scent')->active()->get();

        // If no variants exist, return
        if ($sizeVariants->isEmpty() && $colorVariants->isEmpty() && $scentVariants->isEmpty()) {
            return;
        }

        // Generate all possible combinations
        $combinations = [];

        // Handle different combination scenarios
        if ($sizeVariants->isNotEmpty() && $colorVariants->isNotEmpty() && $scentVariants->isNotEmpty()) {
            // All three types
            foreach ($sizeVariants as $size) {
                foreach ($colorVariants as $color) {
                    foreach ($scentVariants as $scent) {
                        $combinations[] = [
                            'size' => $size,
                            'color' => $color,
                            'scent' => $scent
                        ];
                    }
                }
            }
        } elseif ($sizeVariants->isNotEmpty() && $colorVariants->isNotEmpty()) {
            // Size and Color
            foreach ($sizeVariants as $size) {
                foreach ($colorVariants as $color) {
                    $combinations[] = [
                        'size' => $size,
                        'color' => $color,
                        'scent' => null
                    ];
                }
            }
        } elseif ($sizeVariants->isNotEmpty() && $scentVariants->isNotEmpty()) {
            // Size and Scent
            foreach ($sizeVariants as $size) {
                foreach ($scentVariants as $scent) {
                    $combinations[] = [
                        'size' => $size,
                        'color' => null,
                        'scent' => $scent
                    ];
                }
            }
        } elseif ($colorVariants->isNotEmpty() && $scentVariants->isNotEmpty()) {
            // Color and Scent
            foreach ($colorVariants as $color) {
                foreach ($scentVariants as $scent) {
                    $combinations[] = [
                        'size' => null,
                        'color' => $color,
                        'scent' => $scent
                    ];
                }
            }
        } else {
            // Single variant type
            if ($sizeVariants->isNotEmpty()) {
                foreach ($sizeVariants as $size) {
                    $combinations[] = ['size' => $size, 'color' => null, 'scent' => null];
                }
            }
            if ($colorVariants->isNotEmpty()) {
                foreach ($colorVariants as $color) {
                    $combinations[] = ['size' => null, 'color' => $color, 'scent' => null];
                }
            }
            if ($scentVariants->isNotEmpty()) {
                foreach ($scentVariants as $scent) {
                    $combinations[] = ['size' => null, 'color' => null, 'scent' => $scent];
                }
            }
        }

        // Create or update combinations
        foreach ($combinations as $combo) {
            $skuParts = [$product->sku];
            $price = $product->selling_price;
            $costPrice = $product->cost_price;

            if ($combo['size']) {
                $skuParts[] = $combo['size']->sku_suffix;
                $price = $combo['size']->price;
                $costPrice = $combo['size']->cost_price;
            }
            if ($combo['color']) {
                $skuParts[] = $combo['color']->sku_suffix;
                // Use color price only if no size variant
                if (!$combo['size']) {
                    $price = $combo['color']->price;
                    $costPrice = $combo['color']->cost_price;
                }
            }
            if ($combo['scent']) {
                $skuParts[] = $combo['scent']->sku_suffix;
                // Use scent price only if no other variants
                if (!$combo['size'] && !$combo['color']) {
                    $price = $combo['scent']->price;
                    $costPrice = $combo['scent']->cost_price;
                }
            }

            $combinationSku = implode('-', $skuParts);

            $combination = VariantCombination::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'size_variant_id' => $combo['size']?->id,
                    'color_variant_id' => $combo['color']?->id,
                    'scent_variant_id' => $combo['scent']?->id,
                ],
                [
                    'combination_sku' => $combinationSku,
                    'combination_price' => $price,
                    'combination_cost_price' => $costPrice,
                ]
            );

            // Create inventory record if it doesn't exist
            Inventory::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'variant_combination_id' => $combination->id,
                ],
                [
                    'current_stock' => 0,
                    'reserved_stock' => 0,
                    'low_stock_threshold' => 10,
                ]
            );
        }
    }

    /**
     * Deactivate related combinations when a variant is deactivated.
     */
    private function deactivateRelatedCombinations(ProductVariant $variant)
    {
        // This is a simplified version - in a real app, you might want to handle this differently
        // For now, we'll just note that combinations with inactive variants should be handled
        // You could add an 'is_active' field to variant_combinations table
    }


    /**
 * Show a specific variant (for editing)
 */
public function show(ProductVariant $variant)
{
    try {
        return response()->json([
            'success' => true,
            'variant' => $variant
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error loading variant: ' . $e->getMessage()
        ], 500);
    }
}
}