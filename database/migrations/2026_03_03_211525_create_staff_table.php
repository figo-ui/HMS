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
        if (Schema::hasTable('staff')) {
            return;
        }

        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id', 50)->unique();
            $table->string('full_name');
            $table->string('role', 120);
            $table->string('department', 120)->nullable();
            $table->enum('shift', ['day', 'night', 'rotational', 'custom'])->default('day');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable()->unique();
            $table->date('join_date')->nullable();
            $table->decimal('salary', 12, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'on_leave', 'terminated'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
