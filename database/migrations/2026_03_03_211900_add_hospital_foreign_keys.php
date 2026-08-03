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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('created_by')->references('staff_id')->on('staff')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::table('o_p_d_s', function (Blueprint $table) {
            $table->foreign('bed_id')->references('bed_id')->on('beds')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::table('i_p_d_s', function (Blueprint $table) {
            $table->foreign('bed_id')->references('bed_id')->on('beds')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::table('beds', function (Blueprint $table) {
            $table->foreign('assigned_nurse')->references('staff_id')->on('staff')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::table('pharmacies', function (Blueprint $table) {
            $table->foreign('medicine_id')->references('item_id')->on('inventories')->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('generated_by')->references('staff_id')->on('staff')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['generated_by']);
        });

        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropForeign(['medicine_id']);
        });

        Schema::table('beds', function (Blueprint $table) {
            $table->dropForeign(['assigned_nurse']);
        });

        Schema::table('i_p_d_s', function (Blueprint $table) {
            $table->dropForeign(['bed_id']);
        });

        Schema::table('o_p_d_s', function (Blueprint $table) {
            $table->dropForeign(['bed_id']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
    }
};
