<?php

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
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
        ->has('patientInfo')
        ->has('greeting')
    );
});

test('authenticated patient dashboard provides vitals, prescriptions, and profile metadata', function () {
    Role::firstOrCreate(['name' => 'Patient']);
    Role::firstOrCreate(['name' => 'Doctor']);

    $user = User::factory()->create([
        'role' => 'Patient',
        'name' => 'Johnathan Doe',
        'email' => 'john.doe@example.com',
    ]);
    $user->assignRole('Patient');

    $patient = Patient::factory()->create([
        'user_id' => $user->id,
        'patient_code' => 'MDF-7788',
        'blood_group' => 'O+',
        'allergies' => 'Penicillin',
        'gender' => 'male',
        'date_of_birth' => Carbon::now()->subYears(30)->format('Y-m-d'),
    ]);

    $docUser = User::factory()->create(['role' => 'Doctor', 'name' => 'Robert Smith']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id]);

    $appointment = Appointment::factory()->create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'appointment_date' => Carbon::now()->addDay()->format('Y-m-d'),
        'start_time' => '14:30:00',
        'status' => 'confirmed',
        'reason' => 'Routine cardiology follow-up',
    ]);

    // Create medical record with vitals
    $medicalRecord = MedicalRecord::factory()->create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'appointment_id' => $appointment->id,
        'diagnosis' => 'Mild hypertension',
        'vitals' => [
            'bp' => '125/82',
            'pulse' => 74,
            'temp' => 98.4,
            'weight_kg' => 78,
        ],
        'created_at' => now(),
    ]);

    // Create prescription with items
    $prescription = Prescription::factory()->create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'appointment_id' => $appointment->id,
        'medical_record_id' => $medicalRecord->id,
        'prescription_code' => 'RX-100200',
        'issued_at' => now(),
    ]);

    PrescriptionItem::factory()->create([
        'prescription_id' => $prescription->id,
        'medication_name' => 'Lisinopril 10mg',
        'dosage' => '1 tablet daily',
        'frequency' => 'Morning',
        'duration' => '30 days',
    ]);

    $response = $this->actingAs($user)->get('/patient/dashboard');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Patient/Dashboard')
        ->has('greeting')
        ->where('patientInfo.code', 'MDF-7788')
        ->where('patientInfo.blood_group', 'O+')
        ->where('patientInfo.allergies', 'Penicillin')
        ->where('patientInfo.gender', 'Male')
        ->has('latestVitals')
        ->where('latestVitals.bp', '125/82')
        ->where('latestVitals.pulse', 74)
        ->where('latestVitals.temp', 98.4)
        ->where('latestVitals.weight', 78)
        ->where('latestVitals.diagnosis', 'Mild hypertension')
        ->has('recentPrescriptions', 1)
        ->where('recentPrescriptions.0.code', 'RX-100200')
        ->where('recentPrescriptions.0.medication_summary', 'Lisinopril 10mg')
        ->where('stats.active_prescriptions', 1)
        ->where('stats.medical_records', 1)
        ->where('stats.upcoming_visits', 1)
        ->where('nextAppointment.status', 'confirmed')
        ->where('nextAppointment.reason', 'Routine cardiology follow-up')
    );
});

test('authenticated user without pre-existing patient record creates one automatically', function () {
    Role::firstOrCreate(['name' => 'Patient']);

    $user = User::factory()->create(['role' => 'Patient', 'name' => 'Auto Patient']);
    $user->assignRole('Patient');

    $response = $this->actingAs($user)->get('/patient/dashboard');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Patient/Dashboard')
        ->where('patientInfo.name', 'Auto Patient')
        ->where('stats.upcoming_visits', 0)
        ->where('stats.active_prescriptions', 0)
        ->where('nextAppointment', null)
        ->where('latestVitals', null)
    );

    $this->assertDatabaseHas('patients', [
        'user_id' => $user->id,
    ]);
});
