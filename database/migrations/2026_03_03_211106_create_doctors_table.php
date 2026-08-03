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
        if (Schema::hasTable('doctors')) {
            return;
        }

        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id', 50)->unique();
            $table->string('full_name');
            $table->string('specialization', 120)->nullable();
            $table->string('license_no', 80)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('department', 120)->nullable();
            $table->json('availability_schedule')->nullable();
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
