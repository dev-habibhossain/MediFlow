<?php

use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('doctor profile settings page can be rendered', function () {
    $department = Department::factory()->create(['name' => 'Cardiology']);
    $user = User::factory()->create(['name' => 'Dr. Jane Doe', 'role' => 'doctor']);
    $user->assignRole('Doctor');

    $doctor = Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'qualifications' => 'MD, FACC',
        'years_of_experience' => 10,
        'consultation_fee' => 150.00,
        'license_number' => 'DOC-998877',
        'specialization' => 'Cardiology, Hypertension',
        'bio' => 'Experienced cardiologist',
    ]);

    $response = $this->actingAs($user)->get(route('doctor.settings.profile'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Doctor/Settings/Profile')
        ->has('profile')
        ->where('profile.name', 'Dr. Jane Doe')
        ->where('profile.licenseNumber', 'DOC-998877')
    );
});

test('doctor profile can be updated', function () {
    Storage::fake('public');

    $department = Department::factory()->create(['name' => 'Neurology']);
    $user = User::factory()->create(['name' => 'Dr. Old Name', 'role' => 'doctor']);
    $user->assignRole('Doctor');

    $doctor = Doctor::factory()->create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'qualifications' => 'MD',
        'license_number' => 'DOC-101010',
    ]);

    $avatar = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->actingAs($user)->post(route('doctor.settings.profile.update'), [
        'name' => 'Dr. Alexander Wright',
        'title' => 'MD, PhD — Consultant Neurologist',
        'department' => 'Neurology',
        'licenseNumber' => 'DOC-101010',
        'experienceYears' => 14,
        'consultationFee' => 200.00,
        'specialties' => 'Neurology, Stroke Management',
        'education' => 'Johns Hopkins School of Medicine',
        'bio' => 'Senior consultant with focus on acute neuro care.',
        'avatar' => $avatar,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Doctor profile updated successfully.');

    $user->refresh();
    $doctor->refresh();

    expect($user->name)->toBe('Dr. Alexander Wright');
    expect($user->avatar_path)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar_path);

    expect($doctor->qualifications)->toBe('MD, PhD — Consultant Neurologist');
    expect($doctor->years_of_experience)->toBe(14);
    expect((float) $doctor->consultation_fee)->toBe(200.00);
    expect($doctor->specialization)->toBe('Neurology, Stroke Management');
    expect($doctor->education)->toBe('Johns Hopkins School of Medicine');
    expect($doctor->bio)->toBe('Senior consultant with focus on acute neuro care.');
});
