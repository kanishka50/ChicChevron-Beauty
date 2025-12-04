<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Removes PayHere payment gateway related columns and tables
     * Converts system to Cash on Delivery (COD) only
     */
    public function up(): void
    {
        // 1. Update all existing orders to COD payment method
        DB::table('orders')
            ->where('payment_method', 'payhere')
            ->update(['payment_method' => 'cod']);

        // 2. Update orders status enum to remove payment_completed (no longer needed for COD)
        // First update any payment_completed orders to processing
        DB::table('orders')
            ->where('status', 'payment_completed')
            ->update(['status' => 'processing']);

        // 3. Drop PayHere-specific columns from orders table
        Schema::table('orders', function (Blueprint $table) {
            // Drop payment token related columns
            if (Schema::hasColumn('orders', 'payment_token')) {
                $table->dropColumn('payment_token');
            }
            if (Schema::hasColumn('orders', 'payment_initiated_at')) {
                $table->dropColumn('payment_initiated_at');
            }
            if (Schema::hasColumn('orders', 'webhook_received_at')) {
                $table->dropColumn('webhook_received_at');
            }
            if (Schema::hasColumn('orders', 'payment_verified')) {
                $table->dropColumn('payment_verified');
            }
            if (Schema::hasColumn('orders', 'verification_attempts')) {
                $table->dropColumn('verification_attempts');
            }
            if (Schema::hasColumn('orders', 'payment_session_data')) {
                $table->dropColumn('payment_session_data');
            }
        });

        // 4. Drop webhook_calls table (used for PayHere webhooks logging)
        Schema::dropIfExists('webhook_calls');

        // 5. Drop payment_logs table if exists
        Schema::dropIfExists('payment_logs');

        // 6. Update payment_method enum to only allow 'cod'
        // MySQL specific - modify the enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cod') DEFAULT 'cod'");

        // 7. Update status enum to remove 'payment_completed' and 'pending'
        // New flow: processing -> shipping -> completed | cancelled
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('processing', 'shipping', 'completed', 'cancelled') DEFAULT 'processing'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore payment_method enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('payhere', 'cod') DEFAULT 'cod'");

        // Restore status enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'payment_completed', 'processing', 'shipping', 'completed', 'cancelled') DEFAULT 'pending'");

        // Restore payment token columns
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_token')->nullable()->after('payment_reference');
            $table->timestamp('payment_initiated_at')->nullable()->after('payment_token');
            $table->timestamp('webhook_received_at')->nullable()->after('payment_initiated_at');
            $table->boolean('payment_verified')->default(false)->after('webhook_received_at');
            $table->integer('verification_attempts')->default(0)->after('payment_verified');
            $table->json('payment_session_data')->nullable()->after('verification_attempts');
        });

        // Recreate webhook_calls table
        Schema::create('webhook_calls', function (Blueprint $table) {
            $table->id();
            $table->string('merchant_id')->nullable();
            $table->string('status_code')->nullable();
            $table->string('md5_signature')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        // Recreate payment_logs table
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method');
            $table->string('status');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();
        });
    }
};
