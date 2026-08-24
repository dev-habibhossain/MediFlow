<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

class AdminDoctorController extends Controller
{
    /**
     * Display listing of doctors.
     */
    public function index(Request $request): Response
    {
        $search = $request->query('search');
        $departmentId = $request->query('department');
        $status = $request->query('status');

        $query = Doctor::with(['user:id,name,email,avatar_path', 'department:id,name,slug'])
            ->withAvg(['reviews' => fn ($q) => $q->where('is_visible', true)], 'rating')
            ->withCount(['reviews' => fn ($q) => $q->where('is_visible', true)]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('specialization', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($departmentId && $departmentId !== 'all') {
            $query->where('department_id', $departmentId);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $doctors = $query->latest()->get()->map(function ($doctor) {
            $name = $doctor->user->name ?? 'Doctor';
            $cleaned = trim(str_replace(['Dr.', 'Dr '], '', $name));
            $words = explode(' ', $cleaned);
            $initials = strtoupper(
                substr($words[0] ?? 'D', 0, 1).
                substr($words[1] ?? '', 0, 1)
            );

            return [
                'id' => $doctor->id,
                'name' => $name,
                'initials' => $initials ?: 'DR',
                'title' => ($doctor->qualifications ?? 'MD').' · '.($doctor->years_of_experience ?? 0).' Yrs Exp',
                'license_code' => $doctor->license_number,
                'department' => $doctor->department->name ?? 'General',
                'department_id' => $doctor->department_id,
                'fee' => '$'.number_format((float) $doctor->consultation_fee, 2),
                'rating' => round((float) ($doctor->reviews_avg_rating ?? 5.0), 1),
                'reviews_count' => $doctor->reviews_count ?? 0,
                'status' => $doctor->status,
                'avatar' => $doctor->user?->avatar_url ?? null,
            ];
        });

        $departments = Department::where('is_active', true)->select('id', 'name', 'slug')->get();

        return Inertia::render('Admin/Doctors/Index', [
            'doctors' => $doctors,
            'departments' => $departments,
            'filters' => [
                'search' => $search ?? '',
                'department' => $departmentId ?? 'all',
                'status' => $status ?? 'all',
            ],
        ]);
    }

    /**
     * Show form for promoting an existing user/patient to Doctor.
     */
    public function create(Request $request): Response
    {
        $departments = Department::where('is_active', true)->select('id', 'name')->get();

        $eligibleUsers = User::doesntHave('doctor')
            ->where(function ($q) {
                $q->where('role', '!=', 'Admin')->orWhereNull('role');
            })
            ->where('id', '!=', $request->user()?->id)
            ->select('id', 'name', 'email', 'phone', 'avatar_path', 'role', 'created_at')
            ->latest()
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'N/A',
                'avatar' => $user->avatar_url,
                'role' => $user->role ?? 'Patient',
                'joined' => $user->created_at ? $user->created_at->format('M j, Y') : 'Recently',
            ]);

        $selectedUserId = $request->query('user_id');

        return Inertia::render('Admin/Doctors/Create', [
            'departments' => $departments,
            'eligibleUsers' => $eligibleUsers,
            'selectedUserId' => $selectedUserId ? (int) $selectedUserId : null,
        ]);
    }

    /**
     * Promote existing user/patient to Doctor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'license_number' => 'required|string|unique:doctors,license_number',
            'department_id' => 'required|exists:departments,id',
            'qualifications' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:60',
            'bio' => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'room_number' => 'nullable|string|max:100',
            'status' => 'required|string|in:active,leave,inactive',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if (Doctor::where('user_id', $user->id)->exists()) {
            return redirect()->back()->withErrors(['user_id' => 'This account is already registered as a doctor.']);
        }

        if ($request->hasFile('avatar') && $request->file('avatar') instanceof UploadedFile) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar_path' => $avatarPath]);
        }

        $user->update([
            'role' => 'Doctor',
            'is_active' => $validated['status'] === 'active',
        ]);

        $user->syncRoles(['Doctor']);

        Doctor::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'specialization' => $validated['qualifications'],
            'qualifications' => $validated['qualifications'],
            'years_of_experience' => $validated['experience_years'],
            'bio' => $validated['bio'] ?? null,
            'consultation_fee' => $validated['consultation_fee'],
            'license_number' => $validated['license_number'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'User promoted to Doctor successfully.');
    }

    /**
     * Show form for editing doctor.
     */
    public function edit(int $id): Response
    {
        $doctor = Doctor::with(['user', 'department'])->findOrFail($id);
        $departments = Department::where('is_active', true)->select('id', 'name')->get();

        return Inertia::render('Admin/Doctors/Edit', [
            'doctor' => [
                'id' => $doctor->id,
                'user_id' => $doctor->user_id,
                'name' => $doctor->user->name,
                'email' => $doctor->user->email,
                'phone' => $doctor->user->phone,
                'license_number' => $doctor->license_number,
                'department' => $doctor->department->name ?? 'General',
                'department_id' => $doctor->department_id,
                'qualifications' => $doctor->qualifications,
                'experience_years' => $doctor->years_of_experience,
                'bio' => $doctor->bio,
                'consultation_fee' => $doctor->consultation_fee,
                'status' => $doctor->status,
                'avatar' => $doctor->user?->avatar_url,
            ],
            'departments' => $departments,
        ]);
    }

    /**
     * Update specified doctor.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $doctor = Doctor::with('user')->findOrFail($id);

        $validated = $request->validate([
            'title_name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$doctor->user_id}",
            'phone' => 'nullable|string|max:30',
            'license_number' => "required|string|unique:doctors,license_number,{$doctor->id}",
            'department_id' => 'required|exists:departments,id',
            'qualifications' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:60',
            'bio' => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'status' => 'required|string|in:active,leave,inactive',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('avatar') && $request->file('avatar') instanceof UploadedFile) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $doctor->user->update(['avatar_path' => $avatarPath]);
        }

        $doctor->user->update([
            'name' => $validated['title_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['status'] === 'active',
        ]);

        $doctor->update([
            'department_id' => $validated['department_id'],
            'specialization' => $validated['qualifications'],
            'qualifications' => $validated['qualifications'],
            'years_of_experience' => $validated['experience_years'],
            'bio' => $validated['bio'] ?? null,
            'consultation_fee' => $validated['consultation_fee'],
            'license_number' => $validated['license_number'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor profile updated successfully.');
    }

    /**
     * Show doctor's schedule override console.
     */
    public function schedule(int $id): Response
    {
        $doctor = Doctor::with('user')->findOrFail($id);

        return Inertia::render('Admin/Doctors/Schedule', [
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->user->name ?? 'Doctor',
            ],
        ]);
    }

    /**
     * Update doctor's weekly recurring schedule.
     */
    public function updateSchedule(Request $request, int $id): RedirectResponse
    {
        $doctor = Doctor::findOrFail($id);

        $validated = $request->validate([
            'schedule' => 'nullable|array',
        ]);

        if (! empty($validated['schedule'])) {
            $dayMap = [
                'mon' => 1, 'tue' => 2, 'wed' => 3, 'thu' => 4,
                'fri' => 5, 'sat' => 6, 'sun' => 7,
            ];

            foreach ($validated['schedule'] as $dayItem) {
                $key = $dayItem['key'] ?? null;
                $dayNum = $dayMap[$key] ?? null;
                if (! $dayNum) {
                    continue;
                }

                DoctorSchedule::updateOrCreate(
                    ['doctor_id' => $doctor->id, 'day_of_week' => $dayNum],
                    [
                        'start_time' => $dayItem['start'] ?? '09:00',
                        'end_time' => $dayItem['end'] ?? '17:00',
                        'slot_duration_minutes' => 30,
                        'is_active' => (bool) ($dayItem['active'] ?? true),
                    ]
                );
            }
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor schedule template updated successfully.');
    }

    /**
     * Remove specified doctor (revert role to Patient or soft delete).
     */
    public function destroy(int $id): RedirectResponse
    {
        $doctor = Doctor::findOrFail($id);
        $user = $doctor->user;

        if ($user) {
            $user->update(['role' => 'Patient']);
            $user->syncRoles(['Patient']);
        }

        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor role removed. User reverted to Patient.');
    }
}
