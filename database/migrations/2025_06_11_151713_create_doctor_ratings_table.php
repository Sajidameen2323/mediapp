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
        Schema::create('doctor_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            
            // Rating details
            $table->tinyInteger('rating')->unsigned()->comment('Rating from 1 to 5 stars');
            $table->text('review')->nullable();
            
            // Spam prevention
            $table->string('patient_ip')->nullable();
            $table->string('user_agent')->nullable();
            
            // Status tracking
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_published')->default(true);
            $table->text('admin_notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['doctor_id', 'rating']);
            $table->index(['doctor_id', 'is_published']);
            $table->index(['patient_id', 'doctor_id']);
            $table->index(['appointment_id']);
            
            // Ensure one rating per appointment
            $table->unique(['appointment_id']);
            
            // Prevent multiple ratings from same patient to same doctor on same day
            $table->index(['patient_id', 'doctor_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_ratings');
    }
};
