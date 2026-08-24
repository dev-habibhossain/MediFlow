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
            $query->whereDate('appointment_date', '>=', today());
        } elseif ($activeTab === 'past') {
            $query->whereDate('appointment_date', '<', today());
        }

        if (! empty($selectedDate)) {
            $query->whereDate('appointment_date', $selectedDate);
        }

        if ($statusFilter && $statusFilter !== 'All Statuses') {
            $normalizedStatus = strtolower(str_replace(' ', '_', $statusFilter));
            $query->where('status', $normalizedStatus);
        }

        if ($searchQuery) {
            $query->whereHas('patient.user', function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('email', 'like', "%{$searchQuery}%");
            })->orWhere('appointment_code', 'like', "%{$searchQuery}%");
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($app) {
                $pUser = $app->patient?->user;
                $pName = $pUser?->name ?? 'Patient Account';
                $initials = strtoupper(substr($pName, 0, 2));
                $dateFormatted = $app->appointment_date ? Carbon::parse($app->appointment_date)->format('M j, Y') : 'Today';
                $timeFormatted = $app->start_time ? Carbon::parse($app->start_time)->format('g:i A') : '10:00 AM';

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
                    'statusLabel' => ucfirst(str_replace('_', ' ', $app->status)),
                    'actionLabel' => 'Manage',
                    'actionUrl' => route('doctor.appointments.show', $app->appointment_code),
                ];
            });

        $todayCount = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
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
            'prescriptions',
            'payment',
        ])
            ->where('doctor_id', $doctor->id)
            ->where(function ($q) use ($id) {
                $q->where('appointment_code', $id)->orWhere('id', $id);
            })
            ->firstOrFail();

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
            ],
        ]);
    }

    public function updateStatus(Request $request, string $id): RedirectResponse
    {
        $doctor = $this->getDoctor();

        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where(function ($q) use ($id) {
                $q->where('appointment_code', $id)->orWhere('id', $id);
            })
            ->firstOrFail();

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
