<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    protected $model = MedicalRecord::class;

    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::factory(),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'diagnosis' => fake()->sentence(),
            'symptoms' => fake()->paragraph(),
            'vitals' => [
                'bp' => '120/80',
                'pulse' => 72,
                'temp' => 98.6,
                'weight_kg' => 70,
            ],
            'doctor_notes' => fake()->paragraph(),
            'version' => 1,
        ];
    }
}
