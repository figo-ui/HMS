<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('o_p_d_s', function (Blueprint $table) {
            if (! Schema::hasColumn('o_p_d_s', 'icd10_code')) {
                $table->string('icd10_code', 20)->nullable()->after('diagnosis');
            }

            if (! Schema::hasColumn('o_p_d_s', 'follow_up_date')) {
                $table->date('follow_up_date')->nullable()->after('discharge_date');
            }

            if (! Schema::hasColumn('o_p_d_s', 'discharge_summary')) {
                $table->text('discharge_summary')->nullable()->after('treatment_plan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('o_p_d_s', function (Blueprint $table) {
            $toDrop = [];

            if (Schema::hasColumn('o_p_d_s', 'icd10_code')) {
                $toDrop[] = 'icd10_code';
            }
            if (Schema::hasColumn('o_p_d_s', 'follow_up_date')) {
                $toDrop[] = 'follow_up_date';
            }
            if (Schema::hasColumn('o_p_d_s', 'discharge_summary')) {
                $toDrop[] = 'discharge_summary';
            }

            if ($toDrop !== []) {
                $table->dropColumn($toDrop);
            }
        });
    }
};
