<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('radiologies')) {
            return;
        }

        Schema::create('radiologies', function (Blueprint $table) {
            $table->id();
            $table->string('radiology_id', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->cascadeOnUpdate()->nullOnDelete();
            $table->string('exam_name', 120)->nullable();
            $table->string('modality', 60)->nullable();
            $table->date('exam_date')->nullable();
            $table->text('result_summary')->nullable();
            $table->enum('result_status', ['normal', 'abnormal', 'critical', 'pending'])->nullable();
            $table->enum('status', ['ordered', 'in_progress', 'completed', 'cancelled'])->default('ordered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radiologies');
    }
};
