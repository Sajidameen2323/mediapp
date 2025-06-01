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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            
            // Appointment details
            $table->string('appointment_number')->unique(); // Generated appointment number
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes');
            
            // Patient information
            // Removed: $table->string('patient_name');
            // Removed: $table->string('patient_phone');
            // Removed: $table->string('patient_email')->nullable();
            // Removed: $table->text('patient_notes')->nullable();

            // Appointment request details
            $table->string('reason', 500);
            $table->string('symptoms', 1000)->nullable();
            $table->string('notes', 500)->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->enum('appointment_type', ['consultation', 'follow_up', 'check_up', 'emergency']);
            
            // Appointment status and management
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'no_show'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'refunded'])->default('pending');
            $table->decimal('total_amount', 8, 2);
            $table->decimal('paid_amount', 8, 2)->default(0.00);
            $table->decimal('tax_amount', 8, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable();
            
            // Booking details
            $table->timestamp('booked_at');
            $table->enum('booking_source', ['online', 'phone', 'walk_in', 'admin'])->default('online');
            $table->text('special_instructions')->nullable();
            
            // Cancellation and rescheduling
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancelled_by')->nullable(); // doctor, patient, admin
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('rescheduled_from')->nullable()->references('id')->on('appointments');
            
            // Completion details
            $table->timestamp('completed_at')->nullable();
            $table->text('completion_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['doctor_id', 'appointment_date']);
            $table->index(['patient_id', 'appointment_date']);
            $table->index(['appointment_date', 'start_time']);
            $table->index(['status', 'appointment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
