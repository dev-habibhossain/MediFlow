<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $doctorRole = Role::firstOrCreate(['name' => 'Doctor']);
        $patientRole = Role::firstOrCreate(['name' => 'Patient']);
        Role::firstOrCreate(['name' => 'Guest']);

        // 2. Create Admin User
        $adminUser = User::factory()->create([
            'name' => 'MediFlow Administrator',
            'email' => 'admin@mediflow.com',
            'phone' => '+15550000001',
            'is_active' => true,
        ]);
        $adminUser->assignRole($adminRole);

        // 3. Create Departments
        $departmentsData = [
            [
                'name' => 'Cardiology',
                'slug' => 'cardiology',
                'description' => 'Comprehensive heart care, cardiovascular diagnosis, and therapeutic treatments.',
                'icon_path' => 'icons/cardiology.svg',
            ],
            [
                'name' => 'Pediatrics',
                'slug' => 'pediatrics',
                'description' => 'Expert medical care for infants, children, and adolescents.',
                'icon_path' => 'icons/pediatrics.svg',
            ],
            [
                'name' => 'Neurology',
                'slug' => 'neurology',
                'description' => 'Diagnosis and management of brain, spine, and nervous system disorders.',
                'icon_path' => 'icons/neurology.svg',
            ],
            [
                'name' => 'Orthopedics',
                'slug' => 'orthopedics',
                'description' => 'Care for musculoskeletal conditions, joint health, and fracture treatments.',
                'icon_path' => 'icons/orthopedics.svg',
            ],
            [
                'name' => 'General Medicine',
                'slug' => 'general-medicine',
                'description' => 'Primary health services, routine check-ups, and preventive medical care.',
                'icon_path' => 'icons/general-medicine.svg',
            ],
        ];

        $departments = collect();
        foreach ($departmentsData as $dep) {
            $departments->push(Department::create(array_merge($dep, ['is_active' => true])));
        }

        // 4. Create Doctors & Schedules
        $doctorsData = [
            ['name' => 'Dr. Sarah Jenkins', 'email' => 'sarah.jenkins@mediflow.com', 'spec' => 'Consultant Cardiologist', 'fee' => 120.00, 'exp' => 12],
            ['name' => 'Dr. Michael Chen', 'email' => 'michael.chen@mediflow.com', 'spec' => 'Pediatric Specialist', 'fee' => 90.00, 'exp' => 8],
            ['name' => 'Dr. Elena Rostova', 'email' => 'elena.rostova@mediflow.com', 'spec' => 'Senior Neurologist', 'fee' => 150.00, 'exp' => 16],
            ['name' => 'Dr. James Wilson', 'email' => 'james.wilson@mediflow.com', 'spec' => 'Orthopedic Surgeon', 'fee' => 130.00, 'exp' => 14],
            ['name' => 'Dr. Emily Watson', 'email' => 'emily.watson@mediflow.com', 'spec' => 'General Physician', 'fee' => 70.00, 'exp' => 6],
        ];

        $doctors = collect();
        foreach ($doctorsData as $index => $docInfo) {
            $user = User::factory()->create([
                'name' => $docInfo['name'],
                'email' => $docInfo['email'],
                'phone' => '+1555100000'.($index + 1),
                'is_active' => true,
            ]);
            $user->assignRole($doctorRole);

            $department = $departments[$index % $departments->count()];

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'department_id' => $department->id,
                'specialization' => $docInfo['spec'],
                'qualifications' => 'MBBS, MD, Board Certified Specialist',
                'bio' => "Dedicated healthcare professional specializing in {$docInfo['spec']} with {$docInfo['exp']} years of clinical experience.",
                'years_of_experience' => $docInfo['exp'],
                'consultation_fee' => $docInfo['fee'],
                'license_number' => 'DOC-LIC-'.(10000 + $index),
                'status' => 'active',
            ]);
            $doctors->push($doctor);

            // Create recurring weekly schedule (Mon - Fri, 09:00 - 17:00)
            for ($day = 1; $day <= 5; $day++) {
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'slot_duration_minutes' => 30,
                    'is_active' => true,
                ]);
            }
        }

        // 5. Create Patients
        $patients = collect();
        for ($i = 1; $i <= 10; $i++) {
            $user = User::factory()->create([
                'name' => "Patient User {$i}",
                'email' => "patient{$i}@mediflow.com",
                'phone' => '+1555200000'.$i,
                'is_active' => true,
            ]);
            $user->assignRole($patientRole);

            $patient = Patient::create([
                'user_id' => $user->id,
                'date_of_birth' => now()->subYears(20 + $i * 3)->format('Y-m-d'),
                'gender' => $i % 2 === 0 ? 'female' : 'male',
                'blood_group' => ['A+', 'O+', 'B+', 'AB+'][$i % 4],
                'address' => "{$i}0{$i} Health Street, Medical District, City",
                'emergency_contact_name' => "Emergency Contact {$i}",
                'emergency_contact_phone' => '+1555900000'.$i,
                'allergies' => $i % 3 === 0 ? 'Penicillin allergy' : null,
                'patient_code' => 'PAT-'.(50000 + $i),
            ]);
            $patients->push($patient);
        }

        // 6. Create Completed Past Appointments with Clinical Records & Payments
        foreach ($patients->take(5) as $idx => $patient) {
            $doctor = $doctors[$idx % $doctors->count()];

            $appointment = Appointment::create([
                'appointment_code' => 'APT-'.(700000 + $idx),
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'department_id' => $doctor->department_id,
                'appointment_date' => now()->subDays(5 + $idx)->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '10:30:00',
                'reason' => 'Routine medical consultation and health assessment.',
                'status' => 'completed',
                'consultation_fee_snapshot' => $doctor->consultation_fee,
                'confirmed_at' => now()->subDays(6 + $idx),
                'completed_at' => now()->subDays(5 + $idx),
            ]);

            // Medical Record
            $medicalRecord = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'diagnosis' => 'Mild seasonal allergy and fatigue.',
                'symptoms' => 'Nasal congestion, slight tiredness over 3 days.',
                'vitals' => [
                    'bp' => '120/80',
                    'pulse' => 72,
                    'temp' => 98.6,
                    'weight_kg' => 68,
                ],
                'doctor_notes' => 'Patient advised rest, hydration, and prescribed antihistamines.',
                'version' => 1,
            ]);

            // Prescription
            $prescription = Prescription::create([
                'prescription_code' => 'RX-'.(800000 + $idx),
                'appointment_id' => $appointment->id,
                'medical_record_id' => $medicalRecord->id,
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'special_instructions' => 'Take medication after meals. Drink plenty of water.',
                'issued_at' => now()->subDays(5 + $idx),
            ]);

            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medication_name' => 'Cetirizine 10mg',
                'dosage' => '1 tablet',
                'frequency' => 'Once daily at bedtime',
                'duration' => '7 days',
                'notes' => 'May cause slight drowsiness.',
            ]);

            // Payment
            Payment::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $patient->id,
                'amount' => $doctor->consultation_fee,
                'currency' => 'USD',
                'status' => 'paid',
                'stripe_payment_intent_id' => 'pi_mock_'.Str::random(16),
                'paid_at' => now()->subDays(6 + $idx),
            ]);

            // Review
            Review::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'rating' => 5,
                'comment' => 'Very thorough consultation! The doctor answered all my questions clearly.',
                'is_visible' => true,
            ]);
        }

        // 7. Create Upcoming Confirmed Appointments
        foreach ($patients->skip(5) as $idx => $patient) {
            $doctor = $doctors[$idx % $doctors->count()];

            Appointment::create([
                'appointment_code' => 'APT-'.(710000 + $idx),
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'department_id' => $doctor->department_id,
                'appointment_date' => now()->addDays(3 + $idx)->format('Y-m-d'),
                'start_time' => '11:00:00',
                'end_time' => '11:30:00',
                'reason' => 'Follow-up health review.',
                'status' => 'confirmed',
                'consultation_fee_snapshot' => $doctor->consultation_fee,
                'confirmed_at' => now()->subDays(1),
            ]);
        }

        // 8. System Settings
        Setting::create(['key' => 'hospital_name', 'value' => 'MediFlow General Hospital', 'type' => 'string', 'updated_by' => $adminUser->id]);
        Setting::create(['key' => 'support_email', 'value' => 'support@mediflow.com', 'type' => 'string', 'updated_by' => $adminUser->id]);
        Setting::create(['key' => 'currency', 'value' => 'USD', 'type' => 'string', 'updated_by' => $adminUser->id]);
        Setting::create(['key' => 'default_slot_duration', 'value' => '30', 'type' => 'integer', 'updated_by' => $adminUser->id]);
    }
}
