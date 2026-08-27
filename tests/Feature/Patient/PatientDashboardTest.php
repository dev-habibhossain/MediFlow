<?php

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;

test('unauthenticated users are redirected to login', function () {
    $this->get('/patient/dashboard')
        ->assertRedirect('/login');
});

test('authenticated patient can access dashboard with dynamic metrics', function () {
    Role::firstOrCreate(['name' => 'Patient']);
    Role::firstOrCreate(['name' => 'Doctor']);

    $user = User::factory()->create(['role' => 'Patient']);
    $user->assignRole('Patient');

    $patient = Patient::factory()->create([
        'user_id' => $user->id,
        'patient_code' => 'MDF-9988',
    ]);

    $docUser = User::factory()->create(['role' => 'Doctor', 'name' => 'Sarah Jenkins']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id]);

    Appointment::factory()->create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'appointment_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
        'start_time' => '10:00:00',
        'status' => 'Confirmed',
    ]);

    $response = $this->actingAs($user)->get('/patient/dashboard');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Patient/Dashboard')
        ->has('stats')
        ->where('stats.upcoming_visits', 1)
        ->has('nextAppointment')
        ->where('nextAppointment.doctor_name', 'Dr. Sarah Jenkins')
        ->has('upcomingAppointments')
        ->has('recentNotifications')
    );
});
