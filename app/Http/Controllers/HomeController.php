<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Faq;
use App\Models\Review;
use App\Models\Setting;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the public homepage with live database statistics and featured records.
     */
    public function __invoke(): Response
    {
        $departments = Department::where('is_active', true)
            ->withCount(['doctors as active_doctors_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get();

        $doctors = Doctor::where('status', 'active')
            ->with(['user:id,name,avatar_path', 'department:id,name,slug'])
            ->take(5)
            ->get();

        $reviews = Review::where('is_visible', true)
            ->with(['patient.user:id,name,avatar_path'])
            ->latest()
            ->take(3)
            ->get();

        $stats = [
            'specialists_count' => Doctor::where('status', 'active')->count(),
            'departments_count' => Department::where('is_active', true)->count(),
            'appointments_count' => Appointment::count(),
            'average_rating' => round((float) Review::where('is_visible', true)->avg('rating') ?: 4.9, 1),
        ];

        $hospitalName = Setting::where('key', 'hospital_name')->value('value') ?? 'MediFlow General Hospital';

        $faqs = Faq::where('is_published', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        return Inertia::render('Home', [
            'departments' => $departments,
            'doctors' => $doctors,
            'reviews' => $reviews,
            'stats' => $stats,
            'hospitalName' => $hospitalName,
            'faqs' => $faqs,
        ]);
    }
}
