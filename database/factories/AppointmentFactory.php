<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'appointment_code' => 'APT-'.fake()->unique()->numerify('######'),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'department_id' => Department::factory(),
            'appointment_date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'reason' => fake()->sentence(),
            'status' => 'confirmed',
            'consultation_fee_snapshot' => 100.00,
        ];
    }
}
