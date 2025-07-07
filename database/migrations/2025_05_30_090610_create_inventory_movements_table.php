<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventory')->onDelete('cascade');
            $table->string('batch_number', 100);
            $table->enum('movement_type', ['in', 'out', 'adjustment']);
            $table->integer('quantity');
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->string('reason')->nullable();
            $table->string('reference_type', 50)->nullable(); // 'order', 'return', 'adjustment'
            $table->bigInteger('reference_id')->nullable();
            $table->dateTime('movement_date');
            $table->timestamps();
            
            $table->index('inventory_id');
            $table->index('batch_number');
            $table->index('movement_type');
            $table->index('movement_date');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};