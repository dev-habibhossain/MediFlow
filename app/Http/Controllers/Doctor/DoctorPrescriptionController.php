<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorPrescriptionController extends Controller
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
        $doctor = $this->getDoctor();

        $appointment = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->where(function ($q) use ($appointmentId) {
                $q->where('appointment_code', $appointmentId)->orWhere('id', $appointmentId);
            })
            ->firstOrFail();

        $patient = $appointment->patient;
        $pUser = $patient?->user;
        $pName = $pUser?->name ?? 'Patient Account';
        $initials = strtoupper(substr($pName, 0, 2));

        return Inertia::render('Doctor/Prescriptions/Create', [
            'appointment' => [
                'id' => $appointment->appointment_code,
                'db_id' => $appointment->id,
            ],
            'patient' => [
                'id' => $patient?->patient_code ?? 'MDF-9021',
                'name' => $pName,
                'initials' => $initials,
                'gender' => ucfirst($patient?->gender ?? 'Male'),
                'age' => $patient?->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 28,
                'bloodGroup' => $patient?->blood_group ?? 'O+',
                'allergies' => $patient?->allergies ?? 'Penicillin (Mild)',
            ],
            'doctor' => [
                'name' => $doctor->user?->name ?? 'Dr. Sarah Jenkins',
                'license' => $doctor->license_number ?? 'MD-90412',
            ],
        ]);
    }

    public function store(Request $request, string $appointmentId): RedirectResponse
    {
        $doctor = $this->getDoctor();

        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where(function ($q) use ($appointmentId) {
                $q->where('appointment_code', $appointmentId)->orWhere('id', $appointmentId);
            })
            ->firstOrFail();

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.duration' => 'required|string',
            'items.*.refills' => 'required',
            'items.*.instructions' => 'nullable|string',
            'pharmacyNotes' => 'nullable|string',
        ]);

        $prescriptionCode = 'RX-'.strtoupper(substr(uniqid(), -6));

        $prescription = Prescription::create([
            'prescription_code' => $prescriptionCode,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $doctor->id,
            'appointment_id' => $appointment->id,
            'status' => 'active',
            'notes' => $validated['pharmacyNotes'] ?? null,
        ]);

        foreach ($validated['items'] as $itemData) {
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medication_name' => $itemData['name'],
                'dosage' => 'As Prescribed',
                'frequency' => $itemData['frequency'],
                'duration' => $itemData['duration'],
                'refills_allowed' => (int) $itemData['refills'],
                'instructions' => $itemData['instructions'] ?? null,
            ]);
        }

        return redirect()->route('doctor.appointments.show', $appointment->appointment_code)
            ->with('success', "Prescription #{$prescriptionCode} issued successfully.");
    }

    public function supersede(string $id): Response
    {
        $doctor = $this->getDoctor();

        $prescription = Prescription::with(['patient.user', 'items', 'appointment'])
            ->where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->firstOrFail();

        $patient = $prescription->patient;
        $pUser = $patient?->user;
        $pName = $pUser?->name ?? 'Patient Account';
        $initials = strtoupper(substr($pName, 0, 2));

        $items = $prescription->items->map(fn ($item) => [
            'name' => $item->medication_name,
            'frequency' => $item->frequency,
            'duration' => $item->duration,
            'refills' => (string) $item->refills_allowed,
            'instructions' => $item->instructions,
        ])->toArray();

        if (empty($items)) {
            $items = [
                [
                    'name' => 'Amlodipine Besylate 5mg',
                    'frequency' => '1x Daily (Morning)',
                    'duration' => '90 Days',
                    'refills' => '2',
                    'instructions' => 'Take in the morning',
                ],
            ];
        }

        return Inertia::render('Doctor/Prescriptions/Supersede', [
            'prescription' => [
                'id' => $prescription->id,
                'code' => $prescription->prescription_code,
                'notes' => $prescription->notes,
                'items' => $items,
            ],
            'patient' => [
                'id' => $patient?->patient_code ?? 'MDF-9021',
                'name' => $pName,
                'initials' => $initials,
                'allergies' => $patient?->allergies ?? 'Penicillin (Mild)',
            ],
            'doctor' => [
                'name' => $doctor->user?->name ?? 'Dr. Sarah Jenkins',
                'license' => $doctor->license_number ?? 'MD-90412',
            ],
        ]);
    }

    public function storeSupersede(Request $request, string $id): RedirectResponse
    {
        $doctor = $this->getDoctor();

        $oldRx = Prescription::where('doctor_id', $doctor->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.duration' => 'required|string',
            'items.*.refills' => 'required',
            'items.*.instructions' => 'nullable|string',
            'pharmacyNotes' => 'nullable|string',
        ]);

        $oldRx->status = 'superseded';
        $oldRx->save();

        $newCode = 'RX-'.strtoupper(substr(uniqid(), -6));

        $newRx = Prescription::create([
            'prescription_code' => $newCode,
            'patient_id' => $oldRx->patient_id,
            'doctor_id' => $doctor->id,
            'appointment_id' => $oldRx->appointment_id,
            'supersedes_id' => $oldRx->id,
            'status' => 'active',
            'notes' => $validated['pharmacyNotes'] ?? null,
        ]);

        foreach ($validated['items'] as $itemData) {
            PrescriptionItem::create([
                'prescription_id' => $newRx->id,
                'medication_name' => $itemData['name'],
                'dosage' => 'As Prescribed',
                'frequency' => $itemData['frequency'],
                'duration' => $itemData['duration'],
                'refills_allowed' => (int) $itemData['refills'],
                'instructions' => $itemData['instructions'] ?? null,
            ]);
        }

        $appCode = $oldRx->appointment?->appointment_code ?? $oldRx->appointment_id ?? '101';

        return redirect()->route('doctor.appointments.show', $appCode)
            ->with('success', "Prescription superseded. New Rx #{$newCode} generated.");
    }
}
