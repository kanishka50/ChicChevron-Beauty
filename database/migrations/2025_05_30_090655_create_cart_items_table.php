<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id', 100)->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_combination_id')->nullable()->constrained('variant_combinations')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('session_id');
            $table->index(['user_id', 'product_id', 'variant_combination_id'], 'cart_user_product_variant');
            $table->index(['session_id', 'product_id', 'variant_combination_id'], 'cart_session_product_variant');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};