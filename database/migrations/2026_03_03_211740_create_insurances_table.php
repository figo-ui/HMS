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
        if (Schema::hasTable('insurances')) {
            return;
        }

        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('policy_id', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('provider_name', 120);
            $table->string('policy_no', 80)->unique();
            $table->decimal('coverage_limit', 12, 2)->default(0);
            $table->decimal('co_pay', 10, 2)->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->string('claim_id', 50)->nullable();
            $table->enum('claim_status', ['submitted', 'under_review', 'approved', 'rejected', 'settled'])->default('submitted');
            $table->decimal('approved_amount', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
