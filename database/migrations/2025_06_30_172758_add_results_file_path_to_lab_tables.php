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
        // Add results_file_path to lab_appointments table
        Schema::table('lab_appointments', function (Blueprint $table) {
            $table->string('results_file_path')->nullable()->after('result_notes');
        });

        // Add results_file_path to lab_test_requests table
        Schema::table('lab_test_requests', function (Blueprint $table) {
            $table->string('results_file_path')->nullable()->after('lab_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove results_file_path from lab_appointments table
        Schema::table('lab_appointments', function (Blueprint $table) {
            $table->dropColumn('results_file_path');
        });

        // Remove results_file_path from lab_test_requests table
        Schema::table('lab_test_requests', function (Blueprint $table) {
            $table->dropColumn('results_file_path');
        });
    }
};
