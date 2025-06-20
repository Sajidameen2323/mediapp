<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            // Update the status column to accommodate new status values
            DB::statement("ALTER TABLE prescriptions MODIFY COLUMN status ENUM('pending', 'active', 'partial', 'completed', 'dispensed', 'cancelled', 'expired') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            // Rollback to original status values
            DB::statement("ALTER TABLE prescriptions MODIFY COLUMN status ENUM('pending', 'partial', 'dispensed', 'cancelled') DEFAULT 'pending'");
        });
    }
};
