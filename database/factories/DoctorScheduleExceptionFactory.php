<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\DoctorScheduleException;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DoctorScheduleException>
 */
class DoctorScheduleExceptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory(),
            'exception_date' => fake()->date(),
            'type' => 'vacation',
            'start_time' => '00:00:00',
            'end_time' => '23:59:59',
            'reason' => fake()->sentence(),
        ];
    }
}
