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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('medical_report_id')->nullable()->constrained('medical_reports')->onDelete('set null');
            $table->string('prescription_number')->unique();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'dispensed', 'partial', 'cancelled'])->default('pending');
            $table->date('prescribed_date');
            $table->date('valid_until')->nullable();
            $table->boolean('is_repeatable')->default(false);
            $table->integer('refills_allowed')->default(0);
            $table->integer('refills_remaining')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
