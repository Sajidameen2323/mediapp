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
            $table->timestamp('rescheduled_at')->nullable()->after('cancelled_at');
            $table->string('rescheduled_by')->nullable()->after('rescheduled_at'); // doctor, patient, admin
            $table->text('reschedule_reason')->nullable()->after('rescheduled_by');
            $table->date('original_date')->nullable()->after('reschedule_reason');
            $table->time('original_time')->nullable()->after('original_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'rescheduled_at',
                'rescheduled_by', 
                'reschedule_reason',
                'original_date',
                'original_time'
            ]);
        });
    }
};
