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
        if (Schema::hasTable('o_p_d_s')) {
            return;
        }

        Schema::create('o_p_d_s', function (Blueprint $table) {
            $table->id();
            $table->string('encounter_id', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('type', ['OPD', 'IPD'])->default('OPD');
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('department', 120)->nullable();
            $table->text('diagnosis')->nullable();
            $table->date('admission_date')->nullable();
            $table->date('discharge_date')->nullable();
            $table->string('bed_id', 50)->nullable();
            $table->text('treatment_plan')->nullable();
            $table->string('prescription_id', 50)->nullable();
            $table->enum('status', ['open', 'closed', 'transferred'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('o_p_d_s');
    }
};
