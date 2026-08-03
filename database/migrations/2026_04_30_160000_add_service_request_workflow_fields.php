<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_requests')) {
            Schema::table('service_requests', function (Blueprint $table) {
                if (Schema::hasColumn('service_requests', 'visit_id')) {
                    $table->dropConstrainedForeignId('visit_id');
                }

                if (! Schema::hasColumn('service_requests', 'encounter_type')) {
                    $table->string('encounter_type', 20)->nullable()->after('patient_id');
                }

                if (! Schema::hasColumn('service_requests', 'encounter_id')) {
                    $table->string('encounter_id', 50)->nullable()->after('encounter_type');
                }
            });
        }

        if (Schema::hasTable('laboratories')) {
            Schema::table('laboratories', function (Blueprint $table) {
                if (! Schema::hasColumn('laboratories', 'service_request_id')) {
                    $table->foreignId('service_request_id')
                        ->nullable()
                        ->after('doctor_id')
                        ->constrained('service_requests')
                        ->nullOnDelete();
                }

                if (! Schema::hasColumn('laboratories', 'encounter_type')) {
                    $table->string('encounter_type', 20)->nullable()->after('service_request_id');
                }

                if (! Schema::hasColumn('laboratories', 'encounter_id')) {
                    $table->string('encounter_id', 50)->nullable()->after('encounter_type');
                }
            });
        }

        if (Schema::hasTable('radiologies')) {
            Schema::table('radiologies', function (Blueprint $table) {
                if (! Schema::hasColumn('radiologies', 'service_request_id')) {
                    $table->foreignId('service_request_id')
                        ->nullable()
                        ->after('doctor_id')
                        ->constrained('service_requests')
                        ->nullOnDelete();
                }

                if (! Schema::hasColumn('radiologies', 'encounter_type')) {
                    $table->string('encounter_type', 20)->nullable()->after('service_request_id');
                }

                if (! Schema::hasColumn('radiologies', 'encounter_id')) {
                    $table->string('encounter_id', 50)->nullable()->after('encounter_type');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('radiologies')) {
            Schema::table('radiologies', function (Blueprint $table) {
                if (Schema::hasColumn('radiologies', 'service_request_id')) {
                    $table->dropConstrainedForeignId('service_request_id');
                }

                foreach (['encounter_type', 'encounter_id'] as $column) {
                    if (Schema::hasColumn('radiologies', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('laboratories')) {
            Schema::table('laboratories', function (Blueprint $table) {
                if (Schema::hasColumn('laboratories', 'service_request_id')) {
                    $table->dropConstrainedForeignId('service_request_id');
                }

                foreach (['encounter_type', 'encounter_id'] as $column) {
                    if (Schema::hasColumn('laboratories', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('service_requests')) {
            Schema::table('service_requests', function (Blueprint $table) {
                foreach (['encounter_type', 'encounter_id'] as $column) {
                    if (Schema::hasColumn('service_requests', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
