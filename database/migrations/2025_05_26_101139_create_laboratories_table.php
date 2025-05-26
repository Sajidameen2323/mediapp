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
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->text('services_offered')->nullable();
            $table->time('opening_time')->default('08:00:00');
            $table->time('closing_time')->default('18:00:00');
            $table->json('working_days')->nullable(); // ['monday', 'tuesday', etc.]
            $table->decimal('consultation_fee', 8, 2)->default(0.00);
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->text('equipment_details')->nullable();
            $table->boolean('home_service_available')->default(false);
            $table->decimal('home_service_fee', 8, 2)->default(0.00);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratories');
    }
};
