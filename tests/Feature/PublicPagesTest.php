<?php

use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('homepage loads successfully with inertia props', function () {
    $this->get(route('home'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('departments')
            ->has('doctors')
            ->has('reviews')
            ->has('stats')
        );
});

test('about page renders successfully', function () {
    $this->get(route('about'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('About')
            ->has('stats')
        );
});

test('departments index page lists departments', function () {
    $this->get(route('departments.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Departments/Index')
            ->has('departments')
        );
});

test('single department page renders with details', function () {
    $department = Department::factory()->create(['is_active' => true]);

    $this->get(route('departments.show', $department))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Departments/Show')
            ->where('department.id', $department->id)
        );
});

test('doctors directory page renders with filters and pagination', function () {
    $this->get(route('doctors.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Doctors/Index')
            ->has('doctors')
            ->has('departments')
            ->has('filters')
        );
});

test('doctor profile page renders correctly', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create();
    $doctor = Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'status' => 'active',
    ]);

    $this->get(route('doctors.show', $doctor))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Doctors/Show')
            ->where('doctor.id', $doctor->id)
        );
});

test('contact page loads and accepts contact submission', function () {
    $this->get(route('contact'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page->component('Contact'));

    $response = $this->post(route('contact.submit'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'subject' => 'General Information',
        'message' => 'Hello MediFlow team!',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
});

test('faq and legal pages load successfully', function () {
    $this->get(route('faq'))->assertStatus(200)->assertInertia(fn (Assert $page) => $page->component('Faq'));
    $this->get(route('privacy'))->assertStatus(200)->assertInertia(fn (Assert $page) => $page->component('PrivacyPolicy'));
    $this->get(route('terms'))->assertStatus(200)->assertInertia(fn (Assert $page) => $page->component('TermsOfService'));
});
