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
        Schema::table('doctor_holidays', function (Blueprint $table) {
            $table->text('description')->nullable()->after('reason');
            $table->text('admin_notes')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('admin_notes');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_by');
            $table->timestamp('approved_at')->nullable()->after('rejected_by');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_holidays', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'description',
                'admin_notes',
                'approved_by',
                'rejected_by',
                'approved_at',
                'rejected_at'
            ]);
        });
    }
};
