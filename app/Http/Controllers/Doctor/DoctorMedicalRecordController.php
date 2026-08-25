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
            ->first();

        if (! $appointment) {
            abort(404, 'Appointment record not found.');
        }

        $doctor = $appointment->doctor ?? $this->getDoctor();
        $patient = $appointment->patient;
        $pUser = $patient?->user;
        $pName = $pUser?->name ?? 'Patient Account';
        $initials = strtoupper(substr($pName, 0, 2));

        return Inertia::render('Doctor/Records/Create', [
            'appointment' => [
                'id' => $appointment->appointment_code ?? (string) $appointment->id,
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
            ->first();

        if (! $appointment) {
            abort(404, 'Appointment record not found.');
        }

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
            'bp' => (isset($validated['bpSystolic']) && isset($validated['bpDiastolic'])) ? "{$validated['bpSystolic']}/{$validated['bpDiastolic']}" : '120/80',
            'bp_systolic' => $validated['bpSystolic'] ?? 120,
            'bp_diastolic' => $validated['bpDiastolic'] ?? 80,
            'pulse' => $validated['heartRate'] ?? 72,
            'weight_kg' => $validated['weight'] ?? 70,
            'icd_code' => $validated['icdCode'] ?? 'I10',
            'treatment_plan' => $validated['soapPlan'] ?? null,
        ];

        $notesParts = array_filter([
            $validated['soapSubjective'] ?? null ? "Subjective: {$validated['soapSubjective']}" : null,
            $validated['soapObjective'] ?? null ? "Objective: {$validated['soapObjective']}" : null,
            $validated['soapPlan'] ?? null ? "Treatment Plan: {$validated['soapPlan']}" : null,
        ]);
        $doctorNotes = ! empty($notesParts) ? implode("\n\n", $notesParts) : $validated['symptoms'];

        $record = MedicalRecord::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $doctor->id,
                'symptoms' => $validated['symptoms'],
                'diagnosis' => $validated['primaryDiagnosis'],
                'vitals' => $vitals,
                'doctor_notes' => $doctorNotes,
                'version' => 1,
            ]
        );

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filePath = $file->store('medical-records', 'public');
            $record->attachments()->create([
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id() ?? $doctor->user_id,
            ]);
        }

        $appointment->status = 'completed';
        $appointment->completed_at = now();
        $appointment->save();

        return redirect()->route('doctor.patients.history', $appointment->patient_id)
            ->with('success', 'Medical Record saved and added to patient history.');
    }
}
