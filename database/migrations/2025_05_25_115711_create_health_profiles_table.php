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
        Schema::create('health_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('blood_type')->nullable();
            $table->decimal('height', 5, 2)->nullable()->comment('Height in cm');
            $table->decimal('weight', 5, 2)->nullable()->comment('Weight in kg');
            $table->text('allergies')->nullable();
            $table->text('medications')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->text('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->text('family_history')->nullable();
            $table->text('lifestyle_notes')->nullable();
            $table->text('dietary_restrictions')->nullable();
            $table->boolean('is_smoker')->default(false);
            $table->boolean('is_alcohol_consumer')->default(false);
            $table->string('exercise_frequency')->nullable();
            $table->text('additional_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_profiles');
    }
};
