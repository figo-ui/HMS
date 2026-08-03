<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('inventory_transactions')) {
            return;
        }

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_id', 50)->index();
            $table->integer('quantity');
            $table->string('operation_type', 30)->index();
            $table->string('reference_id', 120)->nullable()->index();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();

            $table->foreign('inventory_id')
                ->references('item_id')
                ->on('inventories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
