<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('complaint_type', ['product_not_received', 'wrong_product', 'damaged_product', 'other']);
            $table->string('subject');
            $table->text('description');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->integer('priority')->default(0); // 0 = normal, 1 = high
            $table->timestamps();
            
            $table->index('ticket_number');
            $table->index('user_id');
            $table->index('order_id');
            $table->index('status');
            $table->index('complaint_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};