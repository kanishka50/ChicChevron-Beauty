<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('ingredient_name');
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('ingredient_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_ingredients');
    }
};