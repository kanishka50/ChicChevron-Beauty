<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Since tables already have both columns, we need to clean up first
        
        // Drop the variant_combination_id columns and their indexes
        Schema::table('inventory', function (Blueprint $table) {
            // Drop the unique constraint first
            $table->dropUnique('inventory_product_id_variant_combination_id_unique');
            // Drop the index
            $table->dropIndex('inventory_variant_combination_id_index');
            // Drop the column
            $table->dropColumn('variant_combination_id');
        });
        
        Schema::table('cart_items', function (Blueprint $table) {
            // Drop indexes that reference variant_combination_id
            $table->dropIndex('cart_items_variant_combination_id_foreign');
            $table->dropIndex('cart_user_product_variant');
            $table->dropIndex('cart_session_product_variant');
            // Drop the column
            $table->dropColumn('variant_combination_id');
        });
        
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the index
            $table->dropIndex('order_items_variant_combination_id_foreign');
            // Drop the column
            $table->dropColumn('variant_combination_id');
        });

        // Now drop the old tables
        Schema::dropIfExists('variant_combinations');
        Schema::dropIfExists('product_variants');
        
        // Create new simplified product_variants table
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Variant attributes - all nullable (free text)
            $table->string('size', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('scent', 50)->nullable();
            
            // Complete variant information
            $table->string('sku', 150)->unique();
            $table->string('name', 255);
            
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

        // Add foreign key constraints for product_variant_id
        Schema::table('inventory', function (Blueprint $table) {
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            // Add new unique constraint
            $table->unique(['product_id', 'product_variant_id']);
        });
        
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
        
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('restrict');
        });

        // Add has_variants flag back to products table if it doesn't exist
        if (!Schema::hasColumn('products', 'has_variants')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('has_variants')->default(false)->after('is_active');
            });
        }
    }

    public function down()
    {
        // Drop foreign key constraints
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropUnique(['product_id', 'product_variant_id']);
        });
        
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
        });
        
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
        });
        
        // Drop the table
        Schema::dropIfExists('product_variants');
        
        // Remove has_variants column if it was added
        if (Schema::hasColumn('products', 'has_variants')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('has_variants');
            });
        }
    }
};