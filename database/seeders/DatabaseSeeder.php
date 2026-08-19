<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\Attachment;
use App\Models\ContactMessage;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleException;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            ['name' => 'Dr. Sarah Jenkins', 'email' => 'sarah.jenkins@mediflow.com', 'spec' => 'Consultant Cardiologist', 'fee' => 120.00, 'exp' => 12, 'avatar' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Dr. Michael Chen', 'email' => 'michael.chen@mediflow.com', 'spec' => 'Pediatric Specialist', 'fee' => 90.00, 'exp' => 8, 'avatar' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Dr. Elena Rostova', 'email' => 'elena.rostova@mediflow.com', 'spec' => 'Senior Neurologist', 'fee' => 150.00, 'exp' => 16, 'avatar' => 'https://images.unsplash.com/photo-1594824813566-78a0c4f74d0e?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Dr. James Wilson', 'email' => 'james.wilson@mediflow.com', 'spec' => 'Orthopedic Surgeon', 'fee' => 130.00, 'exp' => 14, 'avatar' => 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Dr. Emily Watson', 'email' => 'emily.watson@mediflow.com', 'spec' => 'General Physician', 'fee' => 70.00, 'exp' => 6, 'avatar' => 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Dr. Nadia Islam', 'email' => 'nadia.islam@mediflow.com', 'spec' => 'Preventive Cardiologist', 'fee' => 125.00, 'exp' => 10, 'avatar' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Dr. Rashid Khan', 'email' => 'rashid.khan@mediflow.com', 'spec' => 'Cardiac Electrophysiologist', 'fee' => 140.00, 'exp' => 11, 'avatar' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=600&q=80'],
            ['name' => 'Dr. Sophia Martinez', 'email' => 'sophia.martinez@mediflow.com', 'spec' => 'Child Health Specialist', 'fee' => 95.00, 'exp' => 9, 'avatar' => 'https://images.unsplash.com/photo-1527613426441-4da17471b66d?auto=format&fit=crop&w=600&q=80'],
        ];

        $doctors = collect();
        foreach ($doctorsData as $index => $docInfo) {
            $user = User::factory()->create([
                'name' => $docInfo['name'],
                'email' => $docInfo['email'],
                'avatar_path' => $docInfo['avatar'],
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

            // Secondary Department Affiliation (Many-to-Many)
            $secondaryDep = $departments[($index + 1) % $departments->count()];
            $doctor->secondaryDepartments()->attach($secondaryDep->id);

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

            // Create sample schedule exception for 1 doctor
            if ($index === 0) {
                DoctorScheduleException::create([
                    'doctor_id' => $doctor->id,
                    'exception_date' => now()->addDays(14)->format('Y-m-d'),
                    'type' => 'unavailable',
                    'reason' => 'Attending Annual Cardiology Conference',
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

        // 6. Create Completed Past Appointments with Clinical Records, Payments & Financial Transactions
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

            // Attachment for Medical Record
            Attachment::create([
                'attachable_type' => MedicalRecord::class,
                'attachable_id' => $medicalRecord->id,
                'file_path' => 'medical_records/lab_results_sample.pdf',
                'file_name' => 'blood_test_results.pdf',
                'mime_type' => 'application/pdf',
                'file_size_kb' => 245,
                'uploaded_by' => $doctor->user_id,
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
            $stripePaymentIntentId = 'pi_mock_'.Str::random(16);
            $payment = Payment::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $patient->id,
                'amount' => $doctor->consultation_fee,
                'currency' => 'USD',
                'status' => 'paid',
                'stripe_payment_intent_id' => $stripePaymentIntentId,
                'paid_at' => now()->subDays(6 + $idx),
            ]);

            // Transaction (Financial Ledger)
            Transaction::create([
                'payment_id' => $payment->id,
                'type' => 'charge',
                'amount' => $doctor->consultation_fee,
                'gateway_reference' => 'ch_mock_'.Str::random(16),
                'raw_response' => [
                    'id' => $stripePaymentIntentId,
                    'status' => 'succeeded',
                    'currency' => 'usd',
                ],
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

            // Activity Log
            ActivityLog::create([
                'causer_id' => $patient->user_id,
                'subject_type' => Appointment::class,
                'subject_id' => $appointment->id,
                'action' => 'completed',
                'description' => "Appointment {$appointment->appointment_code} completed by doctor.",
                'properties' => ['status' => 'completed'],
                'ip_address' => '127.0.0.1',
            ]);
        }

        // 7. Create Upcoming Confirmed Appointments
        foreach ($patients->slice(5, 3) as $idx => $patient) {
            $doctor = $doctors[$idx % $doctors->count()];

            $appointment = Appointment::create([
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

            // Sample In-App Notification
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => 'App\\Notifications\\AppointmentConfirmedNotification',
                'notifiable_type' => User::class,
                'notifiable_id' => $patient->user_id,
                'data' => json_encode([
                    'appointment_id' => $appointment->id,
                    'message' => "Your appointment {$appointment->appointment_code} with {$doctor->user->name} is confirmed.",
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 8. Create Pending and Cancelled Appointments
        $pendingPatient = $patients->slice(8, 1)->first();
        if ($pendingPatient) {
            $doctor = $doctors[0];
            Appointment::create([
                'appointment_code' => 'APT-720001',
                'patient_id' => $pendingPatient->id,
                'doctor_id' => $doctor->id,
                'department_id' => $doctor->department_id,
                'appointment_date' => now()->addDays(5)->format('Y-m-d'),
                'start_time' => '14:00:00',
                'end_time' => '14:30:00',
                'reason' => 'Initial consultation request.',
                'status' => 'pending',
                'consultation_fee_snapshot' => $doctor->consultation_fee,
            ]);
        }

        $cancelledPatient = $patients->slice(9, 1)->first();
        if ($cancelledPatient) {
            $doctor = $doctors[1];
            Appointment::create([
                'appointment_code' => 'APT-730001',
                'patient_id' => $cancelledPatient->id,
                'doctor_id' => $doctor->id,
                'department_id' => $doctor->department_id,
                'appointment_date' => now()->subDays(2)->format('Y-m-d'),
                'start_time' => '15:00:00',
                'end_time' => '15:30:00',
                'reason' => 'General checkup.',
                'status' => 'cancelled',
                'consultation_fee_snapshot' => $doctor->consultation_fee,
                'cancelled_by' => $cancelledPatient->user_id,
                'cancellation_reason' => 'Personal scheduling conflict.',
                'cancelled_at' => now()->subDays(3),
            ]);
        }

        // 9. System Settings
        Setting::create(['key' => 'hospital_name', 'value' => 'MediFlow General Hospital', 'type' => 'string', 'updated_by' => $adminUser->id]);
        Setting::create(['key' => 'support_email', 'value' => 'support@mediflow.com', 'type' => 'string', 'updated_by' => $adminUser->id]);
        Setting::create(['key' => 'currency', 'value' => 'USD', 'type' => 'string', 'updated_by' => $adminUser->id]);
        Setting::create(['key' => 'default_slot_duration', 'value' => '30', 'type' => 'integer', 'updated_by' => $adminUser->id]);

        // 10. Sample Contact Messages
        ContactMessage::create([
            'name' => 'Alexander Wright',
            'email' => 'alex.wright@example.com',
            'phone' => '+15554321098',
            'department_id' => $departments->first()?->id,
            'subject' => 'Inquiry regarding Cardiology Screening',
            'message' => 'Hello MediFlow Desk, I would like to know if Dr. Sarah Jenkins is accepting new patients for cardiac risk evaluation next week.',
            'status' => 'unread',
            'ip_address' => '127.0.0.1',
        ]);

        ContactMessage::create([
            'name' => 'Maria Garcia',
            'email' => 'm.garcia@example.com',
            'phone' => '+15559876543',
            'department_id' => $departments->skip(1)->first()?->id,
            'subject' => 'Pediatric Consultation Hours',
            'message' => 'Hi team, could you please confirm Saturday consultation hours for Dr. Michael Chen?',
            'status' => 'read',
            'ip_address' => '127.0.0.1',
        ]);
    }
}
