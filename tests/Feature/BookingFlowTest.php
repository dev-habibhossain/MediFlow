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
