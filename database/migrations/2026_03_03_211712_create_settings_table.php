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
        if (Schema::hasTable('settings')) {
            return;
        }

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->json('hospital_profile')->nullable();
            $table->json('departments')->nullable();
            $table->json('service_charges')->nullable();
            $table->json('tax_rules')->nullable();
            $table->json('roles_permissions')->nullable();
            $table->json('email_sms_settings')->nullable();
            $table->json('backup_settings')->nullable();
            $table->string('audit_id', 50)->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('action', 120)->nullable();
            $table->string('module', 120)->nullable();
            $table->string('record_id', 50)->nullable();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
