<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorMedicalRecordController extends Controller
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

    public function create(string $appointmentId): Response
    {
        $appointment = Appointment::with(['patient.user', 'doctor'])
            ->where(function ($q) use ($appointmentId) {
                $q->where('appointment_code', $appointmentId)->orWhere('id', $appointmentId);
            })
            ->firstOrFail();

        $doctor = $appointment->doctor ?? $this->getDoctor();
        $patient = $appointment->patient;
        $pUser = $patient?->user;
        $pName = $pUser?->name ?? 'Patient Account';
        $initials = strtoupper(substr($pName, 0, 2));

        return Inertia::render('Doctor/Records/Create', [
            'appointment' => [
                'id' => $appointment->appointment_code,
                'db_id' => $appointment->id,
                'date' => $appointment->appointment_date ? Carbon::parse($appointment->appointment_date)->format('M j, Y') : 'Today',
            ],
            'patient' => [
                'id' => $patient?->patient_code ?? 'MDF-9021',
                'name' => $pName,
                'initials' => $initials,
                'gender' => ucfirst($patient?->gender ?? 'Male'),
                'age' => $patient?->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 28,
                'bloodGroup' => $patient?->blood_group ?? 'O+',
            ],
        ]);
    }

    public function store(Request $request, string $appointmentId): RedirectResponse
    {
        $appointment = Appointment::with('doctor')
            ->where(function ($q) use ($appointmentId) {
                $q->where('appointment_code', $appointmentId)->orWhere('id', $appointmentId);
            })
            ->firstOrFail();

        $doctor = $appointment->doctor ?? $this->getDoctor();

        $validated = $request->validate([
            'symptoms' => 'required|string',
            'primaryDiagnosis' => 'required|string',
            'icdCode' => 'nullable|string',
            'bpSystolic' => 'nullable|numeric',
            'bpDiastolic' => 'nullable|numeric',
            'heartRate' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'soapSubjective' => 'nullable|string',
            'soapObjective' => 'nullable|string',
            'soapPlan' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('medical-records', 'public');
        }

        $vitals = [
            'bp' => "{$validated['bpSystolic']}/{$validated['bpDiastolic']}",
            'bp_systolic' => $validated['bpSystolic'],
            'bp_diastolic' => $validated['bpDiastolic'],
            'pulse' => $validated['heartRate'],
            'weight_kg' => $validated['weight'],
        ];

        MedicalRecord::create([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $doctor->id,
            'appointment_id' => $appointment->id,
            'symptoms' => $validated['symptoms'],
            'diagnosis' => $validated['primaryDiagnosis'],
            'icd_code' => $validated['icdCode'] ?? 'I10',
            'vitals' => $vitals,
            'doctor_notes' => trim(($validated['soapSubjective'] ?? '')."\n\n".($validated['soapObjective'] ?? '')),
            'treatment_plan' => $validated['soapPlan'] ?? null,
            'file_attachment_path' => $filePath,
            'version' => 1,
        ]);

        $appointment->status = 'completed';
        $appointment->completed_at = now();
        $appointment->save();

        return redirect()->route('doctor.appointments.show', $appointment->appointment_code)
            ->with('success', 'Medical Record saved and visit completed successfully.');
    }

    public function edit(string $id): Response
    {
        $doctor = $this->getDoctor();

        $record = MedicalRecord::with(['patient.user', 'appointment'])
            ->where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->firstOrFail();

        $patient = $record->patient;
        $pUser = $patient?->user;
        $pName = $pUser?->name ?? 'Patient Account';
        $initials = strtoupper(substr($pName, 0, 2));

        $vitals = $record->vitals ?? [];

        return Inertia::render('Doctor/Records/Edit', [
            'record' => [
                'id' => $record->id,
                'symptoms' => $record->symptoms,
                'primaryDiagnosis' => $record->diagnosis,
                'icdCode' => $record->icd_code ?? 'I10',
                'bpSystolic' => $vitals['bp_systolic'] ?? 120,
                'bpDiastolic' => $vitals['bp_diastolic'] ?? 80,
                'heartRate' => $vitals['pulse'] ?? 72,
                'weight' => $vitals['weight_kg'] ?? 74.5,
                'soapSubjective' => $record->doctor_notes,
                'soapObjective' => '',
                'soapPlan' => $record->treatment_plan,
                'version' => $record->version,
            ],
            'appointment' => [
                'id' => $record->appointment?->appointment_code ?? "MDF-{$record->appointment_id}",
                'date' => $record->created_at->format('M j, Y'),
            ],
            'patient' => [
                'id' => $patient?->patient_code ?? 'MDF-9021',
                'name' => $pName,
                'initials' => $initials,
                'gender' => ucfirst($patient?->gender ?? 'Male'),
                'age' => $patient?->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 28,
                'bloodGroup' => $patient?->blood_group ?? 'O+',
            ],
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $doctor = $this->getDoctor();

        $record = MedicalRecord::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'symptoms' => 'required|string',
            'primaryDiagnosis' => 'required|string',
            'icdCode' => 'nullable|string',
            'bpSystolic' => 'nullable|numeric',
            'bpDiastolic' => 'nullable|numeric',
            'heartRate' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'soapSubjective' => 'nullable|string',
            'soapPlan' => 'nullable|string',
        ]);

        $vitals = [
            'bp' => "{$validated['bpSystolic']}/{$validated['bpDiastolic']}",
            'bp_systolic' => $validated['bpSystolic'],
            'bp_diastolic' => $validated['bpDiastolic'],
            'pulse' => $validated['heartRate'],
            'weight_kg' => $validated['weight'],
        ];

        $record->update([
            'symptoms' => $validated['symptoms'],
            'diagnosis' => $validated['primaryDiagnosis'],
            'icd_code' => $validated['icdCode'] ?? 'I10',
            'vitals' => $vitals,
            'doctor_notes' => $validated['soapSubjective'],
            'treatment_plan' => $validated['soapPlan'],
            'version' => $record->version + 1,
        ]);

        $appCode = $record->appointment?->appointment_code ?? $record->appointment_id;

        return redirect()->route('doctor.appointments.show', $appCode)
            ->with('success', 'Medical Record amended successfully.');
    }
}
