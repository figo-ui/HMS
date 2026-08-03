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
        if (Schema::hasTable('nurses')) {
            return;
        }

        Schema::create('nurses', function (Blueprint $table) {
            $table->id();
            $table->string('nurse_id', 50)->unique();
            $table->string('full_name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable()->unique();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->string('license_no', 80)->nullable()->unique();
            $table->enum('shift', ['day', 'night', 'rotational', 'custom'])->default('day');
            $table->date('join_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->string('emergency_contact', 120)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurses');
    }
};
