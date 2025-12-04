<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Removes banners table - banners are now hardcoded in blade view
     */
    public function up(): void
    {
        Schema::dropIfExists('banners');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image_desktop');
            $table->string('image_mobile')->nullable();
            $table->enum('link_type', ['none', 'product', 'category', 'brand', 'custom'])->default('none');
            $table->string('link_value')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('sort_order');
        });
    }
};
