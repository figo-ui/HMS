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
        if (Schema::hasTable('inventories') || Schema::hasTable('inventory')) {
            return;
        }

        Schema::create('inventory', function (Blueprint $table) {
            $table->id('item_id'); // Matches protected $primaryKey = 'item_id'; in Inventory model
            $table->string('item_name');
            $table->string('sku_code')->nullable()->unique(); // Stock Keeping Unit
            $table->string('category')->nullable();
            $table->integer('quantity')->default(0); // Current stock level
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable(); // For tracking specific batches
            $table->integer('reorder_level')->default(10); // Threshold for low stock alerts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
