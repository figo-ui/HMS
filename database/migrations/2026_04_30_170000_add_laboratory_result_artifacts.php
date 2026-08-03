<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('laboratories')) {
            return;
        }

        Schema::table('laboratories', function (Blueprint $table) {
            if (! Schema::hasColumn('laboratories', 'result_data')) {
                $table->json('result_data')->nullable()->after('result_value');
            }

            if (! Schema::hasColumn('laboratories', 'report_path')) {
                $table->string('report_path')->nullable()->after('notes');
            }

            if (! Schema::hasColumn('laboratories', 'lab_report_path')) {
                $table->string('lab_report_path')->nullable()->after('report_path');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('laboratories')) {
            return;
        }

        Schema::table('laboratories', function (Blueprint $table) {
            foreach (['lab_report_path', 'report_path', 'result_data'] as $column) {
                if (Schema::hasColumn('laboratories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
