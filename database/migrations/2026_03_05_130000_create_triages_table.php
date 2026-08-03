<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('triages')) {
            return;
        }

        Schema::create('triages', function (Blueprint $table) {
            $table->id();
            $table->string('triage_id', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('nurse_id')->nullable()->constrained('nurses')->cascadeOnUpdate()->nullOnDelete();
            $table->string('encounter_id', 50)->nullable()->index();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('chief_complaint', 255)->nullable();
            $table->string('temperature', 20)->nullable();
            $table->string('blood_pressure', 20)->nullable();
            $table->unsignedSmallInteger('pulse_rate')->nullable();
            $table->unsignedSmallInteger('respiratory_rate')->nullable();
            $table->unsignedSmallInteger('oxygen_saturation')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['waiting', 'in_progress', 'completed', 'sent_to_opd'])->default('waiting');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('triages');
    }
};
