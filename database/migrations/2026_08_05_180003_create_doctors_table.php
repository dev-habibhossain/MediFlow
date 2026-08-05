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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->restrictOnDelete();
            $table->string('specialization', 255);
            $table->string('qualifications', 500)->nullable();
            $table->text('bio')->nullable();
            $table->unsignedTinyInteger('years_of_experience')->default(0);
            $table->decimal('consultation_fee', 10, 2)->default(0.00);
            $table->string('license_number', 100)->unique();
            $table->enum('status', ['active', 'on_leave', 'suspended', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
