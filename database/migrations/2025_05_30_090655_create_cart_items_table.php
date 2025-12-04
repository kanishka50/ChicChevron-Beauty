<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cart items table - stores shopping cart contents
     *
     * Supports both guest carts (via session_id) and authenticated user carts.
     * Cart items are linked to specific product variants for accurate pricing.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id', 100)->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Cached price at time of adding to cart
            $table->timestamps();

            $table->index('user_id');
            $table->index('session_id');
            $table->index(['user_id', 'product_id'], 'cart_user_product_variant');
            $table->index(['session_id', 'product_id'], 'cart_session_product_variant');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};