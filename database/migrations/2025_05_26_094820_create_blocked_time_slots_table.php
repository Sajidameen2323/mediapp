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
        Schema::create('blocked_time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->enum('type', ['personal', 'maintenance', 'holiday', 'emergency', 'other'])->default('other');
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_pattern', ['daily', 'weekly', 'monthly'])->nullable();
            $table->date('recurring_end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['doctor_id', 'date', 'is_active']);
            $table->index(['date', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_time_slots');
    }
};
