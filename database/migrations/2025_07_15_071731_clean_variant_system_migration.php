<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Step 1: Drop all foreign key constraints that exist
        Schema::table('cart_items', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('cart_items');
            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->getName() === 'cart_items_variant_combination_id_foreign') {
                    $table->dropForeign(['variant_combination_id']);
                }
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('order_items');
            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->getName() === 'order_items_variant_combination_id_foreign') {
                    $table->dropForeign(['variant_combination_id']);
                }
            }
        });

        // Step 2: Since you don't need data, let's truncate tables that reference variants
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('inventory')->truncate();
        DB::table('cart_items')->truncate();
        DB::table('order_items')->truncate();
        DB::table('variant_combinations')->truncate();
        DB::table('product_variants')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Step 3: Drop the variant_combinations table
        Schema::dropIfExists('variant_combinations');

        // Step 4: Drop the old product_variants table
        Schema::dropIfExists('product_variants');

        // Step 5: Create new simplified product_variants table
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Variant attributes - all nullable (free text)
            $table->string('size', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('scent', 50)->nullable();
            
            // Complete variant information
            $table->string('sku', 150)->unique();
            $table->string('name', 255); // e.g., "50ml - Rose" or "Large - Red"
            
            // Pricing - directly on variant
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index('product_id');
            $table->index('sku');
            $table->index('is_active');
        });

        // Step 6: Update inventory table
        Schema::table('inventory', function (Blueprint $table) {
            // Add product_variant_id if it doesn't exist
            if (!Schema::hasColumn('inventory', 'product_variant_id')) {
                $table->foreignId('product_variant_id')->nullable()->after('product_id')
                    ->constrained('product_variants')->onDelete('cascade');
            }
            
            // Drop variant_combination_id if it exists
            if (Schema::hasColumn('inventory', 'variant_combination_id')) {
                $table->dropColumn('variant_combination_id');
            }
        });

        // Step 7: Update cart_items table
        Schema::table('cart_items', function (Blueprint $table) {
            // Add foreign key for product_variant_id if not exists
            if (Schema::hasColumn('cart_items', 'product_variant_id')) {
                $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            }
            
            // Drop variant_combination_id
            if (Schema::hasColumn('cart_items', 'variant_combination_id')) {
                $table->dropColumn('variant_combination_id');
            }
        });

        // Step 8: Update order_items table  
        Schema::table('order_items', function (Blueprint $table) {
            // Add foreign key for product_variant_id if not exists
            if (Schema::hasColumn('order_items', 'product_variant_id')) {
                $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('restrict');
            }
            
            // Drop variant_combination_id
            if (Schema::hasColumn('order_items', 'variant_combination_id')) {
                $table->dropColumn('variant_combination_id');
            }
        });

        // Step 9: Add has_variants to products table if not exists
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'has_variants')) {
                $table->boolean('has_variants')->default(false)->after('is_active');
            }
        });

        // Step 10: Create default variant for each product
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            DB::table('product_variants')->insert([
                'product_id' => $product->id,
                'name' => 'Standard',
                'sku' => $product->sku,
                'price' => 0, // You'll need to set prices manually
                'cost_price' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        // This migration is not reversible due to data loss
        throw new \Exception('This migration cannot be reversed. Please restore from backup.');
    }
};