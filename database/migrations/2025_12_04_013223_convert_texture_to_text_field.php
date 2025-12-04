<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Converts texture from a foreign key relationship to a simple text field.
     * This simplifies the system since textures are just labels (Cream, Liquid, etc.)
     */
    public function up(): void
    {
        // Step 1: Add texture text column to products table
        Schema::table('products', function (Blueprint $table) {
            $table->string('texture', 100)->nullable()->after('texture_id');
        });

        // Step 2: Migrate existing texture data from textures table
        DB::statement("
            UPDATE products p
            INNER JOIN textures t ON p.texture_id = t.id
            SET p.texture = t.name
        ");

        // Step 3: Drop the foreign key and texture_id column
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['texture_id']);
            $table->dropColumn('texture_id');
        });

        // Step 4: Drop the textures table
        Schema::dropIfExists('textures');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate textures table
        Schema::create('textures', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add texture_id back to products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('texture_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
        });

        // Migrate unique texture values back to textures table and update products
        $textures = DB::table('products')
            ->whereNotNull('texture')
            ->distinct()
            ->pluck('texture');

        foreach ($textures as $textureName) {
            $textureId = DB::table('textures')->insertGetId([
                'name' => $textureName,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('products')
                ->where('texture', $textureName)
                ->update(['texture_id' => $textureId]);
        }

        // Drop the texture text column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('texture');
        });
    }
};
