<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Inventory table - tracks stock per product variant
     *
     * Each variant has its own inventory record with:
     * - current_stock: Total units in warehouse
     * - reserved_stock: Units held for pending orders
     * - Available stock = current_stock - reserved_stock
     */
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->integer('current_stock')->default(0);
            $table->integer('reserved_stock')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->timestamps();

            $table->unique(['product_id', 'product_variant_id'], 'inventory_product_id_variant_id_unique');
            $table->index('product_id');
            $table->index('current_stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};