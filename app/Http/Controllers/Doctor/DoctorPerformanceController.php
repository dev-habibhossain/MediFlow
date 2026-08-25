<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorPerformanceController extends Controller
{
    protected function getDoctor(): Doctor
    {
        $user = auth()->user();
        if ($user && $user->doctor) {
            return $user->doctor;
        }

        $doctor = Doctor::first();
        if (! $doctor) {
            abort(404, 'Doctor profile not found.');
        }

        return $doctor;
    }

    public function index(Request $request): Response
    {
        $doctor = $this->getDoctor();
        $timeframe = $request->query('timeframe', 'this_month');

        $now = now();
        $startDate = match ($timeframe) {
            'last_3_months' => $now->copy()->subMonths(3)->startOfDay(),
            'this_year' => $now->copy()->startOfYear(),
            'all_time' => null,
            default => $now->copy()->startOfMonth(),
        };

        // Base Queries
        $apptQuery = Appointment::where('doctor_id', $doctor->id);
        $rxQuery = Prescription::where('doctor_id', $doctor->id);
        $reviewQuery = $doctor->reviews()->where('is_visible', true);

        if ($startDate) {
            $apptQuery->where('appointment_date', '>=', $startDate);
            $rxQuery->where('created_at', '>=', $startDate);
            $reviewQuery->where('created_at', '>=', $startDate);
        }

        // 1. Consultations Count
        $consultationsCount = (clone $apptQuery)->where('status', 'completed')->count();

        // 2. Growth Comparison
        $growthText = 'No data yet';
        if ($timeframe === 'this_month') {
            $prevMonthCount = Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'completed')
                ->whereMonth('appointment_date', $now->copy()->subMonth()->month)
                ->whereYear('appointment_date', $now->copy()->subMonth()->year)
                ->count();

            if ($prevMonthCount > 0) {
                $pct = round((($consultationsCount - $prevMonthCount) / $prevMonthCount) * 100);
                $growthText = ($pct >= 0 ? '↑ ' : '↓ ').abs($pct).'% vs last month';
            } elseif ($consultationsCount > 0) {
                $growthText = '↑ New this month';
            }
        } elseif ($consultationsCount > 0) {
            $growthText = 'Active consultations';
        }

        // 3. Average Consultation Duration
        $completedApptsWithTimes = (clone $apptQuery)
            ->where('status', 'completed')
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->get(['start_time', 'end_time']);

        if ($completedApptsWithTimes->isNotEmpty()) {
            $totalMinutes = $completedApptsWithTimes->sum(function ($app) {
                $start = Carbon::parse($app->start_time);
                $end = Carbon::parse($app->end_time);

                return max(0, $end->diffInMinutes($start));
            });
            $avgDurationMinutes = (int) round($totalMinutes / $completedApptsWithTimes->count());
        } else {
            $avgDurationMinutes = 20;
        }

        $avgConsultationTime = "~{$avgDurationMinutes} Mins";
        $timeStatus = $avgDurationMinutes <= 30 ? 'Optimal (Target 20-30m)' : 'Above Target Duration';

        // 4. Completion Rate
        $totalNonCancelled = (clone $apptQuery)->whereNotIn('status', ['cancelled'])->count();
        $completedCount = (clone $apptQuery)->where('status', 'completed')->count();

        $completionPct = $totalNonCancelled > 0 ? round(($completedCount / $totalNonCancelled) * 100, 1) : 0.0;
        $completedPercentage = $completionPct.'%';
        $completionDetail = "{$completedCount} of {$totalNonCancelled} fulfilled";

        // 5. Prescriptions Count
        $prescriptionsIssued = $rxQuery->count();

        // 6. Reviews & Satisfaction Ratings
        $totalReviews = (clone $reviewQuery)->count();
        $avgRatingVal = $totalReviews > 0 ? round((float) (clone $reviewQuery)->avg('rating'), 1) : 0.0;
        $patientSatisfaction = $totalReviews > 0 ? number_format($avgRatingVal, 1).' / 5.0' : 'N/A';

        // Rating Breakdown percentages
        $ratingCounts = (clone $reviewQuery)
            ->selectRaw('rating, count(*) as cnt')
            ->groupBy('rating')
            ->pluck('cnt', 'rating')
            ->toArray();

        $toPercent = fn (int $n): int => $totalReviews > 0 ? (int) round(($n / $totalReviews) * 100) : 0;

        $ratingBreakdown = [
            'fiveStar' => $toPercent((int) ($ratingCounts[5] ?? 0)),
            'fourStar' => $toPercent((int) ($ratingCounts[4] ?? 0)),
            'threeStar' => $toPercent((int) ($ratingCounts[3] ?? 0)),
            'twoStar' => $toPercent((int) ($ratingCounts[2] ?? 0)),
            'oneStar' => $toPercent((int) ($ratingCounts[1] ?? 0)),
        ];

        // 7. Recent Verified Reviews List
        $reviews = (clone $reviewQuery)
            ->with('patient.user')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($rev) => [
                'id' => $rev->id,
                'patient' => $rev->patient?->user?->name ?? 'Verified Patient',
                'rating' => (int) $rev->rating,
                'date' => Carbon::parse($rev->created_at)->format('M d, Y'),
                'comment' => $rev->comment ?? 'Patient consultation feedback on record.',
            ]);

        return Inertia::render('Doctor/Performance/Index', [
            'metrics' => [
                'monthlyConsultations' => $consultationsCount,
                'consultationGrowth' => $growthText,
                'patientSatisfaction' => $patientSatisfaction,
                'satisfactionCount' => $totalReviews,
                'avgConsultationTime' => $avgConsultationTime,
                'timeStatus' => $timeStatus,
                'completedPercentage' => $completedPercentage,
                'completionDetail' => $completionDetail,
                'prescriptionsIssued' => $prescriptionsIssued,
            ],
            'ratingBreakdown' => $ratingBreakdown,
            'reviews' => $reviews,
            'timeframe' => $timeframe,
        ]);
    }
}
