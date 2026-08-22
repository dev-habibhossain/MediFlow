<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminPatientController extends Controller
{
    /**
     * Display paginated patients directory.
     */
    public function index(Request $request): Response
    {
        $search = $request->query('search');
        $bloodGroup = $request->query('blood_group');

        $query = Patient::with(['user:id,name,email,phone,is_active', 'appointments'])
            ->withCount('appointments');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('blood_group', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($bloodGroup && $bloodGroup !== 'all') {
            $query->where('blood_group', $bloodGroup);
        }

        $patients = $query->latest()->get()->map(function ($patient) {
            $name = $patient->user->name ?? 'Patient';
            $words = explode(' ', trim($name));
            $initials = strtoupper(
                substr($words[0] ?? 'P', 0, 1).
                substr($words[1] ?? '', 0, 1)
            );

            return [
                'id' => $patient->id,
                'code' => 'MDF-'.$patient->id,
                'name' => $name,
                'initials' => $initials,
                'phone' => $patient->user->phone ?? '(555) 000-0000',
                'email' => $patient->user->email ?? '',
                'age' => $patient->date_of_birth ? $patient->date_of_birth->age : 28,
                'gender' => ucfirst($patient->gender ?? 'Male'),
                'blood_group' => $patient->blood_group ?? 'O+',
                'visits_count' => $patient->appointments_count ?? 0,
                'status' => ($patient->user && $patient->user->is_active) ? 'active' : 'inactive',
                'avatar_bg' => 'var(--lime)',
                'avatar_color' => 'var(--lime-text)',
            ];
        });

        return Inertia::render('Admin/Patients/Index', [
            'patients' => $patients,
            'filters' => [
                'search' => $search ?? '',
                'blood_group' => $bloodGroup ?? 'all',
            ],
        ]);
    }

    /**
     * Display patient detail view.
     */
    public function show(int $id): Response
    {
        $patient = Patient::with(['user', 'appointments.doctor.user', 'appointments.doctor.department', 'payments'])->findOrFail($id);

        $name = $patient->user->name ?? 'Patient';
        $words = explode(' ', trim($name));
        $initials = strtoupper(
            substr($words[0] ?? 'P', 0, 1).
            substr($words[1] ?? '', 0, 1)
        );

        $totalSpent = (float) $patient->payments->where('status', 'paid')->sum('amount');

        $appointments = $patient->appointments->map(function ($app) {
            return [
                'id' => $app->appointment_code ?? 'MDF-'.$app->id,
                'date' => $app->appointment_date ? $app->appointment_date->format('M j, Y').' · '.($app->start_time ?? '10:00 AM') : 'TBD',
                'doctor' => 'Dr. '.($app->doctor?->user?->name ?? 'Specialist'),
                'department' => $app->doctor?->department?->name ?? 'General',
                'status' => ucfirst($app->status ?? 'Scheduled'),
            ];
        });

        return Inertia::render('Admin/Patients/Show', [
            'patient' => [
                'id' => $patient->id,
                'user_id' => $patient->user_id,
                'code' => 'MDF-'.$patient->id,
                'name' => $name,
                'initials' => $initials,
                'email' => $patient->user->email ?? '',
                'phone' => $patient->user->phone ?? '(555) 000-0000',
                'dob' => $patient->date_of_birth ? $patient->date_of_birth->format('F j, Y') : 'Unknown',
                'age' => $patient->date_of_birth ? $patient->date_of_birth->age : 28,
                'gender' => ucfirst($patient->gender ?? 'Male'),
                'blood_group' => $patient->blood_group ?? 'O+',
                'allergies' => $patient->allergies ?? 'None reported',
                'registered_at' => $patient->created_at ? $patient->created_at->format('M j, Y') : 'Recently',
                'status' => ($patient->user && $patient->user->is_active) ? 'active' : 'inactive',
                'total_spent' => '$'.number_format($totalSpent, 2),
                'invoices_count' => $patient->payments->count(),
                'appointments' => $appointments,
            ],
        ]);
    }

    /**
     * Deactivate / delete patient account.
     */
    public function destroy(int $id): RedirectResponse
    {
        $patient = Patient::with('user')->findOrFail($id);
        if ($patient->user) {
            $patient->user->update(['is_active' => false]);
        }

        return redirect()->route('admin.patients.index')->with('success', 'Patient account deactivated.');
    }
}
