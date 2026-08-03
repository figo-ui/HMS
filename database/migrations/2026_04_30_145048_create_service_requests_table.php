<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('encounter_type', 20)->nullable();
            $table->string('encounter_id', 50)->nullable();
            $table->foreignId('service_id');
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->enum('payment_status', [
                'pending', 'verified', 'paid', 'insurance', 'waived', 'cancelled'
            ])->default('pending');
            
            $table->enum('fulfillment_status', [
                'requested', 'in_progress', 'completed', 'delivered', 'cancelled'
            ])->default('requested');
            
            $table->decimal('total_amount', 10, 2);
            $table->decimal('patient_share', 10, 2);
            $table->decimal('insurance_share', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            
            $table->timestamp('requested_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['payment_status', 'fulfillment_status']);
            $table->index(['encounter_type', 'encounter_id']);
            $table->index('request_number');
            $table->index('requested_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_requests');
    }
};
