<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorAppointmentController extends Controller
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

    public function index(Request $request): Response
    {
        $doctor = $this->getDoctor();

        $activeTab = $request->query('tab', 'all');
        $selectedDate = $request->query('date');
        $statusFilter = $request->query('status', 'All Statuses');
        $searchQuery = $request->query('search');

        $query = Appointment::with(['patient.user', 'department'])
            ->where('doctor_id', $doctor->id);

        if ($activeTab === 'today') {
            $query->whereDate('appointment_date', today());
        } elseif ($activeTab === 'upcoming') {
            $query->whereDate('appointment_date', '>=', today())
                ->whereNotIn('status', ['completed', 'cancelled', 'no_show']);
        } elseif ($activeTab === 'past') {
            $query->where(function ($q) {
                $q->whereDate('appointment_date', '<', today())
                    ->orWhereIn('status', ['completed', 'cancelled', 'no_show']);
            });
        }

        if (! empty($selectedDate)) {
            $query->whereDate('appointment_date', $selectedDate);
        }

        if ($statusFilter && $statusFilter !== 'All Statuses') {
            if ($statusFilter === 'Active Queue') {
                $query->whereIn('status', ['confirmed', 'pending']);
            } elseif ($statusFilter === 'Finished') {
                $query->whereIn('status', ['completed', 'cancelled', 'no_show']);
            } else {
                $normalizedStatus = strtolower(str_replace(' ', '_', $statusFilter));
                $query->where('status', $normalizedStatus);
            }
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->whereHas('patient.user', function ($uq) use ($searchQuery) {
                    $uq->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('email', 'like', "%{$searchQuery}%");
                })->orWhere('appointment_code', 'like', "%{$searchQuery}%");
            });
        }

        $now = Carbon::now();

        $appointments = $query->get()
            ->sortBy(function ($app) use ($now) {
                $appDate = $app->appointment_date ? Carbon::parse($app->appointment_date) : null;
                $startTimeStr = $app->start_time ?? '00:00:00';
                $appDateTime = $appDate ? Carbon::parse($appDate->format('Y-m-d').' '.$startTimeStr) : null;

                $isTimePassed = $appDateTime ? $appDateTime->lt($now) : false;
                $isFinished = in_array($app->status, ['completed', 'cancelled', 'no_show']);
                $isInProgress = $app->status === 'in_progress';

                if (! $isFinished && ! $isInProgress && ! $isTimePassed) {
                    $priority = 1; // Active waiting / upcoming at top
                } elseif ($isInProgress) {
                    $priority = 2; // Doctor seeing patient right now
                } elseif ($isTimePassed && ! $isFinished) {
                    $priority = 3; // Scheduled time passed
                } else {
                    $priority = 4; // Finished (completed, cancelled, no_show) at last
                }

                $timeKey = $appDateTime ? $appDateTime->timestamp : 0;
                $secondarySort = ($priority === 1) ? $timeKey : -$timeKey;

                return [$priority, $secondarySort];
            })
            ->values()
            ->map(function ($app) use ($now) {
                $pUser = $app->patient?->user;
                $pName = $pUser?->name ?? 'Patient Account';
                $initials = strtoupper(substr($pName, 0, 2));
                $dateFormatted = $app->appointment_date ? Carbon::parse($app->appointment_date)->format('M j, Y') : 'Today';
                $timeFormatted = $app->start_time ? Carbon::parse($app->start_time)->format('g:i A') : '10:00 AM';

                $appDate = $app->appointment_date ? Carbon::parse($app->appointment_date) : null;
                $startTimeStr = $app->start_time ?? '00:00:00';
                $appDateTime = $appDate ? Carbon::parse($appDate->format('Y-m-d').' '.$startTimeStr) : null;

                $isTimePassed = $appDateTime ? $appDateTime->lt($now) : false;
                $isFinished = in_array($app->status, ['completed', 'cancelled', 'no_show']);
                $isInProgress = $app->status === 'in_progress';

                $statusLabel = ucfirst(str_replace('_', ' ', $app->status));
                if ($isTimePassed && ! $isFinished && ! $isInProgress) {
                    $statusLabel .= ' (Time Passed)';
                }

                return [
                    'id' => $app->appointment_code,
                    'db_id' => $app->id,
                    'date' => $dateFormatted,
                    'time' => $timeFormatted,
                    'patientName' => $pName,
                    'patientRef' => "#{$app->patient?->patient_code}",
                    'avatarBg' => 'var(--lime)',
                    'avatarColor' => 'var(--lime-text)',
                    'avatarInitials' => $initials,
                    'visitType' => 'In-Person',
                    'status' => $app->status,
                    'statusLabel' => $statusLabel,
                    'isTimePassed' => $isTimePassed && ! $isFinished && ! $isInProgress,
                    'isFinished' => $isFinished,
                    'isInProgress' => $isInProgress,
                    'actionLabel' => $isInProgress ? 'In Session' : ($isFinished ? 'View Details' : 'Manage'),
                    'actionUrl' => route('doctor.appointments.show', $app->appointment_code),
                ];
            });

        $todayCount = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->whereNotIn('status', ['completed', 'cancelled', 'no_show'])
            ->count();

        return Inertia::render('Doctor/Appointments/Index', [
            'appointments' => $appointments,
            'todayCount' => $todayCount,
            'filters' => [
                'tab' => $activeTab,
                'date' => $selectedDate ?? today()->format('Y-m-d'),
                'status' => $statusFilter,
                'search' => $searchQuery ?? '',
            ],
        ]);
    }

    public function show(string $id): Response
    {
        $doctor = $this->getDoctor();

        $appointment = Appointment::with([
            'patient.user',
            'department',
            'medicalRecord',
            'prescriptions.items',
            'payment',
            'doctor',
        ])
            ->where(function ($q) use ($id) {
                $q->where('appointment_code', $id)->orWhere('id', $id);
            })
            ->firstOrFail();

        $doctor = $appointment->doctor ?? $this->getDoctor();
        $patient = $appointment->patient;
        $patientUser = $patient?->user;
        $pName = $patientUser?->name ?? 'Patient';
        $initials = strtoupper(substr($pName, 0, 2));

        $visitsCompleted = Appointment::where('patient_id', $patient?->id)
            ->where('status', 'completed')
            ->count();

        $vitals = $appointment->medicalRecord?->vitals ?? [
            'bp' => '120/80',
            'hr' => '72',
            'weight' => '74.5',
        ];

        $paymentStatusText = 'Paid ($'.number_format((float) ($appointment->consultation_fee_snapshot ?? $doctor->consultation_fee), 2).')';
        if ($appointment->payment) {
            $paymentStatusText = ucfirst($appointment->payment->status).' ($'.number_format((float) $appointment->payment->amount, 2).')';
        }

        $dateFormatted = $appointment->appointment_date ? Carbon::parse($appointment->appointment_date)->format('l, M j, Y') : 'Today';
        $timeFormatted = $appointment->start_time ? Carbon::parse($appointment->start_time)->format('g:i A') : '10:00 AM';
        if ($appointment->end_time) {
            $timeFormatted .= ' – '.Carbon::parse($appointment->end_time)->format('g:i A');
        }

        $prescriptions = $appointment->prescriptions->map(function ($rx) {
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

        $medicalRecordData = $appointment->medicalRecord ? [
            'id' => $appointment->medicalRecord->id,
            'symptoms' => $appointment->medicalRecord->symptoms,
            'diagnosis' => $appointment->medicalRecord->diagnosis,
            'icdCode' => is_array($appointment->medicalRecord->vitals) ? ($appointment->medicalRecord->vitals['icd_code'] ?? 'I10') : 'I10',
            'notes' => $appointment->medicalRecord->doctor_notes,
            'treatmentPlan' => is_array($appointment->medicalRecord->vitals) ? ($appointment->medicalRecord->vitals['treatment_plan'] ?? '') : '',
            'createdAt' => $appointment->medicalRecord->created_at->format('M j, Y g:i A'),
        ] : null;

        return Inertia::render('Doctor/Appointments/Show', [
            'appointment' => [
                'id' => $appointment->appointment_code,
                'db_id' => $appointment->id,
                'patientId' => $patient?->patient_code ?? 'MDF-9021',
                'patientDbId' => $patient?->id,
                'patientName' => $pName,
                'patientInitials' => $initials,
                'age' => $patient?->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 28,
                'gender' => ucfirst($patient?->gender ?? 'Male'),
                'bloodGroup' => $patient?->blood_group ?? 'O+',
                'allergies' => $patient?->allergies ?? 'None Reported',
                'visitsCompleted' => $visitsCompleted ?: 1,
                'date' => $dateFormatted,
                'time' => $timeFormatted,
                'mode' => 'In-Person Visit',
                'location' => 'Room 302, Harbor Ave Clinic',
                'phone' => $patientUser?->phone ?? '(555) 019-2831',
                'email' => $patientUser?->email ?? 'patient@example.com',
                'paymentStatus' => $paymentStatusText,
                'receipt' => 'Receipt #INV-'.(80000 + $appointment->id),
                'reason' => $appointment->reason ?? 'Routine follow-up consultation.',
                'status' => $appointment->status,
                'vitals' => [
                    'bp' => $vitals['bp'] ?? '120/80',
                    'hr' => $vitals['hr'] ?? $vitals['pulse'] ?? '72',
                    'weight' => $vitals['weight'] ?? $vitals['weight_kg'] ?? '74.5',
                ],
                'prescriptions' => $prescriptions,
                'medicalRecord' => $medicalRecordData,
            ],
        ]);
    }

    public function updateStatus(Request $request, string $id): RedirectResponse
    {
        $appointment = Appointment::where(function ($q) use ($id) {
            $q->where('appointment_code', $id)->orWhere('id', $id);
        })->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|string|in:confirmed,in_progress,completed,no_show,cancelled,scheduled,pending',
        ]);

        $appointment->status = $validated['status'];
        if ($validated['status'] === 'completed') {
            $appointment->completed_at = now();
        } elseif ($validated['status'] === 'confirmed') {
            $appointment->confirmed_at = now();
        }
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment status updated successfully.');
    }
}
