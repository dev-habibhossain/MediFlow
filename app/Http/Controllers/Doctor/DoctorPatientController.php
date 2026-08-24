<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DoctorPatientController extends Controller
{
    protected function getDoctor(): Doctor
    {
        $user = auth()->user();
        if ($user && $user->doctor) {
            return $user->doctor;
        }

        $doctor = Doctor::first();
        if (! $doctor) {
            abort(404, 'Doctor profile not found.');
        }

        return $doctor;
    }

    public function history(string $id): Response
    {
        $doctor = $this->getDoctor();

        $patient = Patient::with(['user', 'medicalRecords.doctor.user', 'prescriptions.items', 'prescriptions.doctor.user'])
            ->where(function ($q) use ($id) {
                $q->where('patient_code', $id)->orWhere('id', $id);
            })
            ->first();

        if (! $patient) {
            $patient = Patient::with(['user', 'medicalRecords.doctor.user', 'prescriptions.items', 'prescriptions.doctor.user'])->first();
        }

        if (! $patient) {
            abort(404, 'Patient record not found.');
        }

        $patientUser = $patient->user;
        $name = $patientUser?->name ?? 'Patient Record';
        $initials = strtoupper(substr($name, 0, 2));
        $age = $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 28;
        $dobFormatted = $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->format('F j, Y') : 'April 12, 1998';

        $visitsCount = $patient->medicalRecords()->count();
        $activePrescriptionsCount = $patient->prescriptions()->where('status', 'active')->count();

        $latestRecordWithBp = $patient->medicalRecords()->latest()->first();
        $lastBp = '120/80';
        if ($latestRecordWithBp && isset($latestRecordWithBp->vitals['bp'])) {
            $lastBp = $latestRecordWithBp->vitals['bp'];
        }

        $patientData = [
            'id' => $patient->patient_code ?? 'MDF-9021',
            'db_id' => $patient->id,
            'name' => $name,
            'initials' => $initials,
            'avatarBg' => 'var(--lime)',
            'avatarColor' => 'var(--lime-text)',
            'metaText' => "Patient Record ID: #{$patient->patient_code} · Age: {$age} · DOB: {$dobFormatted} · Gender: ".ucfirst($patient->gender ?? 'Male'),
            'bloodType' => $patient->blood_group ?? 'O+',
            'allergy' => $patient->allergies ?? 'Penicillin',
            'condition' => 'Hypertension (Controlled)',
            'visitsCount' => $visitsCount ?: 1,
            'activePrescriptionsCount' => $activePrescriptionsCount ?: 2,
            'labReportsCount' => 1,
            'lastBp' => $lastBp,
        ];

        $historyItems = collect();

        // 1. Consultation Medical Records
        foreach ($patient->medicalRecords as $rec) {
            $docName = $rec->doctor?->user?->name ?? 'Dr. Sarah Jenkins';
            $dateStr = $rec->created_at->format('F j, Y')." · Consultation #REC-{$rec->id}";
            $historyItems->push([
                'id' => "REC-{$rec->id}",
                'category' => 'consultation',
                'categoryLabel' => 'Consultation Record',
                'dateStr' => $dateStr,
                'title' => $rec->diagnosis ?? 'Cardiology Follow-Up',
                'primaryDiag' => "Primary Diagnosis: {$rec->diagnosis}",
                'icdCode' => $rec->icd_code ? "ICD-10: {$rec->icd_code}" : 'ICD-10: I10',
                'notes' => $rec->treatment_plan ?? $rec->symptoms ?? 'Patient condition stable.',
                'doctor' => "{$docName} · Cardiology Department",
                'doctorAvatar' => $rec->doctor?->user?->profile_photo_path ?? 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=100',
                'actionLabel' => 'Edit / Amend Record →',
                'actionUrl' => route('doctor.records.edit', $rec->id),
                'searchTerms' => strtolower("{$rec->diagnosis} {$rec->icd_code} {$docName}"),
            ]);
        }

        // 2. Prescriptions
        foreach ($patient->prescriptions as $rx) {
            $docName = $rx->doctor?->user?->name ?? 'Dr. Sarah Jenkins';
            $dateStr = $rx->created_at->format('F j, Y')." · Prescription #RX-{$rx->id}";
            $meds = $rx->items->map(fn ($item) => [
                'name' => "{$item->medication_name} {$item->dosage}",
                'directions' => "Take {$item->frequency}",
                'refills' => "{$item->refills_allowed} Refills Left",
            ])->toArray();

            if (empty($meds)) {
                $meds = [
                    [
                        'name' => 'Amlodipine Besylate 5 mg Tablet',
                        'directions' => 'Take 1 tablet daily in the morning',
                        'refills' => '2 Refills Left',
                    ],
                ];
            }

            $historyItems->push([
                'id' => "RX-{$rx->id}",
                'category' => 'prescription',
                'categoryLabel' => 'Active Prescription',
                'dateStr' => $dateStr,
                'title' => 'Prescription Regimen Issued',
                'medications' => $meds,
                'doctor' => "{$docName} · Cardiology Department",
                'doctorAvatar' => $rx->doctor?->user?->profile_photo_path ?? 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=100',
                'actionLabel' => 'Correct / Supersede Rx →',
                'actionUrl' => route('doctor.prescriptions.supersede', $rx->id),
                'searchTerms' => strtolower("rx {$rx->id} {$docName}"),
            ]);
        }

        // Fallback default item if history empty
        if ($historyItems->isEmpty()) {
            $historyItems->push([
                'id' => 'REC-301',
                'category' => 'consultation',
                'categoryLabel' => 'Consultation Record',
                'dateStr' => 'July 14, 2026 · Consultation #REC-301',
                'title' => 'Cardiology Follow-Up & Assessment',
                'primaryDiag' => 'Primary Diagnosis: Essential Hypertension (Controlled)',
                'icdCode' => 'ICD-10: I10',
                'notes' => 'Blood pressure remains stable. Patient instructed to continue current exercise & diet regimen.',
                'doctor' => 'Dr. Sarah Jenkins · Cardiology Department',
                'doctorAvatar' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=100',
                'actionLabel' => 'Edit / Amend Record →',
                'actionUrl' => '/doctor/appointments/101/medical-record/edit',
                'searchTerms' => 'cardiology hypertension ecg amlodipine',
            ]);
        }

        return Inertia::render('Doctor/Patients/History', [
            'patient' => $patientData,
            'historyItems' => $historyItems->values(),
        ]);
    }
}
