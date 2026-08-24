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
        $appointment = Appointment::with(['patient.user', 'doctor.user', 'prescriptions.items'])
            ->where(function ($q) use ($appointmentId) {
                $q->where('appointment_code', $appointmentId)->orWhere('id', $appointmentId);
            })
            ->firstOrFail();

        $doctor = $appointment->doctor ?? $this->getDoctor();
        $patient = $appointment->patient;
        $pUser = $patient?->user;
        $pName = $pUser?->name ?? 'Patient Account';
        $initials = strtoupper(substr($pName, 0, 2));

        $existingPrescriptions = $appointment->prescriptions->map(function ($rx) {
            return [
                'id' => $rx->id,
                'code' => $rx->prescription_code,
                'status' => $rx->status,
                'issuedAt' => $rx->issued_at ? Carbon::parse($rx->issued_at)->format('M j, Y g:i A') : $rx->created_at->format('M j, Y g:i A'),
                'notes' => $rx->special_instructions,
                'items' => $rx->items->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->medication_name,
                    'dosage' => $item->dosage,
                    'frequency' => $item->frequency,
                    'duration' => $item->duration,
                    'refills' => $item->refills_allowed,
                    'instructions' => $item->instructions,
                ])->toArray(),
            ];
        })->toArray();

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
            'existingPrescriptions' => $existingPrescriptions,
        ]);
    }

    public function store(Request $request, string $appointmentId): RedirectResponse
    {
        $appointment = Appointment::with(['medicalRecord', 'doctor'])
            ->where(function ($q) use ($appointmentId) {
                $q->where('appointment_code', $appointmentId)->orWhere('id', $appointmentId);
            })
            ->firstOrFail();

        $doctor = $appointment->doctor ?? $this->getDoctor();

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
            'medical_record_id' => $appointment->medicalRecord?->id,
            'special_instructions' => $validated['pharmacyNotes'] ?? null,
            'issued_at' => now(),
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

        return Inertia::render('Doctor/Prescriptions/Supersede', [
            'prescription' => [
                'id' => $prescription->id,
                'code' => $prescription->prescription_code,
                'notes' => $prescription->special_instructions,
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

        $oldRx = Prescription::where('id', $id)
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
            'medical_record_id' => $oldRx->medical_record_id,
            'special_instructions' => $validated['pharmacyNotes'] ?? null,
            'supersedes_id' => $oldRx->id,
            'issued_at' => now(),
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

    public function update(Request $request, string $id): RedirectResponse
    {
        $prescription = Prescription::findOrFail($id);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.duration' => 'required|string',
            'items.*.refills' => 'required',
            'items.*.instructions' => 'nullable|string',
            'pharmacyNotes' => 'nullable|string',
        ]);

        $prescription->update([
            'special_instructions' => $validated['pharmacyNotes'] ?? null,
        ]);

        $prescription->items()->delete();

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

        return redirect()->back()->with('success', "Prescription #{$prescription->prescription_code} updated successfully.");
    }

    public function destroy(string $id): RedirectResponse
    {
        $prescription = Prescription::findOrFail($id);
        $code = $prescription->prescription_code;
        $prescription->items()->delete();
        $prescription->delete();

        return redirect()->back()->with('success', "Prescription #{$code} deleted successfully.");
    }
}
