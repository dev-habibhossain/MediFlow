<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Setting;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function __invoke(): Response
    {
        $now = now();

        // 1. Core Metrics
        $totalDoctors = Doctor::count();
        $doctorsThisMonth = Doctor::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $activePatients = Patient::count();
        $patientsThisMonth = Patient::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $appointmentsToday = Appointment::whereDate('appointment_date', today())->count();
        $completedToday = Appointment::whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->count();
        $pendingToday = Appointment::whereDate('appointment_date', today())
            ->whereIn('status', ['confirmed', 'scheduled', 'pending', 'in_progress'])
            ->count();

        $currentMonthRevenue = (float) Payment::where('status', 'paid')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('amount');

        $previousMonthRevenue = (float) Payment::where('status', 'paid')
            ->whereYear('created_at', $now->subMonth()->year)
            ->whereMonth('created_at', $now->subMonth()->month)
            ->sum('amount');

        $revenueGrowth = $previousMonthRevenue > 0
            ? (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100
            : 0;

        // 2. Trailing 6 Months Appointment Volume
        $monthlyVolume = [];
        $maxVolume = 1;

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $count = Appointment::whereYear('appointment_date', $monthDate->year)
                ->whereMonth('appointment_date', $monthDate->month)
                ->count();

            if ($count > $maxVolume) {
                $maxVolume = $count;
            }

            $monthlyVolume[] = [
                'month' => $monthDate->format('M'),
                'count' => $count,
                'is_current' => $i === 0,
            ];
        }

        // Calculate relative height percentage for chart bars
        foreach ($monthlyVolume as &$item) {
            $percentage = $maxVolume > 0 && $item['count'] > 0
                ? (int) min(100, max(20, round(($item['count'] / $maxVolume) * 100)))
                : 15;
            $item['height'] = $percentage.'%';
        }
        unset($item);

        // 3. Activity Feed (ActivityLog or Recent Appointments fallback)
        $activityLogs = ActivityLog::with('causer')
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'description' => $log->description,
                    'causer' => $log->causer ? $log->causer->name : 'System Admin',
                    'time_ago' => $log->created_at ? $log->created_at->diffForHumans() : 'Just now',
                ];
            });

        if ($activityLogs->isEmpty()) {
            $activityLogs = Appointment::with(['patient.user', 'doctor.user'])
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(function ($appointment) {
                    $patientName = $appointment->patient?->user?->name ?? 'Patient';
                    $doctorName = $appointment->doctor?->user?->name ?? 'Doctor';

                    return [
                        'id' => $appointment->id,
                        'action' => 'appointment_booked',
                        'description' => "Appointment {$appointment->appointment_code} booked for {$patientName} with Dr. {$doctorName}.",
                        'causer' => $patientName,
                        'time_ago' => $appointment->created_at ? $appointment->created_at->diffForHumans() : 'Recently',
                    ];
                });
        }

        // 4. System Settings Configuration
        $settings = Setting::whereIn('key', ['hospital_name', 'slot_duration', 'payment_gateway'])
            ->pluck('value', 'key');

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_doctors' => $totalDoctors,
                'doctors_this_month' => $doctorsThisMonth,
                'active_patients' => $activePatients,
                'patients_this_month' => $patientsThisMonth,
                'appointments_today' => $appointmentsToday,
                'completed_today' => $completedToday,
                'pending_today' => $pendingToday,
                'monthly_revenue' => number_format($currentMonthRevenue, 2),
                'revenue_growth' => round($revenueGrowth, 1),
            ],
            'monthlyVolume' => $monthlyVolume,
            'activityLogs' => $activityLogs,
            'systemConfig' => [
                'hospital_name' => $settings->get('hospital_name', 'MediFlow Central'),
                'slot_duration' => $settings->get('slot_duration', '30 Minutes'),
                'payment_gateway' => $settings->get('payment_gateway', 'Stripe Active'),
            ],
        ]);
    }
}
