<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variant_combinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('color_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('scent_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->string('combination_sku', 150)->unique();
            $table->decimal('combination_price', 10, 2);
            $table->decimal('combination_cost_price', 10, 2);
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('combination_sku');
            $table->index(['product_id', 'size_variant_id', 'color_variant_id', 'scent_variant_id'], 'variant_combo_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_combinations');
    }
};