<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add customer_email field after user_id
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->after('user_id')->nullable();
                $table->index('customer_email');
            }
            
            // Add payment_reference if it doesn't exist (from your existing migration)
            if (!Schema::hasColumn('orders', 'payment_reference')) {
                // This is already in your existing migration, but keeping for completeness
                // The field already exists as per your orders table migration
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'customer_email')) {
                $table->dropIndex(['customer_email']);
                $table->dropColumn('customer_email');
            }
        });
    }
};