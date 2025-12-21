<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove flat attribute columns - now handled by variant_attribute_values table
     * - size: Now in variant_attribute_values
     * - color: Now in variant_attribute_values
     * - scent: Now in variant_attribute_values
     * - cost_price: Now tracked in inventory_movements
     */
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['size', 'color', 'scent', 'cost_price']);
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('size', 50)->nullable()->after('product_id');
            $table->string('color', 50)->nullable()->after('size');
            $table->string('scent', 50)->nullable()->after('color');
            $table->decimal('cost_price', 10, 2)->after('price');
        });
    }
};
