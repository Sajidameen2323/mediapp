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
        Schema::create('pharmacy_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_order_id')->constrained('pharmacy_orders')->onDelete('cascade');
            $table->foreignId('prescription_medication_id')->constrained('prescription_medications')->onDelete('cascade');
            $table->string('medication_name');
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');
            $table->text('instructions')->nullable();
            $table->integer('quantity_prescribed');
            $table->integer('quantity_dispensed')->default(0);
            $table->boolean('is_available')->default(true);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->text('pharmacy_notes')->nullable();
            $table->enum('status', ['pending', 'available', 'partial', 'unavailable', 'dispensed'])->default('pending');
            $table->timestamps();
            
            $table->index(['pharmacy_order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_order_items');
    }
};
