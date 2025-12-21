<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Simplify inventory table:
     * - Rename current_stock to stock_quantity
     * - Rename reserved_stock to reserved_quantity
     * - Remove product_id (track only via product_variant_id)
     * - Add unique constraint on product_variant_id
     */
    public function up(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            // Rename columns for clarity
            $table->renameColumn('current_stock', 'stock_quantity');
            $table->renameColumn('reserved_stock', 'reserved_quantity');
        });

        Schema::table('inventory', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['product_id']);

            // Drop the old index if it exists
            $table->dropIndex(['product_id']);

            // Remove product_id column (redundant - variant already has product_id)
            $table->dropColumn('product_id');
        });

        // Drop the old unique constraint and add new one
        Schema::table('inventory', function (Blueprint $table) {
            // Remove old composite unique key
            $table->dropUnique('inventory_product_id_variant_id_unique');

            // Add unique constraint on product_variant_id only
            $table->unique('product_variant_id', 'unique_variant');
        });
    }

    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropUnique('unique_variant');
        });

        Schema::table('inventory', function (Blueprint $table) {
            // Re-add product_id
            $table->foreignId('product_id')->after('id')->constrained()->onDelete('cascade');

            // Restore indexes
            $table->index('product_id');
            $table->unique(['product_id', 'product_variant_id'], 'inventory_product_id_variant_id_unique');
        });

        Schema::table('inventory', function (Blueprint $table) {
            // Rename columns back
            $table->renameColumn('stock_quantity', 'current_stock');
            $table->renameColumn('reserved_quantity', 'reserved_stock');
        });
    }
};
