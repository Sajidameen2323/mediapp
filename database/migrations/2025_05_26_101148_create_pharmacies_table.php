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
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->text('services_offered')->nullable();
            $table->time('opening_time')->default('08:00:00');
            $table->time('closing_time')->default('20:00:00');
            $table->json('working_days')->nullable(); // ['monday', 'tuesday', etc.]
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->boolean('home_delivery_available')->default(false);
            $table->decimal('home_delivery_fee', 8, 2)->default(0.00);
            $table->decimal('minimum_order_amount', 8, 2)->default(0.00);
            $table->boolean('prescription_required')->default(true);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
