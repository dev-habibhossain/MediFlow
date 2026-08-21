<?php

use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('booking select slot page renders successfully with default doctor', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'status' => 'active',
        'license_number' => 'DOC-TEST-100',
    ]);

    $this->get(route('appointments.book.select-slot'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Booking/SelectSlot')
            ->has('doctor')
            ->has('availableDoctors')
        );
});

test('booking select slot page renders with specific doctor license number', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'status' => 'active',
        'license_number' => 'DOC-TEST-101',
    ]);

    $this->get(route('appointments.book.select-slot', $doctor))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Booking/SelectSlot')
            ->where('doctor.id', $doctor->id)
        );
});

test('booking confirm page renders successfully', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'status' => 'active',
        'license_number' => 'DOC-TEST-102',
    ]);

    $this->get(route('appointments.book.confirm', $doctor))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Booking/Confirm')
            ->where('doctor.id', $doctor->id)
        );
});

test('booking success page renders successfully', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'status' => 'active',
        'license_number' => 'DOC-TEST-103',
    ]);

    $this->get(route('appointments.book.success', $doctor))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Booking/Success')
            ->where('doctor.id', $doctor->id)
        );
});

test('inactive doctors with weekly schedules can be viewed and booked', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'status' => 'inactive',
        'license_number' => 'DOC-TEST-104',
    ]);

    $this->get(route('doctors.show', $doctor))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Doctors/Show')
            ->where('doctor.id', $doctor->id)
        );

    $this->get(route('appointments.book.select-slot', $doctor))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Booking/SelectSlot')
            ->where('doctor.id', $doctor->id)
        );
});

test('authenticated user can store appointment with pay at clinic creating unpaid payment due', function () {
    $patientUser = User::factory()->create();
    $doctorUser = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $doctorUser->id,
        'department_id' => $department->id,
        'consultation_fee' => 75.00,
        'license_number' => 'DOC-STORE-101',
    ]);

    $response = $this->actingAs($patientUser)->post(route('appointments.book.store'), [
        'doctor_id' => $doctor->id,
        'appointment_date' => '2026-09-01',
        'start_time' => '10:00 AM',
        'reason' => 'Heart Checkup',
        'payment_method' => 'clinic',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('appointments', [
        'doctor_id' => $doctor->id,
        'status' => 'confirmed',
    ]);

    $this->assertDatabaseHas('payments', [
        'amount' => 75.00,
        'status' => 'pending',
    ]);
});

test('authenticated user can store appointment with stripe creating paid payment', function () {
    $patientUser = User::factory()->create();
    $doctorUser = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $doctorUser->id,
        'department_id' => $department->id,
        'consultation_fee' => 120.00,
        'license_number' => 'DOC-STORE-102',
    ]);

    $response = $this->actingAs($patientUser)->post(route('appointments.book.store'), [
        'doctor_id' => $doctor->id,
        'appointment_date' => '2026-09-02',
        'start_time' => '02:00 PM',
        'reason' => 'General Health Consultation',
        'payment_method' => 'stripe',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('payments', [
        'amount' => 120.00,
        'status' => 'paid',
    ]);
});

test('dashboard renders user appointments and unpaid dues', function () {
    $patientUser = User::factory()->create();
    $doctorUser = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $doctorUser->id,
        'department_id' => $department->id,
        'consultation_fee' => 50.00,
    ]);

    $this->actingAs($patientUser)->post(route('appointments.book.store'), [
        'doctor_id' => $doctor->id,
        'appointment_date' => '2026-09-05',
        'start_time' => '11:00 AM',
        'reason' => 'Routine Check',
        'payment_method' => 'clinic',
    ]);

    $this->actingAs($patientUser)->get(route('dashboard'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('appointments', 1)
            ->has('unpaidPayments', 1)
        );
});
