<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('message');
            $table->boolean('is_admin_response')->default(false);
            $table->timestamps();
            
            $table->index('complaint_id');
            $table->index('admin_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_responses');
    }
};