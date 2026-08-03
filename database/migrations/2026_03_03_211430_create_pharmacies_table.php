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
        if (Schema::hasTable('pharmacies')) {
            return;
        }

        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_id', 50)->index();
            $table->string('medicine_name');
            $table->string('batch_no', 50)->nullable()->index();
            $table->date('expiry_date')->nullable();
            $table->unsignedInteger('stock_qty')->default(0);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->string('supplier_id', 50)->nullable();
            $table->unsignedInteger('reorder_level')->default(0);
            $table->string('prescription_sale_id', 50)->nullable();
            $table->foreignId('issued_to_patient_id')->nullable()->constrained('patients')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
