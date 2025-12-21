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
        // Drop tables in correct order (child tables first due to foreign keys)
        Schema::dropIfExists('promotion_usage');
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('promotions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to recreate these tables
        // Promotion system has been removed from the project
    }
};
