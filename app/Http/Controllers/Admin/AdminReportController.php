<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Inertia\Inertia;
use Inertia\Response;

class AdminReportController extends Controller
{
    /**
     * Display reports summary dashboard.
     */
    public function index(): Response
    {
        $totalRevenue = (float) Payment::where('status', 'paid')->sum('amount');
        $totalAppointments = Appointment::count();
        $completedAppointments = Appointment::where('status', 'completed')->count();

        return Inertia::render('Admin/Reports/Index', [
            'reports' => [
                'total_revenue' => '$'.number_format($totalRevenue, 2),
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
            ],
        ]);
    }

    /**
     * Display detailed revenue breakdown.
     */
    public function revenue(): Response
    {
        $totalRevenue = (float) Payment::where('status', 'paid')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return Inertia::render('Admin/Reports/Revenue', [
            'metrics' => [
                'total_revenue' => '$'.number_format($totalRevenue, 2),
                'year_to_date' => '$'.number_format($monthlyRevenue, 2),
            ],
        ]);
    }
}
