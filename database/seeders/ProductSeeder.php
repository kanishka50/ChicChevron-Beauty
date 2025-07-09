<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductType;
use App\Models\Texture;
use App\Models\Color;
use App\Models\ProductIngredient;
use App\Models\ProductColor;
use App\Models\ProductVariant;
use App\Models\VariantCombination;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->createSkinCareProducts();
            $this->createHairCareProducts();
            $this->createBabyCareProducts();
        });

        $this->command->info('✅ Products seeded successfully!');
        $this->command->info('📊 Created ' . Product::count() . ' products with variants and inventory');
    }

    private function createSkinCareProducts()
    {
        $skinCareCategory = Category::where('name', 'Skin Care')->first();
        $cleansersSubcat = Category::where('name', 'Cleansers')->first();
        $moisturizersSubcat = Category::where('name', 'Moisturizers')->first();
        $serumsSubcat = Category::where('name', 'Serums')->first();
        $sunscreensSubcat = Category::where('name', 'Sunscreens')->first();

        $skinCareType = ProductType::where('name', 'Skin Care')->first();
        $creamTexture = Texture::where('name', 'Cream')->first();
        $gelTexture = Texture::where('name', 'Gel')->first();
        $serumTexture = Texture::where('name', 'Serum')->first();

        // Cetaphil Gentle Skin Cleanser
        $product1 = $this->createProduct([
            'name' => 'Cetaphil Gentle Skin Cleanser',
            'description' => 'A mild, non-alkaline cleanser that cleanses without irritation. Formulated for daily facial cleansing of normal to oily, sensitive skin. Removes surface oils, dirt and makeup without leaving skin tight or overly dry.',
            'sku' => 'CTF-GSC-001',
            'brand_id' => Brand::where('name', 'Cetaphil')->first()->id,
            'category_id' => $cleansersSubcat->id,
            'product_type_id' => $skinCareType->id,
            'texture_id' => $gelTexture->id,
            'cost_price' => 1200.00,
            'selling_price' => 1800.00,
            'main_image' => 'products/placeholder-cleanser.jpg',
            'suitable_for' => 'Normal to Oily Skin',
            'fragrance' => 'Fragrance-free',
            'has_variants' => true,
        ]);

        $this->addIngredients($product1, [
            'Water', 'Cetyl Alcohol', 'Propylene Glycol', 'Sodium Lauryl Sulfate', 
            'Stearyl Alcohol', 'Methylparaben', 'Propylparaben', 'Butylparaben'
        ]);

        $this->createVariantsAndInventory($product1, [
            'sizes' => [
                ['value' => '125ml', 'price' => 1800.00, 'cost' => 1200.00, 'stock' => 50],
                ['value' => '236ml', 'price' => 2800.00, 'cost' => 1900.00, 'stock' => 30],
                ['value' => '473ml', 'price' => 4200.00, 'cost' => 2800.00, 'stock' => 20],
            ]
        ]);

        // Neutrogena Hydrating Foaming Cleanser
        $product2 = $this->createProduct([
            'name' => 'Neutrogena Hydrating Foaming Cleanser',
            'description' => 'This unique formula cleanses thoroughly while maintaining skin\'s natural moisture barrier. Clinically proven to remove 99% of dirt, oil and bacteria. Suitable for sensitive skin.',
            'sku' => 'NEU-HFC-002',
            'brand_id' => Brand::where('name', 'Neutrogena')->first()->id,
            'category_id' => $cleansersSubcat->id,
            'product_type_id' => $skinCareType->id,
            'texture_id' => $gelTexture->id,
            'cost_price' => 1500.00,
            'selling_price' => 2200.00,
            'discount_price' => 1980.00,
            'main_image' => 'products/placeholder-cleanser.jpg',
            'suitable_for' => 'All Skin Types',
            'fragrance' => 'Light Fresh Scent',
            'has_variants' => false,
        ]);

        $this->addIngredients($product2, [
            'Water', 'Sodium Cocoyl Glycinate', 'Coco-Betaine', 'Glycerin', 'PEG-120 Methyl Glucose Dioleate'
        ]);

        $this->createSimpleInventory($product2, 75);

        // Olay Regenerist Micro-Sculpting Cream
        $product3 = $this->createProduct([
            'name' => 'Olay Regenerist Micro-Sculpting Cream',
            'description' => 'This advanced anti-aging moisturizer with Amino-Peptides and Niacinamide helps regenerate surface cells while you sleep. Firms skin and reduces the appearance of fine lines and wrinkles.',
            'sku' => 'OLY-RMC-003',
            'brand_id' => Brand::where('name', 'Olay')->first()->id,
            'category_id' => $moisturizersSubcat->id,
            'product_type_id' => $skinCareType->id,
            'texture_id' => $creamTexture->id,
            'cost_price' => 3500.00,
            'selling_price' => 5200.00,
            'main_image' => 'products/placeholder-moisturizer.jpg',
            'suitable_for' => 'Mature Skin',
            'fragrance' => 'Light Floral',
            'has_variants' => true,
        ]);

        $this->addIngredients($product3, [
            'Water', 'Glycerin', 'Isohexadecane', 'Niacinamide', 'Dimethicone', 'Amino-Peptide Complex'
        ]);

        $this->createVariantsAndInventory($product3, [
            'sizes' => [
                ['value' => '50ml', 'price' => 5200.00, 'cost' => 3500.00, 'stock' => 40],
                ['value' => '100ml', 'price' => 8900.00, 'cost' => 6000.00, 'stock' => 25],
            ]
        ]);

        // The Ordinary Niacinamide 10% + Zinc 1%
        $product4 = $this->createProduct([
            'name' => 'The Ordinary Niacinamide 10% + Zinc 1%',
            'description' => 'A high-strength vitamin and mineral treatment that reduces the appearance of skin blemishes and congestion. Improves skin texture and balances visible aspects of sebum activity.',
            'sku' => 'ORD-NIA-004',
            'brand_id' => Brand::where('name', 'The Body Shop')->first()->id, // Using available brand
            'category_id' => $serumsSubcat->id,
            'product_type_id' => $skinCareType->id,
            'texture_id' => $serumTexture->id,
            'cost_price' => 800.00,
            'selling_price' => 1450.00,
            'main_image' => 'products/placeholder-serum.jpg',
            'suitable_for' => 'Oily & Acne-Prone Skin',
            'fragrance' => 'Fragrance-free',
            'has_variants' => false,
        ]);

        $this->addIngredients($product4, [
            'Aqua', 'Niacinamide', 'Pentylene Glycol', 'Zinc PCA', 'Dimethyl Isosorbide', 'Tamarindus Indica Seed Gum'
        ]);

        $this->createSimpleInventory($product4, 100);

        // Nivea Sun Protect & Moisture SPF 50+
        $product5 = $this->createProduct([
            'name' => 'Nivea Sun Protect & Moisture SPF 50+',
            'description' => 'Advanced sun protection with immediate UVA/UVB protection. Water-resistant formula with Hydra IQ technology provides 24-hour moisture. Non-greasy and fast-absorbing.',
            'sku' => 'NIV-SPM-005',
            'brand_id' => Brand::where('name', 'Nivea')->first()->id,
            'category_id' => $sunscreensSubcat->id,
            'product_type_id' => $skinCareType->id,
            'texture_id' => $creamTexture->id,
            'cost_price' => 1200.00,
            'selling_price' => 1890.00,
            'main_image' => 'products/placeholder-sunscreen.jpg',
            'suitable_for' => 'All Skin Types',
            'fragrance' => 'Fresh Aqua',
            'has_variants' => true,
        ]);

        $this->addIngredients($product5, [
            'Aqua', 'Homosalate', 'Butyl Methoxydibenzoylmethane', 'Ethylhexyl Salicylate', 'Glycerin'
        ]);

        $this->createVariantsAndInventory($product5, [
            'sizes' => [
                ['value' => '75ml', 'price' => 1890.00, 'cost' => 1200.00, 'stock' => 60],
                ['value' => '200ml', 'price' => 3200.00, 'cost' => 2100.00, 'stock' => 35],
            ]
        ]);
    }

    private function createHairCareProducts()
    {
        $hairCareCategory = Category::where('name', 'Hair Care')->first();
        $shampoosSubcat = Category::where('name', 'Shampoos')->first();
        $conditionersSubcat = Category::where('name', 'Conditioners')->first();
        $hairOilsSubcat = Category::where('name', 'Hair Oils')->first();

        $hairCareType = ProductType::where('name', 'Hair Care')->first();
        $creamTexture = Texture::where('name', 'Cream')->first();
        $oilTexture = Texture::where('name', 'Oil')->first();

        // L'Oreal Paris Total Repair 5 Shampoo
        $product6 = $this->createProduct([
            'name' => 'L\'Oreal Paris Total Repair 5 Shampoo',
            'description' => 'Reconstructing shampoo for damaged hair. Targets 5 signs of damage: hair fall, dryness, roughness, dullness and split ends. With Ceramide-Cement technology.',
            'sku' => 'LOR-TR5-006',
            'brand_id' => Brand::where('name', 'L\'Oreal')->first()->id,
            'category_id' => $shampoosSubcat->id,
            'product_type_id' => $hairCareType->id,
            'cost_price' => 800.00,
            'selling_price' => 1350.00,
            'main_image' => 'products/placeholder-shampoo.jpg',
            'suitable_for' => 'Damaged Hair',
            'fragrance' => 'Fresh Floral',
            'has_variants' => true,
        ]);

        $this->addIngredients($product6, [
            'Aqua', 'Sodium Laureth Sulfate', 'Cocamidopropyl Betaine', 'Glycerin', 'Ceramide'
        ]);

        $this->createVariantsAndInventory($product6, [
            'sizes' => [
                ['value' => '175ml', 'price' => 1350.00, 'cost' => 800.00, 'stock' => 45],
                ['value' => '360ml', 'price' => 2200.00, 'cost' => 1400.00, 'stock' => 30],
                ['value' => '640ml', 'price' => 3500.00, 'cost' => 2300.00, 'stock' => 15],
            ]
        ]);

        // Garnier Fructis Hair Oil
        $product7 = $this->createProduct([
            'name' => 'Garnier Fructis Strengthening Hair Oil',
            'description' => 'Nourishing hair oil with fortifying Apple extract and Ceramide. Strengthens hair from root to tip, reduces hair fall and adds natural shine. Non-greasy formula.',
            'sku' => 'GAR-FHO-007',
            'brand_id' => Brand::where('name', 'Garnier')->first()->id,
            'category_id' => $hairOilsSubcat->id,
            'product_type_id' => $hairCareType->id,
            'texture_id' => $oilTexture->id,
            'cost_price' => 650.00,
            'selling_price' => 1100.00,
            'main_image' => 'products/placeholder-hair-oil.jpg',
            'suitable_for' => 'All Hair Types',
            'fragrance' => 'Apple Fresh',
            'has_variants' => false,
        ]);

        $this->addIngredients($product7, [
            'Cyclopentasiloxane', 'Dimethiconol', 'Pyrus Malus Extract', 'Ceramide', 'Parfum'
        ]);

        $this->createSimpleInventory($product7, 80);

        // Dove Intense Repair Conditioner
        $product8 = $this->createProduct([
            'name' => 'Dove Intense Repair Conditioner',
            'description' => 'Deep conditioning treatment with Keratin Repair Actives. Helps repair signs of damage and makes hair 5x smoother. Suitable for dry and damaged hair.',
            'sku' => 'DOV-IRC-008',
            'brand_id' => Brand::where('name', 'Dove')->first()->id,
            'category_id' => $conditionersSubcat->id,
            'product_type_id' => $hairCareType->id,
            'texture_id' => $creamTexture->id,
            'cost_price' => 750.00,
            'selling_price' => 1250.00,
            'discount_price' => 1125.00,
            'main_image' => 'products/placeholder-conditioner.jpg',
            'suitable_for' => 'Dry & Damaged Hair',
            'fragrance' => 'Soft Floral',
            'has_variants' => true,
        ]);

        $this->addIngredients($product8, [
            'Water', 'Cetearyl Alcohol', 'Behentrimonium Chloride', 'Dimethicone', 'Keratin'
        ]);

        $this->createVariantsAndInventory($product8, [
            'sizes' => [
                ['value' => '180ml', 'price' => 1125.00, 'cost' => 750.00, 'stock' => 55],
                ['value' => '355ml', 'price' => 1980.00, 'cost' => 1320.00, 'stock' => 25],
            ]
        ]);
    }

    private function createBabyCareProducts()
    {
        $babyCareCategory = Category::where('name', 'Baby Care')->first();
        $babyLotionsSubcat = Category::where('name', 'Baby Lotions')->first();
        $babyOilsSubcat = Category::where('name', 'Baby Oils')->first();

        $babyCareType = ProductType::where('name', 'Baby Care')->first();
        $lotionTexture = Texture::where('name', 'Lotion')->first();
        $oilTexture = Texture::where('name', 'Oil')->first();

        // Johnson's Baby Lotion
        $product9 = $this->createProduct([
            'name' => 'Johnson\'s Baby Lotion',
            'description' => 'Clinically proven mildness. Hypoallergenic and dermatologist-tested formula that\'s gentle enough for newborns. Helps keep baby\'s skin soft and smooth for 24 hours.',
            'sku' => 'JOH-BL-009',
            'brand_id' => Brand::where('name', 'Johnson\'s Baby')->first()->id,
            'category_id' => $babyLotionsSubcat->id,
            'product_type_id' => $babyCareType->id,
            'texture_id' => $lotionTexture->id,
            'cost_price' => 580.00,
            'selling_price' => 950.00,
            'main_image' => 'products/placeholder-baby-lotion.jpg',
            'suitable_for' => 'Newborns & Babies',
            'fragrance' => 'Classic Baby Scent',
            'has_variants' => true,
        ]);

        $this->addIngredients($product9, [
            'Water', 'Mineral Oil', 'Glycerin', 'Cetearyl Alcohol', 'Dimethicone'
        ]);

        $this->createVariantsAndInventory($product9, [
            'sizes' => [
                ['value' => '100ml', 'price' => 950.00, 'cost' => 580.00, 'stock' => 70],
                ['value' => '200ml', 'price' => 1650.00, 'cost' => 1000.00, 'stock' => 40],
                ['value' => '500ml', 'price' => 3200.00, 'cost' => 2000.00, 'stock' => 20],
            ]
        ]);

        // Himalaya Baby Oil
        $product10 = $this->createProduct([
            'name' => 'Himalaya Baby Oil',
            'description' => 'Herbal baby oil with natural ingredients like Olive Oil, Winter Cherry and Licorice. Provides gentle nourishment and helps in baby\'s physical development through massage.',
            'sku' => 'HIM-BO-010',
            'brand_id' => Brand::where('name', 'Himalaya')->first()->id,
            'category_id' => $babyOilsSubcat->id,
            'product_type_id' => $babyCareType->id,
            'texture_id' => $oilTexture->id,
            'cost_price' => 420.00,
            'selling_price' => 720.00,
            'main_image' => 'products/placeholder-baby-oil.jpg',
            'suitable_for' => 'Babies & Infants',
            'fragrance' => 'Mild Herbal',
            'has_variants' => false,
        ]);

        $this->addIngredients($product10, [
            'Sesame Oil', 'Olive Oil', 'Winter Cherry Extract', 'Licorice Extract', 'Almond Oil'
        ]);

        $this->createSimpleInventory($product10, 90);
    }

    private function createProduct(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = true;
        $data['views_count'] = rand(10, 500);

        return Product::create($data);
    }

    private function addIngredients(Product $product, array $ingredients)
    {
        foreach ($ingredients as $ingredient) {
            ProductIngredient::create([
                'product_id' => $product->id,
                'ingredient_name' => $ingredient,
            ]);
        }
    }

    private function createSimpleInventory(Product $product, int $stock)
    {
        $inventory = Inventory::create([
            'product_id' => $product->id,
            'variant_combination_id' => null,
            'current_stock' => $stock,
            'reserved_stock' => 0,
            'low_stock_threshold' => 10,
        ]);

        // Create initial stock movement
        InventoryMovement::create([
            'inventory_id' => $inventory->id,
            'batch_number' => 'INIT-' . $product->sku . '-' . now()->format('YmdHis'),
            'movement_type' => 'in',
            'quantity' => $stock,
            'cost_per_unit' => $product->cost_price,
            'reason' => 'Initial stock',
            'movement_date' => now(),
        ]);
    }

    private function createVariantsAndInventory(Product $product, array $variantData)
    {
        $combinations = [];

        if (isset($variantData['sizes'])) {
            foreach ($variantData['sizes'] as $sizeData) {
                // Create size variant
                $sizeVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_type' => 'size',
                    'variant_value' => $sizeData['value'],
                    'sku_suffix' => strtoupper(str_replace(['ml', 'g', ' '], ['ML', 'G', ''], $sizeData['value'])),
                    'price' => $sizeData['price'],
                    'cost_price' => $sizeData['cost'],
                    'is_active' => true,
                ]);

                // Create variant combination
                $combination = VariantCombination::create([
                    'product_id' => $product->id,
                    'size_variant_id' => $sizeVariant->id,
                    'color_variant_id' => null,
                    'scent_variant_id' => null,
                    'combination_sku' => $product->sku . '-' . $sizeVariant->sku_suffix,
                    'combination_price' => $sizeData['price'],
                    'combination_cost_price' => $sizeData['cost'],
                ]);

                // Create inventory for this combination
                $inventory = Inventory::create([
                    'product_id' => $product->id,
                    'variant_combination_id' => $combination->id,
                    'current_stock' => $sizeData['stock'],
                    'reserved_stock' => 0,
                    'low_stock_threshold' => 5,
                ]);

                // Create initial stock movement
                InventoryMovement::create([
                    'inventory_id' => $inventory->id,
                    'batch_number' => 'INIT-' . $combination->combination_sku . '-' . now()->format('YmdHis'),
                    'movement_type' => 'in',
                    'quantity' => $sizeData['stock'],
                    'cost_per_unit' => $sizeData['cost'],
                    'reason' => 'Initial stock - ' . $sizeData['value'],
                    'movement_date' => now(),
                ]);
            }
        }

        // Add colors and scents logic here if needed for other products
        if (isset($variantData['colors'])) {
            foreach ($variantData['colors'] as $colorData) {
                // Implementation for color variants
            }
        }

        if (isset($variantData['scents'])) {
            foreach ($variantData['scents'] as $scentData) {
                // Implementation for scent variants
            }
        }
    }
}