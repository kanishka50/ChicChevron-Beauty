<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Attribute options table - stores option values for each attribute
     *
     * Example: Size attribute has options '50ml', '100ml'
     */
    public function up(): void
    {
        Schema::create('attribute_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_attribute_id')->constrained('product_attributes')->onDelete('cascade');
            $table->string('value', 100); // '50ml', 'Red', 'Rose', etc.
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('product_attribute_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_options');
    }
};
