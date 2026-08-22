<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorController extends Controller
{
    /**
     * Display a listing of active doctors with optional department and search filters.
     */
    public function index(Request $request): Response
    {
        $search = $request->query('search');
        $departmentSlug = $request->query('department');

        $query = Doctor::where('status', 'active')
            ->with(['user:id,name,email,avatar_path', 'department:id,name,slug'])
            ->withAvg(['reviews' => function ($q) {
                $q->where('is_visible', true);
            }], 'rating')
            ->withCount(['reviews' => function ($q) {
                $q->where('is_visible', true);
            }]);

        if ($departmentSlug) {
            $query->whereHas('department', function ($q) use ($departmentSlug) {
                $q->where('slug', $departmentSlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('specialization', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $doctors = $query->paginate(12)->withQueryString();
        $departments = Department::where('is_active', true)->select('id', 'name', 'slug')->get();

        return Inertia::render('Doctors/Index', [
            'doctors' => $doctors,
            'departments' => $departments,
            'filters' => [
                'search' => $search ?? '',
                'department' => $departmentSlug ?? '',
            ],
        ]);
    }

    /**
     * Display the specified doctor profile with availability schedules and patient reviews.
     */
    public function show(Doctor $doctor): Response
    {
        $doctor->load([
            'user:id,name,email,phone,avatar_path',
            'department:id,name,slug',
            'secondaryDepartments:id,name,slug',
            'schedules' => function ($query) {
                $query->where('is_active', true)->orderBy('day_of_week');
            },
            'scheduleExceptions' => function ($query) {
                $query->where('exception_date', '>=', now()->format('Y-m-d'));
            },
            'reviews' => function ($query) {
                $query->where('is_visible', true)->with('patient.user:id,name')->latest();
            },
        ]);

        return Inertia::render('Doctors/Show', [
            'doctor' => $doctor,
        ]);
    }
}
