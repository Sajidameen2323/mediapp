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
        Schema::table('appointments', function (Blueprint $table) {
            // Add rating tracking fields
            $table->boolean('is_rated')->default(false)->after('completion_notes');
            $table->timestamp('rated_at')->nullable()->after('is_rated');
            $table->unsignedBigInteger('doctor_rating_id')->nullable()->after('rated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['is_rated', 'rated_at', 'doctor_rating_id']);
        });
    }
};
