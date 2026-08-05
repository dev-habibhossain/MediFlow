<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prescription>
 */
class PrescriptionFactory extends Factory
{
    protected $model = Prescription::class;

    public function definition(): array
    {
        return [
            'prescription_code' => 'RX-'.fake()->unique()->numerify('######'),
            'appointment_id' => Appointment::factory(),
            'medical_record_id' => MedicalRecord::factory(),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'special_instructions' => 'Take with food and drink plenty of water.',
            'issued_at' => now(),
        ];
    }
}
