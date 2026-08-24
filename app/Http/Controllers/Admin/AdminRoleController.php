<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    /**
     * Display Spatie roles & permissions matrix.
     */
    public function index(): Response
    {
        $roles = Role::with('permissions')->get();

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }
}
