<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pharmacy_sales')) {
            return;
        }

        Schema::create('pharmacy_sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_id', 50)->unique();
            $table->string('sale_type', 30)->default('prescription')->index();
            $table->foreignId('pharmacy_id')->nullable()->constrained('pharmacies')->cascadeOnUpdate()->nullOnDelete();
            $table->string('inventory_item_id', 50)->index();
            $table->string('prescription_id', 50)->nullable()->index();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->cascadeOnUpdate()->nullOnDelete();
            $table->string('medicine_name');
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('payment_status', 20)->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('sold_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();

            $table->foreign('inventory_item_id')
                ->references('item_id')
                ->on('inventories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_sales');
    }
};
