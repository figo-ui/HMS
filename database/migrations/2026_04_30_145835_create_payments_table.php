<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collected_by')->constrained('users')->cascadeOnDelete();
            
            $table->enum('payment_mode', [
                'cash', 'card', 'upi', 'netbanking', 'insurance', 'mixed'
            ]);
            
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->json('split_details')->nullable();
            $table->text('remarks')->nullable();
            
            $table->timestamp('payment_date');
            $table->timestamps();
            
            $table->index(['payment_mode', 'payment_date']);
            $table->index('invoice_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};