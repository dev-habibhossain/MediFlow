<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PatientMedicalRecordController extends Controller
{
    /**
     * List all medical records for the authenticated patient.
     */
    public function index(Request $request): Response
    {
        $patient = $this->resolvePatient($request);

        $records = MedicalRecord::with(['doctor.user', 'doctor.department', 'prescriptions.items'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function (MedicalRecord $rec) {
                $docUser = $rec->doctor?->user;
                $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Attending Physician';
                $docAvatar = $docUser?->avatar_url ?? null;
                $dept = $rec->doctor?->department?->name ?? $rec->doctor?->specialization ?? 'General Medicine';
                $dateCarbon = Carbon::parse($rec->created_at);

                return [
                    'id' => $rec->id,
                    'year' => $dateCarbon->format('Y'),
                    'date' => $dateCarbon->format('M j, Y'),
                    'category' => 'consultation',
                    'typeLabel' => 'Consultation',
                    'tagClass' => 'tag-consultation',
                    'title' => $rec->diagnosis ?? 'Medical Consultation',
                    'description' => $rec->doctor_notes ?? $rec->symptoms ?? 'Clinical notes on file.',
                    'doctorName' => $docName,
                    'doctorDept' => $dept,
                    'avatar' => $docAvatar,
                    'highlight' => false,
                    'buttonText' => 'View Full Record →',
                    'vitals' => $rec->vitals,
                ];
            });

        // Extract latest vitals from the most recent record that has them
        $latestVitals = $this->extractLatestVitals($records->toArray());

        return Inertia::render('Patient/Records/Index', [
            'records' => $records,
            'latestVitals' => $latestVitals,
        ]);
    }

    /**
     * Show a single medical record in full detail.
     */
    public function show(Request $request, int $id): Response
    {
        $patient = $this->resolvePatient($request);

        /** @var MedicalRecord $record */
        $record = MedicalRecord::with([
            'doctor.user',
            'doctor.department',
            'appointment',
            'prescriptions.items',
            'attachments',
        ])->where('patient_id', $patient->id)->findOrFail($id);

        $docUser = $record->doctor?->user;
        $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Attending Physician';
        $docAvatar = $docUser?->avatar_url ?? null;
        $dept = $record->doctor?->department?->name ?? $record->doctor?->specialization ?? 'General Medicine';
        $dateCarbon = Carbon::parse($record->created_at);

        // Build prescriptions list for sidebar
        $prescriptions = $record->prescriptions->map(function ($rx) {
            return [
                'id' => $rx->id,
                'prescription_code' => $rx->prescription_code,
                'items' => $rx->items->map(fn ($item) => [
                    'medication_name' => $item->medication_name,
                    'dosage' => $item->dosage,
                    'frequency' => $item->frequency,
                    'duration' => $item->duration,
                ])->toArray(),
            ];
        });

        // Build vitals from stored JSON
        $vitals = $record->vitals ?? [];

        return Inertia::render('Patient/Records/Show', [
            'record' => [
                'id' => $record->id,
                'date_formatted' => $dateCarbon->format('M j, Y'),
                'diagnosis' => $record->diagnosis,
                'symptoms' => $record->symptoms,
                'doctor_notes' => $record->doctor_notes,
                'vitals' => $vitals,
                'doctor_name' => $docName,
                'doctor_avatar' => $docAvatar,
                'department' => $dept,
                'appointment_id' => $record->appointment_id,
                'appointment_code' => $record->appointment?->appointment_code,
                'prescriptions' => $prescriptions,
                'attachments' => $record->attachments->map(fn ($att) => [
                    'id' => $att->id,
                    'name' => $att->name ?? basename((string) $att->path),
                    'size' => $att->size_formatted ?? null,
                    'url' => $att->url ?? null,
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

    /**
     * Extract latest vitals from records collection for display on the index strip.
     *
     * @param  array<int, array<string, mixed>>  $records
     * @return array<int, array<string, string>>
     */
    private function extractLatestVitals(array $records): array
    {
        foreach ($records as $rec) {
            if (! empty($rec['vitals'])) {
                $vitals = $rec['vitals'];
                $result = [];

                $map = [
                    'blood_pressure' => ['Blood Pressure', 'mmHg', 'heart'],
                    'heart_rate' => ['Heart Rate', 'bpm', 'pulse'],
                    'weight' => ['Body Weight', 'kg', 'scale'],
                    'blood_sugar' => ['Blood Sugar', 'mg/dL', 'drop'],
                    'temperature' => ['Body Temp', '°F', 'scale'],
                    'oxygen_saturation' => ['O₂ Saturation', '%', 'pulse'],
                ];

                foreach ($map as $key => [$label, $unit, $icon]) {
                    if (isset($vitals[$key]) && $vitals[$key]) {
                        $result[] = [
                            'label' => $label,
                            'value' => (string) $vitals[$key],
                            'unit' => $unit,
                            'sub' => 'From record: '.$rec['date'],
                            'icon' => $icon,
                        ];
                    }
                }

                if (! empty($result)) {
                    return $result;
                }
            }
        }

        return [];
    }
}
