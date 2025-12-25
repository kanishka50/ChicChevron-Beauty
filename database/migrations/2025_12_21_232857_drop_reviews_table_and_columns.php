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
        // Drop the reviews table
        Schema::dropIfExists('reviews');

        // Remove review-related columns from products table
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'average_rating')) {
                $table->dropColumn('average_rating');
            }
            if (Schema::hasColumn('products', 'reviews_count')) {
                $table->dropColumn('reviews_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate review-related columns in products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'average_rating')) {
                $table->decimal('average_rating', 2, 1)->default(0)->after('selling_price');
            }
            if (!Schema::hasColumn('products', 'reviews_count')) {
                $table->integer('reviews_count')->default(0)->after('average_rating');
            }
        });

        // Recreate reviews table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('rating'); // 1-5
            $table->string('title')->nullable();
            $table->text('comment');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->unique(['product_id', 'user_id']);
            $table->index('product_id');
            $table->index('user_id');
            $table->index('rating');
            $table->index('is_approved');
        });
    }
};
