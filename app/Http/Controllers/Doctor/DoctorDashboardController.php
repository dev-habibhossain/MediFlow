<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DoctorDashboardController extends Controller
{
    protected function getDoctor(): Doctor
    {
        $user = auth()->user();
        if ($user && $user->doctor) {
            return $user->doctor->load(['user', 'department']);
        }

        $doctor = Doctor::with(['user', 'department'])->first();
        if (! $doctor) {
            abort(404, 'No doctor profile found.');
        }

        return $doctor;
    }

    public function __invoke(): Response
    {
        $doctor = $this->getDoctor();
        $doctorUser = $doctor->user;
        $doctorName = $doctorUser?->name ? "Dr. {$doctorUser->name}" : 'Doctor';

        // 1. Core Clinical Metrics
        $appointmentsTodayCount = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->count();

        $totalPatientsTreated = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->distinct('patient_id')
            ->count('patient_id');

        $avgRatingRaw = $doctor->reviews()->where('is_visible', true)->avg('rating');
        $averageRating = $avgRatingRaw ? round((float) $avgRatingRaw, 1) : 5.0;

        $pendingNotesCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->doesntHave('medicalRecord')
            ->count();

        $completedThisMonth = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->count();

        // 2. Next Scheduled Consultation (Current active or next upcoming)
        $now = Carbon::now();
        $nextAppointment = Appointment::with(['patient.user', 'department'])
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['confirmed', 'in_progress', 'pending'])
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->first();

        $nextBanner = null;
        if ($nextAppointment) {
            $patientUser = $nextAppointment->patient?->user;
            $pName = $patientUser?->name ?? 'Patient';
            $dateStr = $nextAppointment->appointment_date ? Carbon::parse($nextAppointment->appointment_date)->format('Y-m-d') : today()->format('Y-m-d');
            $startTimeStr = $nextAppointment->start_time ?? '09:00:00';
            $appDateTime = Carbon::parse("{$dateStr} {$startTimeStr}");

            $timeFormatted = $nextAppointment->start_time ? Carbon::parse($nextAppointment->start_time)->format('g:i A') : '09:00 AM';
            $dayText = $appDateTime->isToday() ? 'Today' : ($appDateTime->isTomorrow() ? 'Tomorrow' : $appDateTime->format('M j'));

            $deptName = $nextAppointment->department?->name ?? 'Clinical';

            $nextBanner = [
                'id' => $nextAppointment->appointment_code,
                'patient_name' => $pName,
                'patient_code' => "#{$nextAppointment->patient?->patient_code}",
                'reason' => $nextAppointment->reason ?? 'General Consultation',
                'status' => $nextAppointment->status,
                'time_details' => "{$dayText} at {$timeFormatted} · {$deptName} Visit",
                'action_url' => route('doctor.appointments.show', $nextAppointment->appointment_code),
            ];
        }

        // 3. Today's Appointment Queue (with priority sorting)
        $avatarPalette = [
            ['bg' => '#E0E7FF', 'color' => '#3730A3'],
            ['bg' => '#DCFCE7', 'color' => '#15803D'],
            ['bg' => '#FEF3C7', 'color' => '#B45309'],
            ['bg' => '#F3E8FF', 'color' => '#6B21A8'],
            ['bg' => '#FEE2E2', 'color' => '#B91C1C'],
            ['bg' => '#E0F2FE', 'color' => '#0369A1'],
        ];

        $todayQuery = Appointment::with(['patient.user', 'department'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today());

        $hasTodayAppointments = $todayQuery->count() > 0;

        $appointmentsSource = $hasTodayAppointments
            ? $todayQuery->get()
            : Appointment::with(['patient.user', 'department'])
                ->where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', '>=', today())
                ->orderBy('appointment_date', 'asc')
                ->take(8)
                ->get();

        $todayAppointments = $appointmentsSource
            ->sortBy(function ($app) use ($now) {
                $appDate = $app->appointment_date ? Carbon::parse($app->appointment_date) : null;
                $startTimeStr = $app->start_time ?? '00:00:00';
                $appDateTime = $appDate ? Carbon::parse($appDate->format('Y-m-d').' '.$startTimeStr) : null;

                $isTimePassed = $appDateTime ? $appDateTime->lt($now) : false;
                $isFinished = in_array($app->status, ['completed', 'cancelled', 'no_show']);
                $isInProgress = $app->status === 'in_progress';

                if (! $isFinished && ! $isInProgress && ! $isTimePassed) {
                    $priority = 1;
                } elseif ($isInProgress) {
                    $priority = 2;
                } elseif ($isTimePassed && ! $isFinished) {
                    $priority = 3;
                } else {
                    $priority = 4;
                }

                $timeKey = $appDateTime ? $appDateTime->timestamp : 0;
                $secondarySort = ($priority === 1) ? $timeKey : -$timeKey;

                return [$priority, $secondarySort];
            })
            ->values()
            ->take(8)
            ->map(function ($app) use ($now, $avatarPalette) {
                $pUser = $app->patient?->user;
                $patientName = $pUser?->name ?? 'Patient Account';
                $initials = strtoupper(substr($patientName, 0, 2));

                $timeFormatted = $app->start_time ? Carbon::parse($app->start_time)->format('g:i A') : '09:00 AM';
                $dateFormatted = $app->appointment_date ? Carbon::parse($app->appointment_date)->format('M j') : 'Today';

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

                $colorPair = $avatarPalette[$app->id % count($avatarPalette)];
                $deptName = $app->department?->name ? "{$app->department->name} Visit" : 'In-Person Visit';

                return [
                    'id' => $app->appointment_code,
                    'time' => $timeFormatted,
                    'date' => $dateFormatted,
                    'patientName' => $patientName,
                    'avatarBg' => $colorPair['bg'],
                    'avatarColor' => $colorPair['color'],
                    'avatarInitials' => $initials,
                    'patientMeta' => "#{$app->patient?->patient_code}",
                    'typeMode' => $deptName,
                    'status' => $app->status,
                    'statusLabel' => $statusLabel,
                    'isInProgress' => $isInProgress,
                    'isTimePassed' => $isTimePassed && ! $isFinished && ! $isInProgress,
                    'isFinished' => $isFinished,
                    'actionLabel' => $isInProgress ? 'In Session' : ($isFinished ? 'View Details' : 'Manage'),
                    'actionUrl' => route('doctor.appointments.show', $app->appointment_code),
                ];
            });

        // 4. Real Clinical Activity Feed (Prescriptions + Medical Records + Completed Consultations)
        $recentRecords = MedicalRecord::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($rec) {
                return [
                    'id' => 'rec-'.$rec->id,
                    'text' => "Medical Record finalized for {$rec->patient?->user?->name}.",
                    'time' => $rec->created_at->diffForHumans(),
                    'timestamp' => $rec->created_at->timestamp,
                    'type' => 'record',
                ];
            });

        $recentRx = Prescription::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($rx) {
                return [
                    'id' => 'rx-'.$rx->id,
                    'text' => "Prescription #{$rx->prescription_code} issued for {$rx->patient?->user?->name}.",
                    'time' => $rx->created_at->diffForHumans(),
                    'timestamp' => $rx->created_at->timestamp,
                    'type' => 'prescription',
                ];
            });

        $recentCompleted = Appointment::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->take(4)
            ->get()
            ->map(function ($app) {
                return [
                    'id' => 'app-'.$app->id,
                    'text' => "Consultation completed with {$app->patient?->user?->name}.",
                    'time' => $app->completed_at ? $app->completed_at->diffForHumans() : $app->updated_at->diffForHumans(),
                    'timestamp' => $app->completed_at ? $app->completed_at->timestamp : $app->updated_at->timestamp,
                    'type' => 'appointment',
                ];
            });

        $recentActivities = $recentRecords
            ->concat($recentRx)
            ->concat($recentCompleted)
            ->sortByDesc('timestamp')
            ->values()
            ->take(6);

        // Determine Greeting
        $hour = (int) now()->format('H');
        if ($hour < 12) {
            $greeting = "Good Morning, {$doctorName}";
        } elseif ($hour < 17) {
            $greeting = "Good Afternoon, {$doctorName}";
        } else {
            $greeting = "Good Evening, {$doctorName}";
        }

        return Inertia::render('Doctor/Dashboard', [
            'doctor' => [
                'name' => $doctorName,
                'specialty' => $doctor->specialization ?? 'General Physician',
                'department' => $doctor->department?->name ?? 'General Medicine',
            ],
            'greeting' => $greeting,
            'stats' => [
                'appointments_today' => $appointmentsTodayCount,
                'total_patients' => $totalPatientsTreated,
                'average_rating' => $averageRating,
                'pending_notes' => $pendingNotesCount,
                'completed_this_month' => $completedThisMonth,
            ],
            'nextBanner' => $nextBanner,
            'hasTodayAppointments' => $hasTodayAppointments,
            'todayAppointments' => $todayAppointments,
            'recentActivities' => $recentActivities,
        ]);
    }
}
