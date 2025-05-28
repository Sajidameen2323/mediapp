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
        Schema::create('doctor_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->string('title'); // e.g., "Lunch Break", "Personal Break"
            $table->time('start_time');
            $table->time('end_time');
            $table->string('day_of_week'); // monday, tuesday, etc.
            $table->enum('type', ['lunch', 'personal', 'meeting', 'other', 'daily', 'weekly', 'one_time'])->default('weekly');
            $table->date('specific_date')->nullable(); // for one_time breaks
            $table->boolean('is_recurring')->default(true);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['doctor_id', 'day_of_week']);
            $table->index(['doctor_id', 'specific_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_breaks');
    }
};
