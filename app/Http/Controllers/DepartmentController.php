<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of all active medical departments.
     */
    public function index(): Response
    {
        $departments = Department::where('is_active', true)
            ->withCount(['doctors as active_doctors_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get();

        return Inertia::render('Departments/Index', [
            'departments' => $departments,
        ]);
    }

    /**
     * Display the specified medical department details with assigned specialists.
     */
    public function show(Department $department): Response
    {
        if (! $department->is_active) {
            abort(404);
        }

        $department->load(['doctors' => function ($query) {
            $query->where('status', 'active')->with(['user:id,name,email,avatar_path', 'department:id,name,slug']);
        }]);

        return Inertia::render('Departments/Show', [
            'department' => $department,
        ]);
    }
}
