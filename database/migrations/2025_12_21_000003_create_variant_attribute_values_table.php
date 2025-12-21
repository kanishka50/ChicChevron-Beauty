<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Variant attribute values table - links variants to their attribute options
     *
     * Example: Variant "50ml - Rose" is linked to option "50ml" and option "Rose"
     */
    public function up(): void
    {
        Schema::create('variant_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('attribute_option_id')->constrained('attribute_options')->onDelete('cascade');
            $table->timestamp('created_at')->nullable();

            // Each variant can only have one option per attribute
            $table->unique(['product_variant_id', 'attribute_option_id'], 'unique_variant_option');
            $table->index('product_variant_id');
            $table->index('attribute_option_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_attribute_values');
    }
};
