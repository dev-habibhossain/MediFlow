<?php

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('doctors directory includes review aggregates from database', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'status' => 'active',
    ]);

    $patient = Patient::factory()->create();

    $appointment1 = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'department_id' => $department->id,
        'start_time' => '10:00:00',
    ]);
    $appointment2 = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'department_id' => $department->id,
        'start_time' => '11:00:00',
    ]);
    $appointment3 = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'department_id' => $department->id,
        'start_time' => '12:00:00',
    ]);

    // Create visible reviews with known ratings
    Review::factory()->create([
        'appointment_id' => $appointment1->id,
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'rating' => 5,
        'is_visible' => true,
    ]);
    Review::factory()->create([
        'appointment_id' => $appointment2->id,
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'rating' => 3,
        'is_visible' => true,
    ]);

    // Create an invisible review (should not affect count)
    Review::factory()->create([
        'appointment_id' => $appointment3->id,
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'rating' => 1,
        'is_visible' => false,
    ]);

    $this->get(route('doctors.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Doctors/Index')
            ->has('doctors.data', 1)
            ->where('doctors.data.0.reviews_avg_rating', fn ($rating) => (float) $rating === 4.0)
            ->where('doctors.data.0.reviews_count', 2)
        );
});

test('doctor with no reviews shows null rating and zero count', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();
    Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'status' => 'active',
    ]);

    $this->get(route('doctors.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Doctors/Index')
            ->has('doctors.data', 1)
            ->where('doctors.data.0.reviews_avg_rating', null)
            ->where('doctors.data.0.reviews_count', 0)
        );
});
