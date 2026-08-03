<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('radiologies', function (Blueprint $table) {
            $table->text('findings')->nullable()->after('result_summary');
            $table->text('conclusion')->nullable()->after('findings');
            $table->string('report_image')->nullable()->after('conclusion');
            $table->timestamp('completed_at')->nullable()->after('report_image');
        });
    }

    public function down(): void
    {
        Schema::table('radiologies', function (Blueprint $table) {
            $table->dropColumn(['findings', 'conclusion', 'report_image', 'completed_at']);
        });
    }
};