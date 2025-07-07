<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_combination_id')->nullable()->constrained('variant_combinations')->onDelete('cascade');
            $table->integer('current_stock')->default(0);
            $table->integer('reserved_stock')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->timestamps();
            
            $table->unique(['product_id', 'variant_combination_id']);
            $table->index('product_id');
            $table->index('variant_combination_id');
            $table->index('current_stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};