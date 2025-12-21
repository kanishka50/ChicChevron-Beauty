<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Product attributes table - stores which attributes a product uses
     *
     * Example: Perfume product has 'size' and 'scent' attributes
     */
    public function up(): void
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('attribute_name', 50); // 'size', 'color', 'scent', 'shade', 'finish', 'type'
            $table->integer('display_order')->default(0);
            $table->timestamps();

            // Each product can only have one of each attribute type
            $table->unique(['product_id', 'attribute_name'], 'unique_product_attribute');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
