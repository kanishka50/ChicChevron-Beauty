<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variant_combinations', function (Blueprint $table) {
            $table->decimal('discount_price', 10, 2)->nullable()->after('combination_price');
        });
    }

    public function down(): void
    {
        Schema::table('variant_combinations', function (Blueprint $table) {
            $table->dropColumn('discount_price');
        });
    }
};