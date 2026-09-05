<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PatientAppointmentController extends Controller
{
    /**
     * List all appointments for the authenticated patient, categorised by status.
     */
    public function index(Request $request): Response
    {
        $patient = $this->resolvePatient($request);

        $appointments = Appointment::with(['doctor.user', 'doctor.department', 'department', 'review'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->get()
            ->map(function (Appointment $app) {
                $docUser = $app->doctor?->user;
                $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Physician';
                $docAvatar = $docUser?->avatar_url ?? 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&q=80&w=150';
                $specialty = $app->doctor?->specialization ?? $app->department?->name ?? 'General Medicine';
                $appDate = Carbon::parse($app->appointment_date);
                $timeFormatted = $app->start_time ? Carbon::parse($app->start_time)->format('g:i A') : 'TBD';
                $endFormatted = $app->end_time ? Carbon::parse($app->end_time)->format('g:i A') : null;

                /** @var string $category */
                $category = match (true) {
                    in_array($app->status, ['Cancelled']) => 'cancelled',
                    in_array($app->status, ['Completed']) => 'past',
                    default => 'upcoming',
                };

                /** @var bool $canReview */
                $canReview = $app->status === 'Completed' && $app->review === null;

                return [
                    'id' => $app->id,
                    'appointment_code' => $app->appointment_code,
                    'doctorName' => $docName,
                    'doctorTitle' => $specialty,
                    'department' => $app->department?->name ?? $specialty,
                    'avatar' => $docAvatar,
                    'dateTime' => $appDate->format('l, M j, Y').' at '.$timeFormatted,
                    'rawDate' => $app->appointment_date->format('Y-m-d'),
                    'mode' => 'In-Person',
                    'location' => 'In-Person Visit',
                    'status' => $app->status,
                    'category' => $category,
                    'canReview' => $canReview,
                    'timeFormatted' => $timeFormatted,
                    'endFormatted' => $endFormatted,
                ];
            });

        return Inertia::render('Patient/Appointments/Index', [
            'appointments' => $appointments,
        ]);
    }

    /**
     * Show a single appointment's full details.
     */
    public function show(Request $request, int $id): Response
    {
        $patient = $this->resolvePatient($request);

        /** @var Appointment $appointment */
        $appointment = Appointment::with([
            'doctor.user',
            'doctor.department',
            'department',
            'payment',
            'medicalRecord.prescriptions.items',
            'review',
        ])->where('patient_id', $patient->id)->findOrFail($id);

        $docUser = $appointment->doctor?->user;
        $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Attending Physician';
        $docAvatar = $docUser?->avatar_url ?? 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=200';
        $specialty = $appointment->doctor?->specialization ?? $appointment->department?->name ?? 'General Medicine';
        $appDate = Carbon::parse($appointment->appointment_date);
        $timeFormatted = $appointment->start_time ? Carbon::parse($appointment->start_time)->format('g:i A') : 'TBD';
        $endFormatted = $appointment->end_time ? Carbon::parse($appointment->end_time)->format('g:i A') : null;

        $payment = $appointment->payment;
        $paymentData = null;
        if ($payment) {
            $paymentData = [
                'status' => $payment->status,
                'amount' => '$'.number_format((float) $payment->amount, 2),
                'paid_at' => $payment->paid_at?->format('M j, Y'),
            ];
        }

        $user = $request->user();

        return Inertia::render('Patient/Appointments/Show', [
            'appointment' => [
                'id' => $appointment->id,
                'appointment_code' => $appointment->appointment_code,
                'status' => $appointment->status,
                'reason' => $appointment->reason,
                'date_formatted' => $appDate->format('l, M j, Y'),
                'time_formatted' => $timeFormatted,
                'end_formatted' => $endFormatted,
                'doctor_name' => $docName,
                'doctor_avatar' => $docAvatar,
                'specialty' => $specialty,
                'department' => $appointment->department?->name ?? $specialty,
                'doctor_experience' => $appointment->doctor?->experience_years
                    ? $appointment->doctor->experience_years.' Yrs Exp'
                    : null,
                'consultation_fee' => $appointment->consultation_fee_snapshot
                    ? '$'.number_format((float) $appointment->consultation_fee_snapshot, 2)
                    : 'N/A',
                'can_reschedule' => in_array($appointment->status, ['Confirmed', 'Pending', 'Scheduled']),
                'can_cancel' => in_array($appointment->status, ['Confirmed', 'Pending', 'Scheduled']),
                'can_review' => $appointment->status === 'Completed' && $appointment->review === null,
                'payment' => $paymentData,
                'patient_name' => $user->name,
                'patient_email' => $user->email,
            ],
        ]);
    }

    /**
     * Render the reschedule page with existing appointment context.
     */
    public function reschedule(Request $request, int $id): Response
    {
        $patient = $this->resolvePatient($request);

        /** @var Appointment $appointment */
        $appointment = Appointment::with(['doctor.user', 'department'])
            ->where('patient_id', $patient->id)
            ->findOrFail($id);

        $docUser = $appointment->doctor?->user;
        $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Attending Physician';

        return Inertia::render('Patient/Appointments/Reschedule', [
            'appointment' => [
                'id' => $appointment->id,
                'appointment_code' => $appointment->appointment_code,
                'doctor_name' => $docName,
                'department' => $appointment->department?->name ?? 'General Medicine',
                'current_date' => $appointment->appointment_date->format('l, M j, Y'),
                'current_time' => $appointment->start_time
                    ? Carbon::parse($appointment->start_time)->format('g:i A')
                    : 'TBD',
                'status' => $appointment->status,
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
