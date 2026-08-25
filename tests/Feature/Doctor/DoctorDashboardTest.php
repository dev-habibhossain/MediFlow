<?php

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;

test('doctor can view dynamic command center dashboard', function () {
    $dept = Department::factory()->create(['name' => 'Cardiology']);
    $docUser = User::factory()->create(['role' => 'doctor', 'name' => 'Sarah Connor']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id, 'department_id' => $dept->id]);

    $patientUser = User::factory()->create(['role' => 'patient', 'name' => 'John Doe']);
    $patient = Patient::factory()->create(['user_id' => $patientUser->id]);

    $appointment = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'department_id' => $dept->id,
        'status' => 'confirmed',
        'appointment_date' => today(),
        'start_time' => '10:00:00',
    ]);

    $response = $this->actingAs($docUser)->get(route('doctor.dashboard'));

    $response->assertOk();
    $response->assertInertia(function ($page) {
        $props = $page->toArray()['props'];
        expect($props['greeting'])->toContain('Dr. Sarah Connor');
        expect($props['stats']['appointments_today'])->toBe(1);
        expect($props['nextBanner'])->not()->toBeNull();
        expect($props['nextBanner']['patient_name'])->toBe('John Doe');
        expect($props['todayAppointments'])->toHaveCount(1);
        expect($props['todayAppointments'][0]['patientName'])->toBe('John Doe');
    });
});
