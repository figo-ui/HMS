<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pharmacy_movements')) {
            return;
        }

        Schema::create('pharmacy_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('movement_type', ['dispense', 'receive', 'loss_adjustment', 'transfer', 'disposal']);
            $table->enum('direction', ['in', 'out', 'adjust']);
            $table->unsignedInteger('quantity');
            $table->foreignId('patient_id')->nullable()->constrained('patients')->cascadeOnUpdate()->nullOnDelete();
            $table->string('prescription_id', 50)->nullable()->index();
            $table->string('reference', 120)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_movements');
    }
};
