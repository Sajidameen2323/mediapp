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
        // Update some appointments to be completed for testing rating functionality
        DB::table('appointments')
            ->where('status', '!=', 'completed')
            ->limit(2)
            ->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for testing purposes only
        // No rollback needed
    }
};
