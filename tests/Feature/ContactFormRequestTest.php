<?php

use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('contact form submission stores message in database', function () {
    $response = $this->post(route('contact.submit'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '+1 555-0123',
        'subject' => 'Appointment Inquiry',
        'message' => 'I would like to schedule a consultation.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('contact_messages', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '+1 555-0123',
        'subject' => 'Appointment Inquiry',
        'message' => 'I would like to schedule a consultation.',
        'status' => 'unread',
    ]);
});

test('contact form validates required fields', function () {
    $response = $this->post(route('contact.submit'), []);

    $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
});

test('contact form validates email format', function () {
    $response = $this->post(route('contact.submit'), [
        'name' => 'Test',
        'email' => 'not-an-email',
        'subject' => 'Test',
        'message' => 'Test message',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('contact form accepts optional department_id', function () {
    $department = Department::factory()->create(['is_active' => true]);

    $response = $this->post(route('contact.submit'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'department_id' => $department->id,
        'subject' => 'Department Inquiry',
        'message' => 'Asking about cardiology services.',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('contact_messages', [
        'name' => 'Jane Doe',
        'department_id' => $department->id,
    ]);
});

test('contact form rejects invalid department_id', function () {
    $response = $this->post(route('contact.submit'), [
        'name' => 'Test',
        'email' => 'test@example.com',
        'department_id' => 99999,
        'subject' => 'Test',
        'message' => 'Test message',
    ]);

    $response->assertSessionHasErrors(['department_id']);
});
