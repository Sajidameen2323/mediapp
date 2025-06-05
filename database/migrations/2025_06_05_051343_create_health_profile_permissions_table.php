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
        Schema::create('health_profile_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_granted')->default(false);
            $table->timestamp('granted_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Ensure unique patient-doctor combinations
            $table->unique(['patient_id', 'doctor_id']);
            
            // Add indexes for performance
            $table->index(['patient_id', 'is_granted']);
            $table->index(['doctor_id', 'is_granted']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_profile_permissions');
    }
};
