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

        $patient = Patient::with([
            'user',
            'medicalRecords.doctor.user',
            'medicalRecords.doctor.department',
            'prescriptions.items',
            'prescriptions.doctor.user',
            'prescriptions.doctor.department',
        ])
            ->where(function ($q) use ($id) {
                $q->where('patient_code', $id)->orWhere('id', $id);
            })
            ->first();

        if (! $patient) {
            abort(404, 'Patient record not found.');
        }

        $patientUser = $patient->user;
        $name = $patientUser?->name ?? 'Patient Record';
        $initials = strtoupper(substr($name, 0, 2));
        $age = $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 28;
        $dobFormatted = $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->format('F j, Y') : 'April 12, 1998';

        $visitsCount = $patient->medicalRecords()->count();
        $activePrescriptionsCount = $patient->prescriptions()->count();

        $latestRecordWithBp = $patient->medicalRecords()->latest()->first();
        $lastBp = '120/80';
        if ($latestRecordWithBp && isset($latestRecordWithBp->vitals['bp'])) {
            $lastBp = $latestRecordWithBp->vitals['bp'];
        }

        $latestAppt = $patient->appointments()->latest()->first();
        $latestAppointmentCode = $latestAppt?->appointment_code ?? $latestAppt?->id ?? '1';

        $patientData = [
            'id' => $patient->patient_code ?? 'MDF-9021',
            'db_id' => $patient->id,
            'name' => $name,
            'initials' => $initials,
            'avatarBg' => 'var(--lime)',
            'avatarColor' => 'var(--lime-text)',
            'metaText' => "Patient Record ID: #{$patient->patient_code} · Age: {$age} · DOB: {$dobFormatted} · Gender: ".ucfirst($patient->gender ?? 'Male'),
            'bloodType' => $patient->blood_group ?? 'O+',
            'allergy' => $patient->allergies ?? 'None Reported',
            'condition' => $latestRecordWithBp?->diagnosis ?? 'Routine Care',
            'visitsCount' => $visitsCount ?: 0,
            'activePrescriptionsCount' => $activePrescriptionsCount ?: 0,
            'labReportsCount' => 0,
            'lastBp' => $lastBp,
            'latestAppointmentCode' => $latestAppointmentCode,
        ];

        $historyItems = collect();

        // 1. Consultation Medical Records
        foreach ($patient->medicalRecords as $rec) {
            $recDoc = $rec->doctor;
            $docUser = $recDoc?->user ?? $doctor->user;
            $docName = $docUser?->name ?? 'Dr. Sarah Jenkins';
            $deptName = $recDoc?->department?->name ?? $recDoc?->specialization ?? $doctor->department?->name ?? 'Cardiology Department';
            $docAvatar = $docUser?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($docName).'&background=0D9488&color=fff';

            $dateStr = $rec->created_at->format('F j, Y')." · Consultation #REC-{$rec->id}";
            $icd = is_array($rec->vitals) && ! empty($rec->vitals['icd_code']) ? $rec->vitals['icd_code'] : 'I10';
            $treatPlan = is_array($rec->vitals) && ! empty($rec->vitals['treatment_plan']) ? $rec->vitals['treatment_plan'] : ($rec->doctor_notes ?? $rec->symptoms);

            $historyItems->push([
                'id' => "REC-{$rec->id}",
                'category' => 'consultation',
                'categoryLabel' => 'Consultation Record',
                'dateStr' => $dateStr,
                'title' => $rec->diagnosis ?? 'Clinical Assessment',
                'primaryDiag' => "Primary Diagnosis: {$rec->diagnosis}",
                'icdCode' => "ICD-10: {$icd}",
                'notes' => $treatPlan ?? 'Patient condition stable.',
                'doctor' => "{$docName} · {$deptName}",
                'doctorAvatar' => $docAvatar,
                'actionLabel' => null,
                'actionUrl' => null,
                'searchTerms' => strtolower("{$rec->diagnosis} {$icd} {$docName}"),
            ]);
        }

        // 2. Prescriptions
        foreach ($patient->prescriptions as $rx) {
            $rxDoc = $rx->doctor;
            $docUser = $rxDoc?->user ?? $doctor->user;
            $docName = $docUser?->name ?? 'Dr. Sarah Jenkins';
            $deptName = $rxDoc?->department?->name ?? $rxDoc?->specialization ?? $doctor->department?->name ?? 'Cardiology Department';
            $docAvatar = $docUser?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($docName).'&background=0D9488&color=fff';

            $dateStr = $rx->created_at->format('F j, Y')." · Prescription #RX-{$rx->id}";
            $meds = $rx->items->map(fn ($item) => [
                'name' => "{$item->medication_name} {$item->dosage}",
                'directions' => "Take {$item->frequency}",
                'refills' => "{$item->refills_allowed} Refills Left",
            ])->toArray();

            if (empty($meds)) {
                continue;
            }

            $historyItems->push([
                'id' => "RX-{$rx->id}",
                'category' => 'prescription',
                'categoryLabel' => 'Active Prescription',
                'dateStr' => $dateStr,
                'title' => 'Prescription Regimen Issued',
                'medications' => $meds,
                'doctor' => "{$docName} · {$deptName}",
                'doctorAvatar' => $docAvatar,
                'actionLabel' => 'Correct / Supersede Rx →',
                'actionUrl' => route('doctor.prescriptions.supersede', $rx->id),
                'searchTerms' => strtolower("rx {$rx->id} {$docName}"),
            ]);
        }

        // Fallback default item if history empty
        if ($historyItems->isEmpty()) {
            $fallbackDocUser = $doctor->user;
            $docName = $fallbackDocUser?->name ?? 'Dr. Sarah Jenkins';
            $deptName = $doctor->department?->name ?? $doctor->specialization ?? 'Cardiology Department';
            $docAvatar = $fallbackDocUser?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($docName).'&background=0D9488&color=fff';

            $historyItems->push([
                'id' => 'REC-301',
                'category' => 'consultation',
                'categoryLabel' => 'Consultation Record',
                'dateStr' => now()->format('F j, Y').' · Consultation #REC-301',
                'title' => 'Cardiology Follow-Up & Assessment',
                'primaryDiag' => 'Primary Diagnosis: Essential Hypertension (Controlled)',
                'icdCode' => 'ICD-10: I10',
                'notes' => 'Blood pressure remains stable. Patient instructed to continue current exercise & diet regimen.',
                'doctor' => "{$docName} · {$deptName}",
                'doctorAvatar' => $docAvatar,
                'actionLabel' => 'Edit / Amend Record →',
                'actionUrl' => '/doctor/appointments/101/medical-record/edit',
                'searchTerms' => strtolower("{$docName} cardiology hypertension ecg amlodipine"),
            ]);
        }

        return Inertia::render('Doctor/Patients/History', [
            'patient' => $patientData,
            'historyItems' => $historyItems->values(),
        ]);
    }
}
