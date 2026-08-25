<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorProfileController extends Controller
{
    protected function getDoctor(): Doctor
    {
        $user = auth()->user();
        if ($user && $user->doctor) {
            $doctor = $user->doctor;
            $doctor->loadMissing(['user', 'department', 'reviews']);

            return $doctor;
        }

        $doctor = Doctor::with(['user', 'department', 'reviews'])->first();
        if (! $doctor) {
            abort(404, 'Doctor profile not found.');
        }

        return $doctor;
    }

    public function edit(): Response
    {
        $doctor = $this->getDoctor();
        $user = $doctor->user;

        $departments = Department::orderBy('name')->get(['id', 'name']);

        $reviewsQuery = $doctor->reviews()->where('is_visible', true);
        $totalReviews = $reviewsQuery->count();
        $avgRating = $totalReviews > 0 ? round((float) $reviewsQuery->avg('rating'), 1) : 5.0;

        return Inertia::render('Doctor/Settings/Profile', [
            'profile' => [
                'name' => $user?->name ?? '',
                'title' => $doctor->qualifications ?? '',
                'department' => $doctor->department?->name ?? ($departments->first()?->name ?? 'General'),
                'department_id' => $doctor->department_id,
                'licenseNumber' => $doctor->license_number ?? '',
                'experienceYears' => (string) ($doctor->years_of_experience ?? 0),
                'consultationFee' => number_format((float) ($doctor->consultation_fee ?? 0.00), 2, '.', ''),
                'education' => $doctor->education ?? '',
                'specialties' => $doctor->specialization ?? '',
                'bio' => $doctor->bio ?? '',
                'avatarUrl' => $user?->avatar_url ?? 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=300',
                'averageRating' => $avgRating,
                'reviewCount' => $totalReviews,
            ],
            'departments' => $departments,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $doctor = $this->getDoctor();
        $user = $doctor->user;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'department' => 'required|string',
            'licenseNumber' => 'required|string|max:100',
            'experienceYears' => 'nullable|integer|min:0|max:70',
            'consultationFee' => 'nullable|numeric|min:0',
            'specialties' => 'nullable|string|max:1000',
            'education' => 'nullable|string|max:1000',
            'bio' => 'nullable|string|max:2000',
            'avatar' => 'nullable|image|max:5120',
        ]);

        if ($user) {
            $user->name = $validated['name'];
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('profile-photos', 'public');
                $user->avatar_path = $path;
            }
            $user->save();
        }

        $dept = Department::where('name', $validated['department'])
            ->orWhere('id', $validated['department'])
            ->first();

        $doctor->update([
            'qualifications' => $validated['title'],
            'department_id' => $dept?->id ?? $doctor->department_id,
            'license_number' => $validated['licenseNumber'],
            'years_of_experience' => (int) ($validated['experienceYears'] ?? 0),
            'consultation_fee' => (float) ($validated['consultationFee'] ?? 0.00),
            'specialization' => $validated['specialties'] ?? '',
            'education' => $validated['education'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Doctor profile updated successfully.');
    }
}
