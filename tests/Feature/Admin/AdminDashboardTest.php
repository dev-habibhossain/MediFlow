<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('admin dashboard page renders successfully for admin user', function () {
    Role::create(['name' => 'Admin']);
    $adminUser = User::factory()->create();
    $adminUser->assignRole('Admin');

    $this->actingAs($adminUser)
        ->get(route('admin.dashboard'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Dashboard')
            ->has('stats')
            ->has('monthlyVolume')
            ->has('activityLogs')
            ->has('systemConfig')
        );
});

test('non admin user cannot access admin dashboard', function () {
    Role::create(['name' => 'Patient']);
    $patientUser = User::factory()->create();
    $patientUser->assignRole('Patient');

    $this->actingAs($patientUser)
        ->get(route('admin.dashboard'))
        ->assertStatus(403);
});

test('unauthenticated user cannot access admin dashboard', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});
