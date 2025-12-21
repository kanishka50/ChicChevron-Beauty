<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drops unused tables:
     * - product_ingredients: Replaced by products.ingredients TEXT column
     * - product_images: Replaced by products.gallery_images JSON column
     */
    public function up(): void
    {
        Schema::dropIfExists('product_ingredients');
        Schema::dropIfExists('product_images');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate product_ingredients table
        Schema::create('product_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('ingredient_name');
            $table->timestamps();
        });

        // Recreate product_images table
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
};
