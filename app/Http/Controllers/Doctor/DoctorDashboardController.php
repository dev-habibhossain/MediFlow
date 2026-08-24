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
            return $user->doctor;
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

        // 1. Core Metrics
        $appointmentsTodayCount = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->count();

        $totalPatientsTreated = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->distinct('patient_id')
            ->count('patient_id');

        $averageRating = round((float) ($doctor->reviews()->avg('rating') ?? 4.9), 1);

        $pendingNotesCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->doesntHave('medicalRecord')
            ->count();

        // 2. Next Scheduled Consultation
        $nextAppointment = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['confirmed', 'in_progress', 'scheduled'])
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->first();

        $nextBanner = null;
        if ($nextAppointment) {
            $patientUser = $nextAppointment->patient?->user;
            $timeFormatted = Carbon::parse($nextAppointment->appointment_date->format('Y-m-d').' '.$nextAppointment->start_time)->format('g:i A');
            $nextBanner = [
                'id' => $nextAppointment->appointment_code,
                'patient_name' => $patientUser?->name ?? 'Patient',
                'reason' => $nextAppointment->reason ?? 'Consultation',
                'time_details' => "Today at {$timeFormatted} · In-Person Visit · Room 302",
                'action_url' => route('doctor.appointments.show', $nextAppointment->appointment_code),
            ];
        }

        // 3. Today's Schedule Queue
        $todayAppointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($app) {
                $pUser = $app->patient?->user;
                $patientName = $pUser?->name ?? 'Patient';
                $initials = strtoupper(substr($patientName, 0, 2));

                $timeFormatted = $app->start_time ? Carbon::parse($app->start_time)->format('g:i A') : '09:00 AM';

                return [
                    'id' => $app->appointment_code,
                    'time' => $timeFormatted,
                    'patientName' => $patientName,
                    'avatarBg' => 'var(--lime)',
                    'avatarColor' => 'var(--lime-text)',
                    'avatarInitials' => $initials,
                    'patientMeta' => "Patient #{$app->patient?->patient_code}",
                    'typeMode' => 'In-Person Visit',
                    'status' => $app->status,
                    'statusLabel' => ucfirst(str_replace('_', ' ', $app->status)),
                    'actionLabel' => 'Manage',
                    'actionUrl' => route('doctor.appointments.show', $app->appointment_code),
                ];
            });

        // 4. Recent Clinical Activity Feed
        $recentActivities = collect();

        $recentRecords = MedicalRecord::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($rec) {
                return [
                    'id' => 'rec-'.$rec->id,
                    'text' => "Medical Record finalized for {$rec->patient?->user?->name}.",
                    'time' => $rec->created_at->diffForHumans(),
                    'is_blue' => true,
                ];
            });

        $recentRx = Prescription::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($rx) {
                return [
                    'id' => 'rx-'.$rx->id,
                    'text' => "Prescription #{$rx->prescription_code} issued for {$rx->patient?->user?->name}.",
                    'time' => $rx->created_at->diffForHumans(),
                    'is_blue' => false,
                ];
            });

        $recentActivities = $recentRecords->concat($recentRx)->sortByDesc('time')->values()->take(5);

        if ($recentActivities->isEmpty()) {
            $recentActivities = collect([
                [
                    'id' => 'default-1',
                    'text' => 'System schedule synchronized successfully.',
                    'time' => 'Today',
                    'is_blue' => false,
                ],
            ]);
        }

        return Inertia::render('Doctor/Dashboard', [
            'stats' => [
                'appointments_today' => $appointmentsTodayCount,
                'total_patients' => $totalPatientsTreated,
                'average_rating' => $averageRating,
                'pending_notes' => $pendingNotesCount,
            ],
            'nextBanner' => $nextBanner,
            'todayAppointments' => $todayAppointments,
            'recentActivities' => $recentActivities,
        ]);
    }
}
