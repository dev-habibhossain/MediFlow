<?php

use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorScheduleException;
use App\Models\User;

test('doctor schedule exceptions page renders successfully', function () {
    $dept = Department::factory()->create();
    $docUser = User::factory()->create(['role' => 'doctor']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id, 'department_id' => $dept->id]);

    DoctorScheduleException::factory()->create([
        'doctor_id' => $doctor->id,
        'type' => 'vacation',
        'reason' => 'Annual holiday',
    ]);

    $response = $this->actingAs($docUser)->get(route('doctor.schedule.exceptions'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Doctor/Schedule/Exceptions')
        ->has('exceptions', 1)
    );
});

test('doctor can submit schedule exception request', function () {
    $dept = Department::factory()->create();
    $docUser = User::factory()->create(['role' => 'doctor']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id, 'department_id' => $dept->id]);

    $response = $this->actingAs($docUser)->post(route('doctor.schedule.exceptions.store'), [
        'exceptionType' => 'vacation',
        'startDate' => '2026-10-01',
        'endDate' => '2026-10-02',
        'reasonNotes' => 'Medical Seminar Attendance',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('doctor_schedule_exceptions', [
        'doctor_id' => $doctor->id,
        'type' => 'vacation',
        'reason' => 'Medical Seminar Attendance',
    ]);
});

test('doctor can cancel schedule exception request', function () {
    $dept = Department::factory()->create();
    $docUser = User::factory()->create(['role' => 'doctor']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id, 'department_id' => $dept->id]);

    $exception = DoctorScheduleException::factory()->create([
        'doctor_id' => $doctor->id,
        'type' => 'vacation',
        'reason' => 'Personal Leave',
    ]);

    $response = $this->actingAs($docUser)->delete(route('doctor.schedule.exceptions.destroy', "EXC-{$exception->id}"));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('doctor_schedule_exceptions', [
        'id' => $exception->id,
    ]);
});
