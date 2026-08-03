<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('billing_payments')) {
            return;
        }

        Schema::table('billing_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('billing_payments', 'billing_scope')) {
                $table->string('billing_scope', 30)->default('hospital')->after('encounter_id');
            }

            if (! Schema::hasColumn('billing_payments', 'service_unit')) {
                $table->string('service_unit', 50)->nullable()->after('billing_scope');
            }
        });
    }

    public function down(): void
    {
        Schema::table('billing_payments', function (Blueprint $table) {
            if (Schema::hasColumn('billing_payments', 'service_unit')) {
                $table->dropColumn('service_unit');
            }

            if (Schema::hasColumn('billing_payments', 'billing_scope')) {
                $table->dropColumn('billing_scope');
            }
        });
    }
};
