<?php

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;

test('doctor can update appointment status to in_progress', function () {
    $dept = Department::factory()->create();
    $docUser = User::factory()->create(['role' => 'doctor']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id, 'department_id' => $dept->id]);

    $patientUser = User::factory()->create(['role' => 'patient']);
    $patient = Patient::factory()->create(['user_id' => $patientUser->id]);

    $appointment = Appointment::factory()->create([
        'appointment_code' => 'MDF-55829',
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'status' => 'confirmed',
    ]);

    $response = $this->actingAs($docUser)->patch(route('doctor.appointments.update-status', 'MDF-55829'), [
        'status' => 'in_progress',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Appointment status updated successfully.');

    expect($appointment->fresh()->status)->toBe('in_progress');
});
