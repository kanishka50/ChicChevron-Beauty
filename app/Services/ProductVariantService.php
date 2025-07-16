<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class ProductVariantService
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Create multiple variants at once
     */
    public function bulkCreateVariants(Product $product, array $variantsData): Collection
    {
        $createdVariants = collect();

        DB::beginTransaction();

        try {
            foreach ($variantsData as $variantData) {
                // Skip if all attributes are empty
                if (empty($variantData['size']) && empty($variantData['color']) && empty($variantData['scent'])) {
                    continue;
                }

                // Check uniqueness
                if (!$this->validateVariantUniqueness($product, $variantData)) {
                    throw new \Exception("Duplicate variant: " . $this->formatVariantName($variantData));
                }

                // Generate SKU if not provided
                $sku = $variantData['sku'] ?? $this->generateVariantSku($product, $variantData);

                // Create variant
                $variant = $product->variants()->create([
                    'size' => $variantData['size'] ?? null,
                    'color' => $variantData['color'] ?? null,
                    'scent' => $variantData['scent'] ?? null,
                    'name' => $this->formatVariantName($variantData),
                    'sku' => $sku,
                    'price' => $variantData['price'] ?? 0,
                    'cost_price' => $variantData['cost_price'] ?? 0,
                    'discount_price' => $variantData['discount_price'] ?? null,
                    'is_active' => $variantData['is_active'] ?? true,
                ]);

                // Create inventory record
                Inventory::create([
                    'product_id' => $product->id,
                    'product_variant_id' => $variant->id,
                    'current_stock' => $variantData['initial_stock'] ?? 0,
                    'reserved_stock' => 0,
                    'low_stock_threshold' => $variantData['low_stock_threshold'] ?? 10,
                ]);

                // Add initial stock if provided
                if (!empty($variantData['initial_stock']) && $variantData['initial_stock'] > 0) {
                    $this->inventoryService->addStock(
                        $product->id,
                        $variant->id,
                        $variantData['initial_stock'],
                        $variantData['cost_price'] ?? 0,
                        'Initial stock'
                    );
                }

                $createdVariants->push($variant);
            }

            // Update product has_variants flag
            if ($product->variants()->count() > 1) {
                $product->update(['has_variants' => true]);
            }

            DB::commit();

            return $createdVariants;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Validate that variant combination is unique for product
     */
    public function validateVariantUniqueness(Product $product, array $variantData, $excludeId = null): bool
    {
        $query = $product->variants()
            ->where(function ($q) use ($variantData) {
                // Handle size
                if (isset($variantData['size'])) {
                    $q->where('size', $variantData['size']);
                } else {
                    $q->whereNull('size');
                }

                // Handle color
                if (isset($variantData['color'])) {
                    $q->where('color', $variantData['color']);
                } else {
                    $q->whereNull('color');
                }

                // Handle scent
                if (isset($variantData['scent'])) {
                    $q->where('scent', $variantData['scent']);
                } else {
                    $q->whereNull('scent');
                }
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    /**
     * Generate unique SKU for variant
     */
    public function generateVariantSku(Product $product, array $variantData): string
    {
        $parts = [$product->sku];

        if (!empty($variantData['size'])) {
            $parts[] = Str::slug($variantData['size']);
        }
        if (!empty($variantData['color'])) {
            $parts[] = Str::slug($variantData['color']);
        }
        if (!empty($variantData['scent'])) {
            $parts[] = Str::slug($variantData['scent']);
        }

        $baseSku = implode('-', $parts);

        // Ensure uniqueness
        $sku = $baseSku;
        $counter = 1;

        while (ProductVariant::where('sku', $sku)->exists()) {
            $sku = $baseSku . '-' . $counter;
            $counter++;
        }

        return strtoupper($sku);
    }

    /**
     * Format variant name from attributes
     */
    public function formatVariantName(array $variantData): string
    {
        $parts = array_filter([
            $variantData['size'] ?? null,
            $variantData['color'] ?? null,
            $variantData['scent'] ?? null,
        ]);

        return !empty($parts) ? implode(' - ', $parts) : 'Standard';
    }

    /**
     * Sync pricing across variants
     */
    public function syncPricing(Product $product, array $pricingRules): void
    {
        DB::beginTransaction();

        try {
            foreach ($pricingRules as $rule) {
                $query = $product->variants();

                // Apply filters
                if (!empty($rule['size'])) {
                    $query->where('size', $rule['size']);
                }
                if (!empty($rule['color'])) {
                    $query->where('color', $rule['color']);
                }
                if (!empty($rule['scent'])) {
                    $query->where('scent', $rule['scent']);
                }

                // Update pricing
                $updateData = [];
                if (isset($rule['price'])) {
                    $updateData['price'] = $rule['price'];
                }
                if (isset($rule['cost_price'])) {
                    $updateData['cost_price'] = $rule['cost_price'];
                }
                if (isset($rule['discount_price'])) {
                    $updateData['discount_price'] = $rule['discount_price'];
                }
                if (isset($rule['price_adjustment'])) {
                    // Percentage adjustment
                    $query->get()->each(function ($variant) use ($rule) {
                        $newPrice = $variant->price * (1 + $rule['price_adjustment'] / 100);
                        $variant->update(['price' => round($newPrice, 2)]);
                    });
                } else {
                    $query->update($updateData);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate variant matrix for product
     */
    public function generateVariantMatrix(Product $product, array $attributes): array
    {
        $matrix = [];

        $sizes = $attributes['sizes'] ?? [];
        $colors = $attributes['colors'] ?? [];
        $scents = $attributes['scents'] ?? [];

        // If all arrays are empty, return single standard variant
        if (empty($sizes) && empty($colors) && empty($scents)) {
            return [[
                'size' => null,
                'color' => null,
                'scent' => null,
                'name' => 'Standard',
            ]];
        }

        // Ensure at least one item in each array for iteration
        $sizes = empty($sizes) ? [null] : $sizes;
        $colors = empty($colors) ? [null] : $colors;
        $scents = empty($scents) ? [null] : $scents;

        // Generate all combinations
        foreach ($sizes as $size) {
            foreach ($colors as $color) {
                foreach ($scents as $scent) {
                    // Skip if all are null
                    if (is_null($size) && is_null($color) && is_null($scent)) {
                        continue;
                    }

                    $matrix[] = [
                        'size' => $size,
                        'color' => $color,
                        'scent' => $scent,
                        'name' => $this->formatVariantName([
                            'size' => $size,
                            'color' => $color,
                            'scent' => $scent,
                        ]),
                        'sku' => $this->generateVariantSku($product, [
                            'size' => $size,
                            'color' => $color,
                            'scent' => $scent,
                        ]),
                        'exists' => !$this->validateVariantUniqueness($product, [
                            'size' => $size,
                            'color' => $color,
                            'scent' => $scent,
                        ]),
                    ];
                }
            }
        }

        return $matrix;
    }

    /**
     * Copy variants from one product to another
     */
    public function copyVariants(Product $sourceProduct, Product $targetProduct, bool $includePricing = true): Collection
    {
        $copiedVariants = collect();

        DB::beginTransaction();

        try {
            $sourceVariants = $sourceProduct->variants;

            foreach ($sourceVariants as $sourceVariant) {
                $variantData = [
                    'size' => $sourceVariant->size,
                    'color' => $sourceVariant->color,
                    'scent' => $sourceVariant->scent,
                    'price' => $includePricing ? $sourceVariant->price : 0,
                    'cost_price' => $includePricing ? $sourceVariant->cost_price : 0,
                    'discount_price' => $includePricing ? $sourceVariant->discount_price : null,
                    'is_active' => true,
                ];

                // Check if variant already exists
                if ($this->validateVariantUniqueness($targetProduct, $variantData)) {
                    $variant = $targetProduct->variants()->create([
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'scent' => $variantData['scent'],
                        'name' => $this->formatVariantName($variantData),
                        'sku' => $this->generateVariantSku($targetProduct, $variantData),
                        'price' => $variantData['price'],
                        'cost_price' => $variantData['cost_price'],
                        'discount_price' => $variantData['discount_price'],
                        'is_active' => $variantData['is_active'],
                    ]);

                    // Create inventory record
                    Inventory::create([
                        'product_id' => $targetProduct->id,
                        'product_variant_id' => $variant->id,
                        'current_stock' => 0,
                        'reserved_stock' => 0,
                        'low_stock_threshold' => $sourceVariant->inventory->low_stock_threshold ?? 10,
                    ]);

                    $copiedVariants->push($variant);
                }
            }

            // Update has_variants flag
            if ($targetProduct->variants()->count() > 1) {
                $targetProduct->update(['has_variants' => true]);
            }

            DB::commit();

            return $copiedVariants;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get variant price range for a product
     */
    public function getVariantPriceRange(Product $product): array
    {
        $activeVariants = $product->activeVariants;

        if ($activeVariants->isEmpty()) {
            return [
                'min' => 0,
                'max' => 0,
                'min_variant' => null,
                'max_variant' => null,
            ];
        }

        $minVariant = $activeVariants->sortBy('effective_price')->first();
        $maxVariant = $activeVariants->sortByDesc('effective_price')->first();

        return [
            'min' => $minVariant->effective_price,
            'max' => $maxVariant->effective_price,
            'min_variant' => $minVariant,
            'max_variant' => $maxVariant,
        ];
    }

    /**
     * Update variant status in bulk
     */
    public function bulkUpdateStatus(array $variantIds, bool $isActive): int
    {
        return ProductVariant::whereIn('id', $variantIds)
            ->update(['is_active' => $isActive]);
    }

    /**
     * Get variant statistics for a product
     */
    public function getVariantStatistics(Product $product): array
    {
        $variants = $product->variants()->with('inventory')->get();

        return [
            'total_variants' => $variants->count(),
            'active_variants' => $variants->where('is_active', true)->count(),
            'total_stock' => $variants->sum(function ($variant) {
                return $variant->inventory->current_stock ?? 0;
            }),
            'total_value' => $variants->sum(function ($variant) {
                $stock = $variant->inventory->current_stock ?? 0;
                return $stock * $variant->cost_price;
            }),
            'out_of_stock' => $variants->filter(function ($variant) {
                return ($variant->inventory->available_stock ?? 0) <= 0;
            })->count(),
            'low_stock' => $variants->filter(function ($variant) {
                $inventory = $variant->inventory;
                return $inventory && $inventory->available_stock > 0 && 
                       $inventory->available_stock <= $inventory->low_stock_threshold;
            })->count(),
        ];
    }
}