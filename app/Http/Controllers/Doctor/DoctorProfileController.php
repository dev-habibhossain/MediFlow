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
            return $user->doctor;
        }

        $doctor = Doctor::with(['user', 'department'])->first();
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

        return Inertia::render('Doctor/Settings/Profile', [
            'doctorProfile' => [
                'name' => $user?->name ?? 'Dr. Sarah Jenkins',
                'title' => $doctor->qualifications ?? 'MD, FACC — Senior Cardiologist',
                'department' => $doctor->department?->name ?? 'Cardiology',
                'department_id' => $doctor->department_id,
                'licenseNumber' => $doctor->license_number ?? 'MD-7890123',
                'experienceYears' => (string) ($doctor->years_of_experience ?? 12),
                'consultationFee' => number_format((float) ($doctor->consultation_fee ?? 120.00), 2, '.', ''),
                'education' => 'Harvard Medical School (Class of 2012), Residency at Massachusetts General Hospital.',
                'specialties' => $doctor->specialization ?? 'Hypertension, Preventive Cardiology, Lipid Disorders, Electrocardiography',
                'bio' => $doctor->bio ?? 'Board-certified cardiologist specializing in preventive cardiovascular care and non-invasive diagnostic hypertension management with over 12 years of clinical excellence.',
                'avatarUrl' => $user?->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=300',
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
            'experienceYears' => 'nullable|numeric',
            'consultationFee' => 'nullable|numeric',
            'specialties' => 'nullable|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:5120',
        ]);

        if ($user) {
            $user->name = $validated['name'];
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('profile-photos', 'public');
                $user->profile_photo_path = $path;
            }
            $user->save();
        }

        $dept = Department::where('name', $validated['department'])->first();

        $doctor->update([
            'qualifications' => $validated['title'],
            'department_id' => $dept?->id ?? $doctor->department_id,
            'license_number' => $validated['licenseNumber'],
            'years_of_experience' => (int) ($validated['experienceYears'] ?? 12),
            'consultation_fee' => (float) ($validated['consultationFee'] ?? 120.00),
            'specialization' => $validated['specialties'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Doctor profile updated successfully.');
    }
}
