<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('service_type', [
                'lab', 'radiology', 'pharmacy', 'consultation', 
                'physiotherapy', 'surgery', 'bed_charges', 'emergency'
            ]);
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->decimal('insurance_coverage_percent', 5, 2)->default(0);
            $table->boolean('requires_pre_auth')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['service_type', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};