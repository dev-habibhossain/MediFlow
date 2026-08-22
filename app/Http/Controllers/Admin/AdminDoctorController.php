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
use Illuminate\Support\Facades\Hash;
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
            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name ?? 'Doctor',
                'title' => ($doctor->qualifications ?? 'MD').' · '.($doctor->years_of_experience ?? 0).' Yrs Exp',
                'license_code' => $doctor->license_number,
                'department' => $doctor->department->name ?? 'General',
                'department_id' => $doctor->department_id,
                'fee' => '$'.number_format((float) $doctor->consultation_fee, 2),
                'rating' => round((float) ($doctor->reviews_avg_rating ?? 5.0), 1),
                'reviews_count' => $doctor->reviews_count ?? 0,
                'status' => $doctor->status,
                'avatar' => $doctor->user->avatar_url ?? 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=150',
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
     * Show creation form.
     */
    public function create(): Response
    {
        $departments = Department::where('is_active', true)->select('id', 'name')->get();

        return Inertia::render('Admin/Doctors/Create', [
            'departments' => $departments,
        ]);
    }

    /**
     * Store newly created doctor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'gender' => 'required|string|in:male,female,other',
            'license_number' => 'required|string|unique:doctors,license_number',
            'department_id' => 'required|exists:departments,id',
            'qualifications' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:60',
            'bio' => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'room_number' => 'nullable|string|max:100',
            'password' => 'required|string|min:8',
            'status' => 'required|string|in:active,leave,inactive',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar') && $request->file('avatar') instanceof UploadedFile) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $validated['title_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'avatar_path' => $avatarPath,
            'role' => 'Doctor',
            'is_active' => $validated['status'] === 'active',
        ]);

        $user->assignRole('Doctor');

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

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor onboarded successfully.');
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
                'department_id' => $doctor->department_id,
                'qualifications' => $doctor->qualifications,
                'experience_years' => $doctor->years_of_experience,
                'bio' => $doctor->bio,
                'consultation_fee' => $doctor->consultation_fee,
                'status' => $doctor->status,
                'avatar' => $doctor->user->avatar_url,
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
     * Doctor schedule view.
     */
    public function schedule(int $id): Response
    {
        $doctor = Doctor::with(['user', 'department', 'schedules', 'scheduleExceptions'])->findOrFail($id);

        return Inertia::render('Admin/Doctors/Schedule', [
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'license_number' => $doctor->license_number,
                'department' => $doctor->department->name ?? 'General',
                'schedules' => $doctor->schedules,
                'exceptions' => $doctor->scheduleExceptions,
            ],
        ]);
    }

    /**
     * Update doctor schedule.
     */
    public function updateSchedule(Request $request, int $id): RedirectResponse
    {
        $doctor = Doctor::findOrFail($id);

        $validated = $request->validate([
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required|integer|min:0|max:6',
            'schedules.*.start_time' => 'required|string',
            'schedules.*.end_time' => 'required|string',
            'schedules.*.slot_duration_minutes' => 'nullable|integer|min:5|max:120',
            'schedules.*.is_active' => 'boolean',
        ]);

        if (isset($validated['schedules'])) {
            foreach ($validated['schedules'] as $sch) {
                DoctorSchedule::updateOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $sch['day_of_week'],
                    ],
                    [
                        'start_time' => $sch['start_time'],
                        'end_time' => $sch['end_time'],
                        'slot_duration_minutes' => $sch['slot_duration_minutes'] ?? 30,
                        'is_active' => $sch['is_active'] ?? true,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Doctor schedule updated.');
    }

    /**
     * Remove / deactivate doctor.
     */
    public function destroy(int $id): RedirectResponse
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update(['status' => 'inactive']);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor status set to inactive.');
    }
}
