<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard, redirecting admins to the admin dashboard.
     */
    public function __invoke(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $patient = $user->patient;

        $appointments = [];
        $unpaidPayments = [];

        if ($patient) {
            $appointments = Appointment::with([
                'doctor.user:id,name,email,avatar_path',
                'department:id,name,slug',
                'payment',
            ])
                ->where('patient_id', $patient->id)
                ->orderBy('appointment_date', 'desc')
                ->get();

            $unpaidPayments = Payment::with('appointment.doctor.user')
                ->where('patient_id', $patient->id)
                ->where('status', 'pending')
                ->get();
        }

        return Inertia::render('Dashboard', [
            'appointments' => $appointments,
            'unpaidPayments' => $unpaidPayments,
            'patient' => $patient,
        ]);
    }
}
