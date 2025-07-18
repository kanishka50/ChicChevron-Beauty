<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_type', 50); // 'admin', 'user', 'system'
            $table->bigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('properties')->nullable(); // Store additional data as JSON
            $table->string('subject_type')->nullable(); // For polymorphic relation
            $table->bigInteger('subject_id')->nullable(); // For polymorphic relation
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('log_type');
            $table->index('user_id');
            $table->index('action');
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};