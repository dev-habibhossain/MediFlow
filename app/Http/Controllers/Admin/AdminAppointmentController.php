<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminAppointmentController extends Controller
{
    /**
     * Display hospital-wide appointments oversight.
     */
    public function index(Request $request): Response
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $department = $request->query('department');

        $query = Appointment::with([
            'patient.user:id,name,email,phone',
            'doctor.user:id,name',
            'doctor.department:id,name,slug',
            'payment',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('appointment_code', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('patient.user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('doctor.user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status && $status !== 'all') {
            $dbStatus = $status === 'progress' ? 'in_progress' : $status;
            $query->where('status', $dbStatus);
        }

        if ($department && $department !== 'all') {
            $query->whereHas('doctor.department', fn ($dq) => $dq->where('name', $department));
        }

        $appointments = $query->latest('appointment_date')->get()->map(function ($app) {
            $rawStatus = $app->status ?? 'confirmed';
            $statusKey = $rawStatus === 'in_progress' ? 'progress' : $rawStatus;

            return [
                'id' => $app->id,
                'code' => $app->appointment_code ?? 'MDF-'.$app->id,
                'patient_name' => $app->patient?->user?->name ?? 'Patient',
                'doctor_name' => 'Dr. '.($app->doctor?->user?->name ?? 'Specialist'),
                'department' => $app->doctor?->department?->name ?? 'General',
                'date_time' => $app->appointment_date ? $app->appointment_date->format('M j, Y').' · '.($app->start_time ?? '10:00 AM') : 'TBD',
                'status' => $statusKey,
            ];
        });

        return Inertia::render('Admin/Appointments/Index', [
            'appointments' => $appointments,
            'filters' => [
                'search' => $search ?? '',
                'status' => $status ?? 'all',
                'department' => $department ?? 'all',
            ],
        ]);
    }

    /**
     * Display detailed appointment view & override panel.
     */
    public function show(int $id): Response
    {
        $appointment = Appointment::with([
            'patient.user',
            'doctor.user',
            'doctor.department',
            'payment',
        ])->findOrFail($id);

        $doctors = Doctor::with('user:id,name')->get()->map(fn ($doc) => [
            'id' => $doc->id,
            'name' => 'Dr. '.($doc->user?->name ?? 'Doctor'),
        ]);

        $paymentStatusText = 'Pending';
        if ($appointment->payment) {
            $paymentStatusText = 'Paid via '.ucfirst($appointment->payment->payment_method ?? 'card').' (Paid)';
        }

        return Inertia::render('Admin/Appointments/Show', [
            'appointment' => [
                'id' => $appointment->id,
                'code' => $appointment->appointment_code ?? 'MDF-'.$appointment->id,
                'scheduled_at' => $appointment->appointment_date ? $appointment->appointment_date->format('F j, Y').' at '.($appointment->start_time ?? '10:00 AM') : 'TBD',
                'status' => ucfirst($appointment->status ?? 'Confirmed').' Booking',
                'patient' => [
                    'name' => $appointment->patient?->user?->name ?? 'Patient',
                    'code' => '#MDF-'.($appointment->patient_id ?? 0),
                    'phone' => $appointment->patient?->user?->phone ?? '(555) 000-0000',
                ],
                'doctor' => [
                    'id' => $appointment->doctor_id,
                    'name' => 'Dr. '.($appointment->doctor?->user?->name ?? 'Doctor'),
                    'department' => $appointment->doctor?->department?->name ?? 'General Dept',
                    'license' => 'Lic #'.($appointment->doctor?->license_number ?? 'MD-00000'),
                ],
                'location' => 'Suite 302, Harbor Ave Clinic',
                'fee' => '$'.number_format((float) ($appointment->fee ?? 120.00), 2).' USD',
                'payment_status' => $paymentStatusText,
            ],
            'doctors' => $doctors,
        ]);
    }

    /**
     * Override / reassign doctor or update appointment status.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'doctor_id' => 'nullable|exists:doctors,id',
            'status' => 'nullable|string|in:confirmed,in_progress,completed,cancelled',
        ]);

        if (! empty($validated['doctor_id'])) {
            $appointment->doctor_id = $validated['doctor_id'];
        }

        if (! empty($validated['status'])) {
            $appointment->status = $validated['status'];
        }

        $appointment->save();

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated successfully.');
    }
}
