<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /**
     * Display the dashboard, redirecting users to their role-specific dashboard.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $roleName = match ($user->role) {
                'admin' => 'Admin',
                'doctor' => 'Doctor',
                'patient' => 'Patient',
                default => null,
            };

            if ($roleName && ! $user->hasRole($roleName)) {
                $role = Role::findOrCreate($roleName, 'web');
                $user->assignRole($role);
            }

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isDoctor()) {
                return redirect()->route('doctor.dashboard');
            }
        }

        return redirect()->route('patient.dashboard');
    }
}
