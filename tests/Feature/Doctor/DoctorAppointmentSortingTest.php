<?php

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;

test('doctor appointments are sorted with active upcoming first and finished or passed appointments last', function () {
    $dept = Department::factory()->create();
    $docUser = User::factory()->create(['role' => 'doctor']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id, 'department_id' => $dept->id]);

    $patientUser = User::factory()->create(['role' => 'patient']);
    $patient = Patient::factory()->create(['user_id' => $patientUser->id]);

    // 1. Finished appointment (completed) -> Should be last (Priority 4)
    $finishedApp = Appointment::factory()->create([
        'appointment_code' => 'MDF-FINISHED',
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'appointment_date' => today(),
        'start_time' => '08:00:00',
        'status' => 'completed',
    ]);

    // 2. Active waiting appointment (today 11:59 PM) -> Should be first (Priority 1)
    $activeApp = Appointment::factory()->create([
        'appointment_code' => 'MDF-ACTIVE',
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'appointment_date' => today(),
        'start_time' => '23:59:00',
        'status' => 'confirmed',
    ]);

    // 3. In Progress appointment -> Priority 2
    $inProgressApp = Appointment::factory()->create([
        'appointment_code' => 'MDF-PROGRESS',
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'appointment_date' => today(),
        'start_time' => '10:00:00',
        'status' => 'in_progress',
    ]);

    $response = $this->actingAs($docUser)->get(route('doctor.appointments.index'));

    $response->assertOk();
    $response->assertInertia(function ($page) {
        $appointments = $page->toArray()['props']['appointments'];

        expect($appointments)->toHaveCount(3);
        expect($appointments[0]['id'])->toBe('MDF-ACTIVE');
        expect($appointments[1]['id'])->toBe('MDF-PROGRESS');
        expect($appointments[2]['id'])->toBe('MDF-FINISHED');
    });
});
