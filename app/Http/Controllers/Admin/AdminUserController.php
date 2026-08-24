<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    /**
     * Display users directory.
     */
    public function index(Request $request): Response
    {
        $search = $request->query('search');
        $roleFilter = $request->query('role');

        $query = User::with('roles');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter && $roleFilter !== 'all') {
            $query->whereHas('roles', fn ($rq) => $rq->where('name', ucfirst($roleFilter)));
        }

        $users = $query->latest()->get()->map(function ($user) {
            $roleName = $user->roles->first()?->name ?? 'Patient';
            $roleKey = strtolower($roleName);

            $name = $user->name;
            $words = explode(' ', trim($name));
            $initials = strtoupper(
                substr($words[0] ?? 'U', 0, 1).
                substr($words[1] ?? '', 0, 1)
            );

            return [
                'id' => $user->id,
                'name' => $user->name,
                'subtext' => "{$roleName} Account",
                'role' => $roleKey,
                'role_label' => $roleName,
                'email' => $user->email,
                'status' => $user->is_active ? 'Active' : 'Inactive',
                'initials' => $initials,
            ];
        });

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search ?? '',
                'role' => $roleFilter ?? 'all',
            ],
        ]);
    }

    /**
     * Display specific user account details.
     */
    public function show(int $id): Response
    {
        $user = User::with('roles')->findOrFail($id);

        $roleName = $user->roles->first()?->name ?? 'Patient';

        return Inertia::render('Admin/Users/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'N/A',
                'role' => strtolower($roleName),
                'role_label' => $roleName,
                'status' => $user->is_active ? 'Active' : 'Inactive',
                'created_at' => $user->created_at ? $user->created_at->format('M j, Y') : 'N/A',
            ],
        ]);
    }

    /**
     * Update user role.
     */
    public function updateRole(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => 'required|string|in:Admin,Doctor,Patient',
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->back()->with('success', 'User role updated.');
    }
}
