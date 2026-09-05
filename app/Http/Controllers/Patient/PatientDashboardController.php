<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                'date_of_birth' => '1995-01-01',
            ]);
        }

        $now = Carbon::now();
        $today = $now->copy()->startOfDay();

        $activeStatuses = ['confirmed', 'scheduled', 'pending', 'in_progress', 'Confirmed', 'Scheduled', 'Pending', 'In Progress'];
        $completedStatuses = ['completed', 'Completed'];

        // 1. Metric Stats
        $upcomingVisitsCount = Appointment::where('patient_id', $patient->id)
            ->whereIn('status', $activeStatuses)
            ->where('appointment_date', '>=', $today)
            ->count();

        $activePrescriptionsCount = Prescription::where('patient_id', $patient->id)
            ->whereNull('supersedes_id')
            ->count();

        $medicalRecordsCount = MedicalRecord::where('patient_id', $patient->id)->count();

        $completedVisitsCount = Appointment::where('patient_id', $patient->id)
            ->whereIn('status', $completedStatuses)
            ->count();

        // 2. Next Scheduled Consultation Hero Banner
        $nextApp = Appointment::with(['doctor.user', 'doctor.department', 'department'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', $activeStatuses)
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
            $deptName = $nextApp->department?->name ?? $nextApp->doctor?->department?->name ?? $specialty;

            $appDate = Carbon::parse($nextApp->appointment_date);
            $timeFormatted = $nextApp->start_time ? Carbon::parse($nextApp->start_time)->format('g:i A') : 'TBD';

            $relativeDay = $appDate->isToday()
                ? 'Today'
                : ($appDate->isTomorrow()
                    ? 'Tomorrow'
                    : ($appDate->diffInDays($today) <= 7 ? $appDate->format('l') : 'In '.$appDate->diffInDays($today).' days'));

            $nextAppointmentData = [
                'id' => $nextApp->id,
                'appointment_code' => $nextApp->appointment_code,
                'doctor_name' => $docName,
                'doctor_avatar' => $docAvatar,
                'doctor_license' => $nextApp->doctor?->license_number ?? 'N/A',
                'specialty' => $specialty,
                'department' => $deptName,
                'date_formatted' => $appDate->format('l, M j, Y'),
                'time_formatted' => $timeFormatted,
                'relative_day' => $relativeDay,
                'type' => 'In-Person Consultation',
                'status' => $nextApp->status,
                'reason' => $nextApp->reason ?? 'Routine Medical Consultation',
                'details_url' => route('patient.appointments.show', $nextApp->id),
                'reschedule_url' => route('patient.appointments.reschedule', $nextApp->id),
            ];
        }

        // 3. Upcoming Appointments (Top 5)
        $upcomingAppointments = Appointment::with(['doctor.user', 'doctor.department', 'department'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', $activeStatuses)
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
                    'department' => $app->department?->name ?? $specialty,
                    'date_formatted' => $appDate->format('M j, Y'),
                    'time_formatted' => $timeFormatted,
                    'type' => 'In-Person',
                    'status' => $app->status,
                    'reason' => $app->reason ?? 'General Consultation',
                    'details_url' => route('patient.appointments.show', $app->id),
                    'reschedule_url' => route('patient.appointments.reschedule', $app->id),
                ];
            });

        // 4. Recent Prescriptions (Top 3)
        $recentPrescriptions = Prescription::with(['doctor.user', 'items'])
            ->where('patient_id', $patient->id)
            ->whereNull('supersedes_id')
            ->latest('created_at')
            ->take(3)
            ->get()
            ->map(function ($rx) {
                $docName = $rx->doctor?->user?->name ? 'Dr. '.$rx->doctor->user->name : 'Physician';
                $firstItem = $rx->items->first();
                $itemCount = $rx->items->count();
                $medSummary = $firstItem ? $firstItem->medication_name : 'Prescription';
                if ($itemCount > 1) {
                    $medSummary .= ' + '.($itemCount - 1).' more';
                }

                return [
                    'id' => $rx->id,
                    'code' => $rx->prescription_code,
                    'doctor_name' => $docName,
                    'medication_summary' => $medSummary,
                    'dosage' => $firstItem ? trim(($firstItem->dosage ?? '').' '.($firstItem->frequency ?? '')) : 'As prescribed',
                    'issued_date' => $rx->issued_at ? Carbon::parse($rx->issued_at)->format('M j, Y') : $rx->created_at->format('M j, Y'),
                    'url' => route('patient.prescriptions.show', $rx->id),
                ];
            });

        // 5. Latest Clinical Vitals & Record
        $latestRecord = MedicalRecord::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->latest('created_at')
            ->first();

        $latestVitals = null;
        if ($latestRecord && is_array($latestRecord->vitals) && ! empty($latestRecord->vitals)) {
            $latestVitals = [
                'bp' => $latestRecord->vitals['bp'] ?? ($latestRecord->vitals['bp_systolic'] ?? null ? "{$latestRecord->vitals['bp_systolic']}/{$latestRecord->vitals['bp_diastolic']}" : '120/80'),
                'pulse' => $latestRecord->vitals['pulse'] ?? $latestRecord->vitals['heart_rate'] ?? '72',
                'weight' => $latestRecord->vitals['weight_kg'] ?? $latestRecord->vitals['weight'] ?? null,
                'temp' => $latestRecord->vitals['temp'] ?? null,
                'recorded_date' => $latestRecord->created_at->format('M j, Y'),
                'diagnosis' => $latestRecord->diagnosis,
                'doctor_name' => $latestRecord->doctor?->user?->name ? 'Dr. '.$latestRecord->doctor->user->name : 'Attending Doctor',
                'record_url' => route('patient.records.show', $latestRecord->id),
            ];
        }

        // 6. Unified Notifications & Activity Feed
        $recentNotifications = collect();

        // Database notifications
        try {
            $dbNotifications = DB::table('notifications')
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', $user->id)
                ->latest('created_at')
                ->take(3)
                ->get();

            foreach ($dbNotifications as $notif) {
                $data = json_decode($notif->data, true) ?? [];
                $msg = $data['message'] ?? 'Notification received.';
                $recentNotifications->push([
                    'id' => 'db-'.$notif->id,
                    'text' => $msg,
                    'time' => Carbon::parse($notif->created_at)->diffForHumans(),
                    'bg_class' => 'bg-blue',
                    'url' => route('patient.notifications.index'),
                    'timestamp' => Carbon::parse($notif->created_at)->timestamp,
                ]);
            }
        } catch (\Throwable $e) {
            // Ignore if notifications table is unavailable
        }

        // Recent completed appointment records
        $recentCompleted = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', $completedStatuses)
            ->latest('updated_at')
            ->take(2)
            ->get();

        foreach ($recentCompleted as $comp) {
            $docName = $comp->doctor?->user?->name ? 'Dr. '.$comp->doctor->user->name : 'your doctor';
            $recentNotifications->push([
                'id' => 'app-comp-'.$comp->id,
                'text' => "Consultation #{$comp->appointment_code} marked completed by {$docName}.",
                'time' => $comp->completed_at ? Carbon::parse($comp->completed_at)->diffForHumans() : ($comp->updated_at ? $comp->updated_at->diffForHumans() : 'Recently'),
                'bg_class' => 'bg-green',
                'url' => route('patient.records.index'),
                'timestamp' => $comp->completed_at ? Carbon::parse($comp->completed_at)->timestamp : ($comp->updated_at ? $comp->updated_at->timestamp : 0),
            ]);
        }

        // Recent prescriptions
        $recentPrescriptionsForFeed = Prescription::with(['doctor.user', 'items'])
            ->where('patient_id', $patient->id)
            ->latest('created_at')
            ->take(2)
            ->get();

        foreach ($recentPrescriptionsForFeed as $rx) {
            $firstItem = $rx->items->first();
            $medName = $firstItem?->medication_name ?? "Rx #{$rx->prescription_code}";
            $recentNotifications->push([
                'id' => 'rx-'.$rx->id,
                'text' => "New prescription issued: {$medName}.",
                'time' => $rx->created_at ? $rx->created_at->diffForHumans() : 'Recently',
                'bg_class' => 'bg-amber',
                'url' => route('patient.prescriptions.show', $rx->id),
                'timestamp' => $rx->created_at ? $rx->created_at->timestamp : 0,
            ]);
        }

        if ($recentNotifications->isEmpty()) {
            $recentNotifications->push([
                'id' => 'def-1',
                'text' => 'Welcome to MediFlow Patient Portal! Book your first consultation online.',
                'time' => 'Just now',
                'bg_class' => 'bg-green',
                'url' => route('appointments.book.select-slot'),
                'timestamp' => now()->timestamp,
            ]);
        }

        $sortedNotifications = $recentNotifications
            ->sortByDesc('timestamp')
            ->values()
            ->take(5);

        // 7. Dynamic Greeting & Patient Identity Info
        $hour = (int) now()->format('H');
        $greetingPrefix = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default => 'Good evening',
        };
        $firstName = explode(' ', trim($user->name))[0] ?? 'there';
        $greeting = "{$greetingPrefix}, {$firstName}!";

        $patientInfo = [
            'id' => $patient->id,
            'code' => $patient->patient_code ?? 'PAT-'.str_pad((string) $patient->id, 5, '0', STR_PAD_LEFT),
            'name' => $user->name,
            'first_name' => $firstName,
            'email' => $user->email,
            'phone' => $user->phone ?? $patient->emergency_contact_phone ?? null,
            'blood_group' => $patient->blood_group ?? null,
            'gender' => $patient->gender ? ucfirst($patient->gender) : null,
            'allergies' => $patient->allergies ?? 'None reported',
            'age' => $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : null,
        ];

        return Inertia::render('Patient/Dashboard', [
            'greeting' => $greeting,
            'patientInfo' => $patientInfo,
            'stats' => [
                'upcoming_visits' => $upcomingVisitsCount,
                'active_prescriptions' => $activePrescriptionsCount,
                'medical_records' => $medicalRecordsCount,
                'completed_visits' => $completedVisitsCount,
            ],
            'nextAppointment' => $nextAppointmentData,
            'upcomingAppointments' => $upcomingAppointments,
            'recentPrescriptions' => $recentPrescriptions,
            'latestVitals' => $latestVitals,
            'recentNotifications' => $sortedNotifications,
        ]);
    }
}
