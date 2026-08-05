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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_code', 20)->unique();
            $table->foreignId('patient_id')->constrained('patients')->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->restrictOnDelete();
            $table->foreignId('department_id')->constrained('departments')->restrictOnDelete();
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('reason');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show', 'rescheduled'])->default('pending')->index();
            $table->decimal('consultation_fee_snapshot', 10, 2);
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cancellation_reason', 500)->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('rescheduled_from_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['doctor_id', 'appointment_date', 'start_time'], 'doctor_date_start_unique');
            $table->index(['patient_id', 'appointment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
