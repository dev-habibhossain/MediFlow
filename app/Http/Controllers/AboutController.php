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
     * Display the About Us page with hospital metrics and mission highlights.
     */
    public function __invoke(): Response
    {
        $stats = [
            'doctors_count' => Doctor::where('status', 'active')->count(),
            'departments_count' => Department::where('is_active', true)->count(),
            'patients_count' => Patient::count(),
            'appointments_count' => Appointment::count(),
        ];

        return Inertia::render('About', [
            'stats' => $stats,
        ]);
    }
}
