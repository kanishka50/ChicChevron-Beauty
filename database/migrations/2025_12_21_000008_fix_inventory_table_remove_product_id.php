<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix: Remove product_id from inventory table if it still exists
     * This migration handles cleanup after partial rollback failure
     */
    public function up(): void
    {
        if (Schema::hasColumn('inventory', 'product_id')) {
            // Try to drop foreign key first
            try {
                Schema::table('inventory', function (Blueprint $table) {
                    $table->dropForeign(['product_id']);
                });
            } catch (\Exception $e) {
                // Foreign key might not exist
            }

            // Try to drop index
            try {
                Schema::table('inventory', function (Blueprint $table) {
                    $table->dropIndex(['product_id']);
                });
            } catch (\Exception $e) {
                // Index might not exist
            }

            // Drop the column
            Schema::table('inventory', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }

        // Ensure unique_variant constraint exists
        try {
            Schema::table('inventory', function (Blueprint $table) {
                $table->unique('product_variant_id', 'unique_variant');
            });
        } catch (\Exception $e) {
            // Already exists
        }
    }

    public function down(): void
    {
        // No rollback - this is a fix migration
    }
};
