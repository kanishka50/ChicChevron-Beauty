<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove columns that are no longer needed:
     * - sku: Now on variants only
     * - fragrance: Now a variant attribute (scent)
     * - has_variants: All products have variants now
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop index first if exists
            $table->dropIndex(['sku']);

            // Remove columns
            $table->dropColumn(['sku', 'fragrance', 'has_variants']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku', 100)->unique()->after('description');
            $table->string('fragrance', 100)->nullable()->after('suitable_for');
            $table->boolean('has_variants')->default(false)->after('fragrance');

            $table->index('sku');
        });
    }
};
