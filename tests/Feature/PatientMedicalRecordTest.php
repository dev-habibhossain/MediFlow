<?php

use App\Models\Appointment;
use App\Models\Attachment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;

test('unauthenticated users are redirected to login when accessing medical record detail', function () {
    $this->get('/patient/medical-records/1')
        ->assertRedirect('/login');
});

test('authenticated patient can view their own dynamic medical record detail with vitals and doctor information', function () {
    Role::firstOrCreate(['name' => 'Patient']);
    Role::firstOrCreate(['name' => 'Doctor']);

    $user = User::factory()->create(['role' => 'Patient', 'name' => 'Habib Hossain']);
    $user->assignRole('Patient');

    $patient = Patient::factory()->create([
        'user_id' => $user->id,
        'patient_code' => 'MDF-0001',
    ]);

    $docUser = User::factory()->create(['role' => 'Doctor', 'name' => 'Sarah Jenkins']);
    $docUser->assignRole('Doctor');

    $dept = Department::factory()->create(['name' => 'Cardiology', 'slug' => 'cardiology']);

    $doctor = Doctor::factory()->create([
        'user_id' => $docUser->id,
        'department_id' => $dept->id,
        'specialization' => 'Consultant Cardiologist',
    ]);

    $record = MedicalRecord::factory()->create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'diagnosis' => 'Essential Hypertension Follow-Up',
        'symptoms' => 'Mild exertion tightness, blood pressure check.',
        'doctor_notes' => "Subjective: Patient reports feeling stable.\nObjective: Blood pressure 120/80 mmHg, pulse 72 bpm.\nPlan: Maintain current medication.",
        'vitals' => [
            'bp' => '120/80',
            'pulse' => 72,
            'temp' => 98.6,
            'weight_kg' => 74.5,
            'icd_code' => 'I10',
        ],
    ]);

    $response = $this->actingAs($user)->get("/patient/medical-records/{$record->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Patient/Records/Show')
        ->has('record')
        ->where('record.id', $record->id)
        ->where('record.diagnosis', 'Essential Hypertension Follow-Up')
        ->where('record.doctor.name', 'Dr. Sarah Jenkins')
        ->where('record.doctor.department', 'Cardiology')
        ->where('record.patient.name', 'Habib Hossain')
        ->has('record.vital_tiles')
        ->where('record.soap.subjective', 'Patient reports feeling stable.')
        ->where('record.soap.plan', 'Maintain current medication.')
    );
});

test('medical record detail displays linked prescriptions and prescription items', function () {
    Role::firstOrCreate(['name' => 'Patient']);
    Role::firstOrCreate(['name' => 'Doctor']);

    $user = User::factory()->create(['role' => 'Patient']);
    $user->assignRole('Patient');

    $patient = Patient::factory()->create(['user_id' => $user->id]);

    $docUser = User::factory()->create(['role' => 'Doctor', 'name' => 'Michael Chen']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id]);

    $appointment = Appointment::factory()->create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
    ]);

    $record = MedicalRecord::factory()->create([
        'appointment_id' => $appointment->id,
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
    ]);

    $prescription = Prescription::create([
        'prescription_code' => 'RX-999001',
        'appointment_id' => $appointment->id,
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'medical_record_id' => $record->id,
        'special_instructions' => 'Take 1 tablet daily with food.',
        'status' => 'Active',
    ]);

    PrescriptionItem::create([
        'prescription_id' => $prescription->id,
        'medication_name' => 'Amlodipine Besylate',
        'dosage' => '5mg',
        'frequency' => 'Once daily',
        'duration' => '30 days',
    ]);

    $response = $this->actingAs($user)->get("/patient/medical-records/{$record->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Patient/Records/Show')
        ->has('record.prescriptions', 1)
        ->where('record.prescriptions.0.prescription_code', 'RX-999001')
        ->where('record.prescriptions.0.items.0.medication_name', 'Amlodipine Besylate')
    );
});

test('medical record detail displays attachments properly', function () {
    Role::firstOrCreate(['name' => 'Patient']);
    Role::firstOrCreate(['name' => 'Doctor']);

    $user = User::factory()->create(['role' => 'Patient']);
    $user->assignRole('Patient');

    $patient = Patient::factory()->create(['user_id' => $user->id]);

    $docUser = User::factory()->create(['role' => 'Doctor']);
    $docUser->assignRole('Doctor');
    $doctor = Doctor::factory()->create(['user_id' => $docUser->id]);

    $record = MedicalRecord::factory()->create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
    ]);

    Attachment::create([
        'attachable_type' => MedicalRecord::class,
        'attachable_id' => $record->id,
        'file_name' => 'ecg_rhythm_strip.pdf',
        'file_path' => 'medical-records/ecg_rhythm_strip.pdf',
        'mime_type' => 'application/pdf',
        'file_size_kb' => 512,
        'uploaded_by' => $docUser->id,
    ]);

    $response = $this->actingAs($user)->get("/patient/medical-records/{$record->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Patient/Records/Show')
        ->has('record.attachments', 1)
        ->where('record.attachments.0.name', 'ecg_rhythm_strip.pdf')
        ->where('record.attachments.0.size', '512 KB')
    );
});
