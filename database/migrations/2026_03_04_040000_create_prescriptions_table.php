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
        if (Schema::hasTable('prescriptions')) {
            return;
        }

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_id', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('encounter_type', ['OPD', 'IPD'])->nullable();
            $table->string('encounter_id', 50)->nullable()->index();
            $table->date('prescribed_date');
            $table->text('diagnosis')->nullable();
            $table->json('medications')->nullable();
            $table->text('instructions')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'dispensed', 'expired', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
