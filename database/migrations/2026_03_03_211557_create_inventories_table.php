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
        if (Schema::hasTable('inventories')) {
            return;
        }

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('item_id', 50)->unique();
            $table->string('item_name');
            $table->string('category', 100)->nullable()->index();
            $table->string('unit', 30)->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('reorder_level')->default(0);
            $table->string('supplier_id', 50)->nullable();
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->date('expiry_date')->nullable();
            $table->string('store_location', 120)->nullable();
            $table->json('stock_movement_log')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
