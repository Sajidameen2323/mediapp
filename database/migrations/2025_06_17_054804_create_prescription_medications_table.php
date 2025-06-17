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
        Schema::create('prescription_medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->onDelete('cascade');
            $table->foreignId('medication_id')->constrained('medications')->onDelete('cascade');
            $table->string('dosage'); // e.g., "500mg", "2 tablets"
            $table->string('frequency'); // e.g., "twice daily", "every 8 hours"
            $table->string('duration'); // e.g., "7 days", "2 weeks"
            $table->text('instructions')->nullable(); // Special instructions
            $table->integer('quantity_prescribed');
            $table->integer('quantity_dispensed')->default(0);
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_medications');
    }
};
