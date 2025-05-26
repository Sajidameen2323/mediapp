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
        Schema::create('appointment_configs', function (Blueprint $table) {
            $table->id();
            
            // Buffer times (in minutes)
            $table->integer('buffer_time_before')->default(15);
            $table->integer('buffer_time_after')->default(15);
            
            // Booking calendar settings
            $table->integer('max_booking_days_ahead')->default(30);
            $table->integer('min_booking_hours_ahead')->default(2);
            
            // Financial settings
            $table->decimal('tax_rate', 5, 4)->default(0.0000); // Up to 99.9999%
            $table->boolean('tax_enabled')->default(false);
            
            // Cancellation and rescheduling
            $table->integer('cancellation_hours_limit')->default(24);
            $table->integer('reschedule_hours_limit')->default(24);
            $table->boolean('allow_cancellation')->default(true);
            $table->boolean('allow_rescheduling')->default(true);
            
            // Appointment limits
            $table->integer('max_appointments_per_patient_per_day')->default(3);
            $table->integer('max_appointments_per_doctor_per_day')->default(20);
            
            // Payment settings
            $table->json('accepted_payment_methods')->nullable(); // ['cash', 'card', 'online', 'insurance']
            $table->boolean('require_payment_on_booking')->default(false);
            $table->decimal('booking_deposit_percentage', 5, 2)->default(0.00); // 0-100%
            
            // Auto-approval settings
            $table->boolean('auto_approve_appointments')->default(true);
            $table->boolean('require_admin_approval')->default(false);
            
            // Notification settings
            $table->boolean('send_confirmation_email')->default(true);
            $table->boolean('send_reminder_email')->default(true);
            $table->integer('reminder_hours_before')->default(24);
            
            // Business operation settings
            $table->time('default_start_time')->default('09:00:00');
            $table->time('default_end_time')->default('17:00:00');
            $table->integer('default_slot_duration')->default(30); // minutes
            
            // Emergency settings
            $table->boolean('allow_emergency_bookings')->default(true);
            $table->integer('emergency_booking_hours_limit')->default(1);
            
            // System settings
            $table->boolean('is_active')->default(true);
            $table->string('timezone')->default('UTC');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_configs');
    }
};
