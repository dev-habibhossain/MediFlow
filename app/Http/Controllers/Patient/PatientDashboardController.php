<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PatientDashboardController extends Controller
{
    /**
     * Handle the incoming request for Patient Dashboard.
     */
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        // Retrieve or create patient profile for current user
        $patient = Patient::where('user_id', $user->id)->first();

        if (! $patient) {
            $patient = Patient::create([
                'user_id' => $user->id,
                'patient_code' => 'MDF-'.str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            ]);
        }

        $now = Carbon::now();
        $today = $now->copy()->startOfDay();

        // 1. Metric Stats
        $upcomingVisitsCount = Appointment::where('patient_id', $patient->id)
            ->whereIn('status', ['Confirmed', 'Scheduled', 'Pending', 'In Progress'])
            ->where('appointment_date', '>=', $today)
            ->count();

        $activePrescriptionsCount = Prescription::where('patient_id', $patient->id)->count();

        $medicalRecordsCount = MedicalRecord::where('patient_id', $patient->id)->count();

        $completedVisitsCount = Appointment::where('patient_id', $patient->id)
            ->where('status', 'Completed')
            ->count();

        // 2. Next Scheduled Consultation Hero Banner
        $nextApp = Appointment::with(['doctor.user', 'doctor.department', 'department'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['Confirmed', 'Scheduled', 'Pending', 'In Progress'])
            ->where('appointment_date', '>=', $today)
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->first();

        $nextAppointmentData = null;
        if ($nextApp) {
            $docUser = $nextApp->doctor?->user;
            $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Attending Physician';
            $docAvatar = $docUser?->avatar_url ?? 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=150';
            $specialty = $nextApp->doctor?->specialization ?? $nextApp->department?->name ?? 'General Medicine';

            $appDate = Carbon::parse($nextApp->appointment_date);
            $timeFormatted = $nextApp->start_time ? Carbon::parse($nextApp->start_time)->format('g:i A') : 'TBD';

            $nextAppointmentData = [
                'id' => $nextApp->id,
                'appointment_code' => $nextApp->appointment_code,
                'doctor_name' => $docName,
                'doctor_avatar' => $docAvatar,
                'specialty' => $specialty,
                'date_formatted' => $appDate->format('l, M j, Y'),
                'time_formatted' => $timeFormatted,
                'type' => 'In-Person Visit',
                'status' => $nextApp->status,
                'reason' => $nextApp->reason,
            ];
        }

        // 3. Upcoming Appointments (Top 5)
        $upcomingAppointments = Appointment::with(['doctor.user', 'doctor.department', 'department'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['Confirmed', 'Scheduled', 'Pending', 'In Progress'])
            ->where('appointment_date', '>=', $today)
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get()
            ->map(function ($app) {
                $docUser = $app->doctor?->user;
                $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Physician';
                $docAvatar = $docUser?->avatar_url ?? 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&q=80&w=100';
                $specialty = $app->doctor?->specialization ?? $app->department?->name ?? 'General Medicine';
                $appDate = Carbon::parse($app->appointment_date);
                $timeFormatted = $app->start_time ? Carbon::parse($app->start_time)->format('g:i A') : 'TBD';

                return [
                    'id' => $app->id,
                    'appointment_code' => $app->appointment_code,
                    'doctor_name' => $docName,
                    'doctor_avatar' => $docAvatar,
                    'specialty' => $specialty,
                    'date_formatted' => $appDate->format('M j, Y'),
                    'time_formatted' => $timeFormatted,
                    'type' => 'In-Person',
                    'status' => $app->status,
                ];
            });

        // 4. Notifications / Activity Feed
        $recentNotifications = collect();

        // Recent completed appointment records
        $recentCompleted = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->where('status', 'Completed')
            ->orderBy('updated_at', 'desc')
            ->take(2)
            ->get();

        foreach ($recentCompleted as $comp) {
            $docName = $comp->doctor?->user?->name ? 'Dr. '.$comp->doctor->user->name : 'your doctor';
            $recentNotifications->push([
                'id' => 'app-comp-'.$comp->id,
                'text' => "Consultation marked completed by {$docName}.",
                'time' => $comp->updated_at ? $comp->updated_at->diffForHumans() : 'Recently',
                'bg_class' => 'bg-green',
                'url' => '/patient/medical-records',
            ]);
        }

        // Recent prescriptions
        $recentPrescriptions = Prescription::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        foreach ($recentPrescriptions as $rx) {
            $recentNotifications->push([
                'id' => 'rx-'.$rx->id,
                'text' => "New prescription issued: {$rx->medication_name}.",
                'time' => $rx->created_at ? $rx->created_at->diffForHumans() : 'Recently',
                'bg_class' => 'bg-amber',
                'url' => '/patient/prescriptions',
            ]);
        }

        // Fallback default notifications if no records exist yet
        if ($recentNotifications->isEmpty()) {
            $recentNotifications->push([
                'id' => 'def-1',
                'text' => 'Welcome to MediFlow Patient Portal! Book your first consultation online.',
                'time' => 'Just now',
                'bg_class' => 'bg-green',
                'url' => '/appointments/book',
            ]);
        }

        return Inertia::render('Patient/Dashboard', [
            'patientInfo' => [
                'id' => $patient->id,
                'code' => $patient->patient_code ?? 'MDF-'.$patient->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'stats' => [
                'upcoming_visits' => $upcomingVisitsCount,
                'active_prescriptions' => $activePrescriptionsCount,
                'medical_records' => $medicalRecordsCount,
                'completed_visits' => $completedVisitsCount,
            ],
            'nextAppointment' => $nextAppointmentData,
            'upcomingAppointments' => $upcomingAppointments,
            'recentNotifications' => $recentNotifications->values(),
        ]);
    }
}
