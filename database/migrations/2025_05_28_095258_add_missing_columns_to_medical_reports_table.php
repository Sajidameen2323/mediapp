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
        Schema::table('medical_reports', function (Blueprint $table) {
            $table->text('lab_tests_ordered')->nullable()->after('vital_signs');
            $table->text('imaging_studies')->nullable()->after('lab_tests_ordered');
            $table->string('priority_level')->default('routine')->after('imaging_studies');
            $table->string('follow_up_required')->nullable()->after('priority_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_reports', function (Blueprint $table) {
            $table->dropColumn([
                'lab_tests_ordered',
                'imaging_studies',
                'priority_level',
                'follow_up_required'
            ]);
        });
    }
};
