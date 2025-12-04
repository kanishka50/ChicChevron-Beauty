<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Removes the colors and product_colors tables as colors are now
     * managed as variant attributes (product_variants.color) with
     * hex codes derived from a static color map.
     */
    public function up(): void
    {
        // Drop the pivot table first (has foreign key to colors)
        Schema::dropIfExists('product_colors');

        // Then drop the colors table
        Schema::dropIfExists('colors');
    }

    /**
     * Reverse the migrations.
     *
     * Recreates the colors and product_colors tables if rollback is needed.
     */
    public function down(): void
    {
        // Recreate colors table
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('hex_code', 7)->nullable();
            $table->string('rgb_code', 20)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Recreate product_colors pivot table
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['product_id', 'color_id']);
        });
    }
};
