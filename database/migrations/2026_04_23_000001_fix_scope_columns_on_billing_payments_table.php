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

        $needsBillingScope = ! Schema::hasColumn('billing_payments', 'billing_scope');
        $needsServiceUnit = ! Schema::hasColumn('billing_payments', 'service_unit');

        if (! $needsBillingScope && ! $needsServiceUnit) {
            return;
        }

        Schema::table('billing_payments', function (Blueprint $table) use ($needsBillingScope, $needsServiceUnit) {
            if ($needsBillingScope) {
                $table->string('billing_scope', 30)->default('hospital')->after('encounter_id');
            }

            if ($needsServiceUnit) {
                $table->string('service_unit', 50)->nullable()->after($needsBillingScope ? 'billing_scope' : 'encounter_id');
            }
        });
    }

    public function down(): void
    {
        $hasBillingScope = Schema::hasColumn('billing_payments', 'billing_scope');
        $hasServiceUnit = Schema::hasColumn('billing_payments', 'service_unit');

        if (! $hasBillingScope && ! $hasServiceUnit) {
            return;
        }

        Schema::table('billing_payments', function (Blueprint $table) use ($hasBillingScope, $hasServiceUnit) {
            if ($hasServiceUnit) {
                $table->dropColumn('service_unit');
            }

            if ($hasBillingScope) {
                $table->dropColumn('billing_scope');
            }
        });
    }
};
