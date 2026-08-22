<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminDepartmentController extends Controller
{
    /**
     * Display listing of hospital departments.
     */
    public function index(): Response
    {
        $departments = Department::withCount('doctors')
            ->latest()
            ->get()
            ->map(function ($dept) {
                return [
                    'id' => $dept->id,
                    'slug' => $dept->slug,
                    'name' => $dept->name,
                    'description' => $dept->description ?? 'Specialty medical services unit.',
                    'doctors_count' => $dept->doctors_count ?? 0,
                    'status' => $dept->is_active ? 'active' : 'inactive',
                ];
            });

        return Inertia::render('Admin/Departments/Index', [
            'departments' => $departments,
        ]);
    }

    /**
     * Show creation form.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Departments/Create');
    }

    /**
     * Store new department.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'status' => 'nullable|string',
        ]);

        $isActive = isset($validated['is_active'])
            ? (bool) $validated['is_active']
            : ($request->input('status', 'active') === 'active');

        Department::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(string $slug): Response
    {
        $department = Department::where('slug', $slug)->firstOrFail();

        $doctors = $department->doctors()->with('user')->get()->map(function ($doc) {
            return [
                'id' => $doc->id,
                'name' => $doc->user?->name ?? 'Dr. Physician',
                'title' => $doc->specialization ?? 'Specialist',
                'license_number' => $doc->license_number ?? ('MD-'.$doc->id),
                'experience_years' => $doc->years_of_experience ?? 5,
                'avatar' => $doc->user?->avatar_url ?? 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=150',
            ];
        });

        return Inertia::render('Admin/Departments/Edit', [
            'department' => [
                'id' => $department->id,
                'slug' => $department->slug,
                'name' => $department->name,
                'code' => strtoupper(substr($department->name, 0, 4)),
                'location' => 'Main Hospital Wing, Level 2',
                'description' => $department->description ?? '',
                'status' => $department->is_active ? 'active' : 'maintenance',
                'is_active' => (bool) $department->is_active,
                'doctors' => $doctors,
            ],
        ]);
    }

    /**
     * Update specified department.
     */
    public function update(Request $request, string $slug): RedirectResponse
    {
        $department = Department::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => "required|string|max:255|unique:departments,name,{$department->id}",
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'status' => 'nullable|string',
        ]);

        $isActive = isset($validated['is_active'])
            ? (bool) $validated['is_active']
            : ($request->input('status', 'active') === 'active');

        $department->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Delete department.
     */
    public function destroy(string $slug): RedirectResponse
    {
        $department = Department::where('slug', $slug)->firstOrFail();
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted.');
    }
}
