<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Product variants table - stores individual SKUs with pricing
     *
     * Each variant represents a specific combination of size/color/scent
     * with its own price, cost, and inventory tracking.
     */
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};