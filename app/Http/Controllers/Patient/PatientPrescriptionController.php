<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PatientPrescriptionController extends Controller
{
    /**
     * List all prescriptions for the authenticated patient.
     */
    public function index(Request $request): Response
    {
        $patient = $this->resolvePatient($request);

        $prescriptions = Prescription::with(['doctor.user', 'doctor.department', 'items'])
            ->where('patient_id', $patient->id)
            ->whereNull('supersedes_id') // Show only latest, not superseded ones
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Prescription $rx) {
                $docUser = $rx->doctor?->user;
                $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Physician';
                $docAvatar = $docUser?->avatar_url ?? 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&q=80&w=150';
                $dept = $rx->doctor?->department?->name ?? $rx->doctor?->specialization ?? 'General Medicine';

                $issuedDate = $rx->issued_at ?? $rx->created_at;
                $issuedCarbon = $issuedDate ? Carbon::parse($issuedDate) : Carbon::parse($rx->created_at);

                // A prescription is considered "active" if issued within 90 days and not superseded
                $isActive = $issuedCarbon->diffInDays(Carbon::now()) <= 90;

                $medications = $rx->items->map(fn ($item) => [
                    'name' => $item->medication_name,
                    'dose' => trim(($item->dosage ?? '').' '.($item->frequency ?? '')),
                    'freq' => $item->duration ?? 'As prescribed',
                ])->toArray();

                return [
                    'id' => $rx->id,
                    'code' => '#'.$rx->prescription_code,
                    'doctorName' => $docName,
                    'doctorDept' => $dept,
                    'avatar' => $docAvatar,
                    'issuedDate' => $issuedCarbon->format('M j, Y'),
                    'status' => $isActive ? 'active' : 'expired',
                    'refillsText' => '—',
                    'medications' => $medications,
                    'special_instructions' => $rx->special_instructions,
                ];
            });

        $activeCount = $prescriptions->where('status', 'active')->count();
        $totalCount = $prescriptions->count();

        return Inertia::render('Patient/Prescriptions/Index', [
            'prescriptions' => $prescriptions->values(),
            'stats' => [
                'active_count' => $activeCount,
                'expired_count' => $totalCount - $activeCount,
                'total_count' => $totalCount,
            ],
        ]);
    }

    /**
     * Show a single prescription in full formal detail.
     */
    public function show(Request $request, int $id): Response
    {
        $patient = $this->resolvePatient($request);

        /** @var Prescription $rx */
        $rx = Prescription::with([
            'doctor.user',
            'doctor.department',
            'patient.user',
            'items',
            'appointment',
        ])->where('patient_id', $patient->id)->findOrFail($id);

        $docUser = $rx->doctor?->user;
        $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Physician';
        $licenseNumber = $rx->doctor?->license_number ?? 'N/A';
        $specialty = $rx->doctor?->specialization ?? $rx->doctor?->department?->name ?? 'General Medicine';

        $patientUser = $rx->patient?->user ?? $request->user();
        $issuedDate = $rx->issued_at ?? $rx->created_at;
        $issuedCarbon = Carbon::parse($issuedDate);
        $validUntilCarbon = $issuedCarbon->copy()->addDays(90);

        $isActive = $issuedCarbon->diffInDays(Carbon::now()) <= 90;

        return Inertia::render('Patient/Prescriptions/Show', [
            'prescription' => [
                'id' => $rx->id,
                'prescription_code' => '#'.$rx->prescription_code,
                'status' => $isActive ? 'active' : 'expired',
                'issued_date' => $issuedCarbon->format('M j, Y'),
                'valid_until' => $validUntilCarbon->format('M j, Y'),
                'special_instructions' => $rx->special_instructions,
                'doctor_name' => $docName,
                'doctor_license' => $licenseNumber,
                'doctor_specialty' => $specialty,
                'patient_name' => $patientUser->name,
                'patient_code' => $rx->patient?->patient_code ?? 'MDF-'.$rx->patient_id,
                'appointment_id' => $rx->appointment_id,
                'appointment_code' => $rx->appointment?->appointment_code,
                'appointment_label' => $rx->appointment
                    ? 'Appointment #'.($rx->appointment->appointment_code)
                    : null,
                'items' => $rx->items->map(fn ($item) => [
                    'id' => $item->id,
                    'medication_name' => $item->medication_name,
                    'dosage' => $item->dosage,
                    'frequency' => $item->frequency,
                    'duration' => $item->duration,
                    'instructions' => $item->notes,
                ])->toArray(),
            ],
        ]);
    }

    /**
     * Resolve (or auto-create) the Patient record for the authenticated user.
     */
    private function resolvePatient(Request $request): Patient
    {
        $user = $request->user();
        $patient = Patient::where('user_id', $user->id)->first();

        if (! $patient) {
            $patient = Patient::create([
                'user_id' => $user->id,
                'patient_code' => 'MDF-'.str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            ]);
        }

        return $patient;
    }
}
