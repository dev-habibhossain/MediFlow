<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    /**
     * Display the About Us page with hospital metrics, departments, and doctors.
     */
    public function __invoke(): Response
    {
        $stats = [
            'doctors_count' => Doctor::where('status', 'active')->count(),
            'departments_count' => Department::where('is_active', true)->count(),
            'patients_count' => Patient::count(),
            'appointments_count' => Appointment::count(),
        ];

        $featuredDoctors = Doctor::with(['user:id,name,avatar_path', 'department:id,name,slug'])
            ->where('status', 'active')
            ->take(4)
            ->get();

        return Inertia::render('About', [
            'stats' => $stats,
            'featuredDoctors' => $featuredDoctors,
        ]);
    }
}
