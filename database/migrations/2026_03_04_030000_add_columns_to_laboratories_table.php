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
        Schema::table('laboratories', function (Blueprint $table) {
            $table->string('lab_id', 50)->nullable()->unique()->after('id');
            $table->foreignId('patient_id')->nullable()->constrained('patients')->cascadeOnUpdate()->nullOnDelete()->after('lab_id');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->cascadeOnUpdate()->nullOnDelete()->after('patient_id');
            $table->string('test_name')->nullable()->after('doctor_id');
            $table->string('test_type', 120)->nullable()->after('test_name');
            $table->string('sample_type', 80)->nullable()->after('test_type');
            $table->date('test_date')->nullable()->after('sample_type');
            $table->date('result_date')->nullable()->after('test_date');
            $table->text('result_value')->nullable()->after('result_date');
            $table->enum('result_status', ['normal', 'abnormal', 'critical', 'pending'])->nullable()->after('result_value');
            $table->string('normal_range', 120)->nullable()->after('result_status');
            $table->decimal('cost', 10, 2)->default(0)->after('normal_range');
            $table->text('notes')->nullable()->after('cost');
            $table->enum('status', ['ordered', 'sample_collected', 'processing', 'completed', 'cancelled'])->default('ordered')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);

            $table->dropColumn([
                'lab_id',
                'patient_id',
                'doctor_id',
                'test_name',
                'test_type',
                'sample_type',
                'test_date',
                'result_date',
                'result_value',
                'result_status',
                'normal_range',
                'cost',
                'notes',
                'status',
            ]);
        });
    }
};
