<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Remove pricing fields from products table
            $table->dropColumn(['cost_price', 'selling_price', 'discount_price']);
            
            // Remove has_variants as ALL products will have variants
            $table->dropColumn('has_variants');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->after('texture_id');
            $table->decimal('selling_price', 10, 2)->after('cost_price');
            $table->decimal('discount_price', 10, 2)->nullable()->after('selling_price');
            $table->boolean('has_variants')->default(false)->after('fragrance');
        });
    }
};