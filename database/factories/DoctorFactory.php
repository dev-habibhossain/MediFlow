<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'specialization' => fake()->jobTitle(),
            'qualifications' => 'MBBS, MD ('.fake()->word().')',
            'bio' => fake()->paragraph(),
            'years_of_experience' => fake()->numberBetween(2, 30),
            'consultation_fee' => fake()->randomElement([50.00, 80.00, 100.00, 120.00, 150.00]),
            'license_number' => 'DOC-LIC-'.fake()->unique()->numerify('#####'),
            'status' => 'active',
        ];
    }
}
