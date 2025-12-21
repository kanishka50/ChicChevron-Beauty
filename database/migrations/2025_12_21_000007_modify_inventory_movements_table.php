<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Restructure inventory_movements for simpler IN/OUT tracking:
     * - Add product_variant_id (direct link to variant)
     * - Add supplier column
     * - Add order_id column
     * - Change movement_type enum values
     * - Remove batch_number, reference_type, inventory_id
     * - Rename reason to notes
     */
    public function up(): void
    {
        // Check if we're resuming from a partial migration
        $hasProductVariantId = Schema::hasColumn('inventory_movements', 'product_variant_id');
        $hasOrderId = Schema::hasColumn('inventory_movements', 'order_id');
        $hasSupplier = Schema::hasColumn('inventory_movements', 'supplier');
        $hasInventoryId = Schema::hasColumn('inventory_movements', 'inventory_id');
        $hasType = Schema::hasColumn('inventory_movements', 'type');
        $hasMovementType = Schema::hasColumn('inventory_movements', 'movement_type');
        $hasNotes = Schema::hasColumn('inventory_movements', 'notes');
        $hasReason = Schema::hasColumn('inventory_movements', 'reason');

        // Step 1: Add new columns if they don't exist
        if (!$hasProductVariantId || !$hasOrderId || !$hasSupplier) {
            Schema::table('inventory_movements', function (Blueprint $table) use ($hasProductVariantId, $hasOrderId, $hasSupplier) {
                if (!$hasProductVariantId) {
                    $table->unsignedBigInteger('product_variant_id')->nullable()->after('id');
                }
                if (!$hasOrderId) {
                    $table->unsignedBigInteger('order_id')->nullable()->after('cost_per_unit');
                }
                if (!$hasSupplier) {
                    $table->string('supplier', 255)->nullable()->after('order_id');
                }
            });
        }

        // Step 2: Migrate data - get product_variant_id from inventory table
        if ($hasInventoryId) {
            DB::statement('
                UPDATE inventory_movements im
                JOIN inventory i ON im.inventory_id = i.id
                SET im.product_variant_id = i.product_variant_id
                WHERE im.product_variant_id IS NULL
            ');
        }

        // Step 3: Make product_variant_id NOT NULL (without foreign key constraint issues)
        // First drop the FK if it exists
        $this->dropForeignIfExists('inventory_movements', 'inventory_movements_product_variant_id_foreign');

        // Modify the column to NOT NULL
        DB::statement('ALTER TABLE inventory_movements MODIFY product_variant_id BIGINT UNSIGNED NOT NULL');

        // Re-add the foreign key
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->onDelete('cascade');
        });

        // Add order_id foreign key if not exists
        $this->addForeignIfNotExists('inventory_movements', 'order_id', 'orders', 'id', 'set null');

        // Step 4: Rename reason to notes if not already done
        if ($hasReason && !$hasNotes) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->renameColumn('reason', 'notes');
            });
        }

        // Step 5: Drop old columns and indexes if they exist
        if ($hasInventoryId) {
            // Drop indexes first
            $this->dropIndexIfExists('inventory_movements', 'inventory_movements_inventory_id_index');
            $this->dropIndexIfExists('inventory_movements', 'inventory_movements_batch_number_index');
            $this->dropIndexIfExists('inventory_movements', 'inventory_movements_reference_type_reference_id_index');

            // Drop foreign key
            $this->dropForeignIfExists('inventory_movements', 'inventory_movements_inventory_id_foreign');

            // Drop columns
            Schema::table('inventory_movements', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('inventory_movements', 'inventory_id')) $columns[] = 'inventory_id';
                if (Schema::hasColumn('inventory_movements', 'batch_number')) $columns[] = 'batch_number';
                if (Schema::hasColumn('inventory_movements', 'reference_type')) $columns[] = 'reference_type';
                if (Schema::hasColumn('inventory_movements', 'reference_id')) $columns[] = 'reference_id';
                if (Schema::hasColumn('inventory_movements', 'movement_date')) $columns[] = 'movement_date';
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        // Step 6: Add new type enum if it doesn't exist
        if (!$hasType && $hasMovementType) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->enum('type', ['in', 'reserved', 'sold', 'released', 'adjustment'])
                    ->after('product_variant_id');
            });

            // Map old values to new values
            DB::statement("
                UPDATE inventory_movements
                SET type = CASE
                    WHEN movement_type = 'in' THEN 'in'
                    WHEN movement_type = 'out' THEN 'sold'
                    WHEN movement_type = 'adjustment' THEN 'adjustment'
                    ELSE 'adjustment'
                END
            ");

            // Drop old column
            $this->dropIndexIfExists('inventory_movements', 'inventory_movements_movement_type_index');
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->dropColumn('movement_type');
            });
        }

        // Step 7: Add new indexes
        $this->addIndexIfNotExists('inventory_movements', 'product_variant_id', 'idx_product_variant_id');
        $this->addIndexIfNotExists('inventory_movements', 'type', 'idx_type');
        $this->addIndexIfNotExists('inventory_movements', 'order_id', 'idx_order_id');
        $this->addIndexIfNotExists('inventory_movements', 'created_at', 'idx_created_at');
    }

    public function down(): void
    {
        // Drop new indexes
        $this->dropIndexIfExists('inventory_movements', 'idx_product_variant_id');
        $this->dropIndexIfExists('inventory_movements', 'idx_type');
        $this->dropIndexIfExists('inventory_movements', 'idx_order_id');
        $this->dropIndexIfExists('inventory_movements', 'idx_created_at');

        // Re-add old movement_type column
        if (!Schema::hasColumn('inventory_movements', 'movement_type')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->enum('movement_type', ['in', 'out', 'adjustment'])->after('product_variant_id');
            });

            // Map back
            DB::statement("
                UPDATE inventory_movements
                SET movement_type = CASE
                    WHEN type = 'in' THEN 'in'
                    WHEN type IN ('reserved', 'sold', 'released') THEN 'out'
                    ELSE 'adjustment'
                END
            ");

            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }

        // Rename notes back to reason
        if (Schema::hasColumn('inventory_movements', 'notes')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->renameColumn('notes', 'reason');
            });
        }

        // Re-add old columns
        if (!Schema::hasColumn('inventory_movements', 'inventory_id')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->unsignedBigInteger('inventory_id')->after('id');
                $table->string('batch_number', 100)->after('inventory_id');
                $table->string('reference_type', 50)->nullable();
                $table->bigInteger('reference_id')->nullable();
                $table->dateTime('movement_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            });

            // Re-add indexes
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');
                $table->index('inventory_id');
                $table->index('batch_number');
                $table->index('movement_type');
                $table->index('movement_date');
                $table->index(['reference_type', 'reference_id']);
            });
        }

        // Drop new foreign keys and columns
        $this->dropForeignIfExists('inventory_movements', 'inventory_movements_product_variant_id_foreign');
        $this->dropForeignIfExists('inventory_movements', 'inventory_movements_order_id_foreign');

        Schema::table('inventory_movements', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('inventory_movements', 'product_variant_id')) $columns[] = 'product_variant_id';
            if (Schema::hasColumn('inventory_movements', 'order_id')) $columns[] = 'order_id';
            if (Schema::hasColumn('inventory_movements', 'supplier')) $columns[] = 'supplier';
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    private function dropForeignIfExists($table, $foreignName)
    {
        try {
            Schema::table($table, function (Blueprint $t) use ($foreignName) {
                $t->dropForeign($foreignName);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, ignore
        }
    }

    private function dropIndexIfExists($table, $indexName)
    {
        try {
            Schema::table($table, function (Blueprint $t) use ($indexName) {
                $t->dropIndex($indexName);
            });
        } catch (\Exception $e) {
            // Index doesn't exist, ignore
        }
    }

    private function addIndexIfNotExists($table, $column, $indexName)
    {
        try {
            Schema::table($table, function (Blueprint $t) use ($column, $indexName) {
                $t->index($column, $indexName);
            });
        } catch (\Exception $e) {
            // Index already exists, ignore
        }
    }

    private function addForeignIfNotExists($table, $column, $refTable, $refColumn, $onDelete)
    {
        try {
            Schema::table($table, function (Blueprint $t) use ($column, $refTable, $refColumn, $onDelete) {
                $t->foreign($column)->references($refColumn)->on($refTable)->onDelete($onDelete);
            });
        } catch (\Exception $e) {
            // Foreign key already exists, ignore
        }
    }
};
