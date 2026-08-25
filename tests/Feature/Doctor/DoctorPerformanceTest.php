<?php

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Review;
use App\Models\User;

test('doctor can view performance metrics and timeframe filters', function () {
    $dept = Department::factory()->create();
    $docUser = User::factory()->create(['role' => 'doctor']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id, 'department_id' => $dept->id]);

    $patientUser = User::factory()->create(['role' => 'patient']);
    $patient = Patient::factory()->create(['user_id' => $patientUser->id]);

    $appointment = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'status' => 'completed',
        'appointment_date' => today(),
    ]);

    Review::create([
        'appointment_id' => $appointment->id,
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'rating' => 5,
        'comment' => 'Great experience!',
        'is_visible' => true,
    ]);

    $response = $this->actingAs($docUser)->get(route('doctor.performance.index', ['timeframe' => 'this_month']));

    $response->assertOk();
    $response->assertInertia(function ($page) {
        $props = $page->toArray()['props'];
        expect($props['metrics']['monthlyConsultations'])->toBe(1);
        expect($props['metrics']['patientSatisfaction'])->toBe('5.0 / 5.0');
        expect($props['metrics']['satisfactionCount'])->toBe(1);
        expect($props['ratingBreakdown']['fiveStar'])->toBe(100);
        expect($props['reviews'])->toHaveCount(1);
        expect($props['reviews'][0]['comment'])->toBe('Great experience!');
    });
});
