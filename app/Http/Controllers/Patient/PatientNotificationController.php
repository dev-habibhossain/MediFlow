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

class PatientNotificationController extends Controller
{
    /**
     * Render the full notifications feed for the authenticated patient.
     */
    public function index(Request $request): Response
    {
        $patient = $this->resolvePatient($request);

        $notifications = collect();

        // --- Confirmed upcoming appointments ---
        $confirmedAppointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['Confirmed', 'Scheduled'])
            ->where('appointment_date', '>=', Carbon::today())
            ->orderByDesc('confirmed_at')
            ->take(5)
            ->get();

        foreach ($confirmedAppointments as $app) {
            $docName = $app->doctor?->user?->name ? 'Dr. '.$app->doctor->user->name : 'Your Doctor';
            $dateStr = Carbon::parse($app->appointment_date)->format('D, M j, Y');
            $timeStr = $app->start_time ? Carbon::parse($app->start_time)->format('g:i A') : '';
            $notifications->push([
                'id' => 'app-confirm-'.$app->id,
                'title' => "Appointment Confirmed with {$docName}",
                'time' => $app->confirmed_at ? $app->confirmed_at->diffForHumans() : 'Recently',
                'description' => "Your consultation #{$app->appointment_code} has been confirmed for {$dateStr}".($timeStr ? " at {$timeStr}" : '').'.',
                'actionUrl' => '/patient/appointments/'.$app->id,
                'actionText' => 'View Appointment Details →',
                'category' => 'appointment',
                'read' => true,
            ]);
        }

        // --- Completed visits ---
        $completedApps = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->where('status', 'Completed')
            ->orderByDesc('completed_at')
            ->take(3)
            ->get();

        foreach ($completedApps as $app) {
            $docName = $app->doctor?->user?->name ? 'Dr. '.$app->doctor->user->name : 'Your Doctor';
            $notifications->push([
                'id' => 'app-complete-'.$app->id,
                'title' => "Consultation Completed with {$docName}",
                'time' => $app->completed_at ? $app->completed_at->diffForHumans() : 'Recently',
                'description' => "Your appointment #{$app->appointment_code} was completed successfully. View your medical record and follow-up notes.",
                'actionUrl' => '/patient/appointments/'.$app->id,
                'actionText' => 'View Visit Summary →',
                'category' => 'appointment',
                'read' => true,
            ]);
        }

        // --- Recent prescriptions ---
        $recentPrescriptions = Prescription::with(['doctor.user', 'items'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        foreach ($recentPrescriptions as $rx) {
            $docName = $rx->doctor?->user?->name ? 'Dr. '.$rx->doctor->user->name : 'Your Doctor';
            $firstMed = $rx->items->first()?->medication_name ?? 'Medication';
            $notifications->push([
                'id' => 'rx-'.$rx->id,
                'title' => "Prescription #{$rx->prescription_code} Issued",
                'time' => $rx->created_at ? $rx->created_at->diffForHumans() : 'Recently',
                'description' => "{$docName} issued a new prescription: {$firstMed}. View your digital Rx for full details.",
                'actionUrl' => '/patient/prescriptions/'.$rx->id,
                'actionText' => 'View Digital Rx →',
                'category' => 'prescription',
                'read' => true,
            ]);
        }

        // --- Recent medical records ---
        $recentRecords = MedicalRecord::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        foreach ($recentRecords as $rec) {
            $docName = $rec->doctor?->user?->name ? 'Dr. '.$rec->doctor->user->name : 'Your Doctor';
            $notifications->push([
                'id' => 'rec-'.$rec->id,
                'title' => 'Medical Record Updated',
                'time' => $rec->created_at ? $rec->created_at->diffForHumans() : 'Recently',
                'description' => "{$docName} added a clinical note to your medical record. Diagnosis: ".($rec->diagnosis ?? 'See record details'),
                'actionUrl' => '/patient/medical-records/'.$rec->id,
                'actionText' => 'View Medical Record →',
                'category' => 'lab',
                'read' => true,
            ]);
        }

        // Fallback welcome if truly no data exists
        if ($notifications->isEmpty()) {
            $notifications->push([
                'id' => 'welcome-1',
                'title' => 'Welcome to MediFlow Patient Portal',
                'time' => 'Just now',
                'description' => 'Your health records, appointments, and prescriptions are all in one place. Book your first consultation to get started.',
                'actionUrl' => '/appointments/book',
                'actionText' => 'Book First Appointment →',
                'category' => 'system',
                'read' => false,
            ]);
        }

        return Inertia::render('Patient/Notifications', [
            'notifications' => $notifications->values(),
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
