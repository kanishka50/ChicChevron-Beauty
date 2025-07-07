<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('variant_type', ['size', 'color', 'scent']);
            $table->string('variant_value', 100);
            $table->string('sku_suffix', 50);
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('variant_type');
            $table->index('is_active');
            $table->unique(['product_id', 'variant_type', 'variant_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};