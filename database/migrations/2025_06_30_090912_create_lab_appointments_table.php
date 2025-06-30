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
        Schema::create('lab_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('laboratory_id')->constrained('laboratories')->onDelete('cascade');
            $table->foreignId('lab_test_request_id')->constrained('lab_test_requests')->onDelete('cascade');
            
            // Appointment details
            $table->string('appointment_number')->unique();
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes')->default(30);
            
            // Status and management
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('patient_notes')->nullable();
            $table->text('lab_instructions')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Booking and confirmation details
            $table->timestamp('booked_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancelled_by')->nullable(); // patient, laboratory, admin
            $table->text('cancellation_reason')->nullable();
            
            // Cost details
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('final_cost', 10, 2)->nullable();
            
            // Special requirements
            $table->boolean('requires_fasting')->default(false);
            $table->integer('fasting_hours')->nullable();
            $table->text('special_instructions')->nullable();
            
            // Results
            $table->json('test_results')->nullable();
            $table->text('result_notes')->nullable();
            $table->timestamp('result_uploaded_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['patient_id', 'status']);
            $table->index(['laboratory_id', 'appointment_date']);
            $table->index(['appointment_date', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_appointments');
    }
};
