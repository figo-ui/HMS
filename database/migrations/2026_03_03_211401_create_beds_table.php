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
        if (Schema::hasTable('beds')) {
            return;
        }

        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->string('ward_id', 50)->nullable()->index();
            $table->string('ward_name', 120);
            $table->string('bed_id', 50)->unique();
            $table->string('bed_no', 50);
            $table->enum('bed_type', ['general', 'semi_private', 'private', 'icu', 'emergency'])->default('general');
            $table->decimal('charge_per_day', 10, 2)->default(0);
            $table->enum('occupancy_status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->foreignId('current_patient_id')->nullable()->constrained('patients')->cascadeOnUpdate()->nullOnDelete();
            $table->string('assigned_nurse', 50)->nullable();
            $table->timestamp('last_cleaned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
