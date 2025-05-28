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
        Schema::create('medical_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->string('report_type'); // e.g., "Consultation", "Follow-up", "Diagnosis"
            $table->date('consultation_date');
            $table->text('chief_complaint')->nullable();
            $table->text('history_of_present_illness')->nullable();
            $table->text('physical_examination')->nullable();
            $table->text('assessment_diagnosis')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->text('medications_prescribed')->nullable();
            $table->text('follow_up_instructions')->nullable();
            $table->text('additional_notes')->nullable();
            $table->json('vital_signs')->nullable(); // blood pressure, temperature, etc.
            $table->string('status')->default('draft'); // draft, completed, sent
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['doctor_id', 'patient_id']);
            $table->index(['doctor_id', 'consultation_date']);
            $table->index(['patient_id', 'consultation_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_reports');
    }
};
