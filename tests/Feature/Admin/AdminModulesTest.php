<?php

use App\Models\Department;
use App\Models\Patient;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'Admin']);
    Role::firstOrCreate(['name' => 'Doctor']);
    Role::firstOrCreate(['name' => 'Patient']);

    $this->admin = User::factory()->create([
        'role' => 'Admin',
        'is_active' => true,
    ]);
    $this->admin->assignRole('Admin');
});

test('admin can access doctors registry and create doctor', function () {
    $dept = Department::create(['name' => 'Surgery', 'slug' => 'surgery', 'is_active' => true]);

    $response = $this->actingAs($this->admin)->get(route('admin.doctors.index'));
    $response->assertOk();

    $response = $this->actingAs($this->admin)->post(route('admin.doctors.store'), [
        'title_name' => 'Dr. Test Specialist',
        'email' => 'testspec@mediflow.com',
        'gender' => 'male',
        'license_number' => 'MD-TEST-999',
        'department_id' => $dept->id,
        'qualifications' => 'MD, FACS',
        'experience_years' => 10,
        'consultation_fee' => 150.00,
        'password' => 'password123',
        'status' => 'active',
    ]);

    $response->assertRedirect(route('admin.doctors.index'));
    $this->assertDatabaseHas('users', ['email' => 'testspec@mediflow.com']);
    $this->assertDatabaseHas('doctors', ['license_number' => 'MD-TEST-999']);
});

test('admin can access patients registry and patient details', function () {
    $patientUser = User::factory()->create(['name' => 'Jane Patient', 'email' => 'jane@example.com']);
    $patient = Patient::create([
        'user_id' => $patientUser->id,
        'patient_code' => 'MDF-P-99',
        'blood_group' => 'A+',
        'gender' => 'female',
        'date_of_birth' => '1995-05-15',
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.patients.index'));
    $response->assertOk();

    $response = $this->actingAs($this->admin)->get(route('admin.patients.show', $patient->id));
    $response->assertOk();
});

test('admin can manage hospital departments', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.departments.store'), [
        'name' => 'Dermatology',
        'description' => 'Skin care and treatments',
        'is_active' => true,
    ]);

    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseHas('departments', ['slug' => 'dermatology']);
});

test('admin can access system activity logs and reports', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.activity-logs.index'));
    $response->assertOk();

    $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));
    $response->assertOk();
});

test('admin can access system settings center', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));
    $response->assertOk();
});
