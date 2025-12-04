<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Products table - parent for product variants
     *
     * Note: Pricing is handled at the variant level (product_variants table)
     * This table contains common product information shared across all variants
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('sku', 100)->unique();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('texture_id')->nullable()->constrained();

            // Cached rating data (updated when reviews are added/modified)
            $table->decimal('average_rating', 2, 1)->default(0);
            $table->integer('reviews_count')->default(0);

            $table->string('main_image');
            $table->text('how_to_use')->nullable();
            $table->string('suitable_for')->nullable();
            $table->string('fragrance', 100)->nullable();
            $table->boolean('has_variants')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views_count')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('brand_id');
            $table->index('category_id');
            $table->index('is_active');
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};