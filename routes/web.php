<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Booking\BookingConfirmController;
use App\Http\Controllers\Booking\BookingSelectSlotController;
use App\Http\Controllers\Booking\BookingStoreController;
use App\Http\Controllers\Booking\BookingSuccessController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentPayController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');

Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/{department:slug}', [DepartmentController::class, 'show'])->name('departments.show');

Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{doctor:license_number}', [DoctorController::class, 'show'])->name('doctors.show');

// Doctor Booking Flow Pages
Route::get('/appointments/book/{doctor:license_number?}', BookingSelectSlotController::class)->name('appointments.book.select-slot');
Route::get('/appointments/book/{doctor:license_number?}/confirm', BookingConfirmController::class)->name('appointments.book.confirm');
Route::post('/appointments/book/store', BookingStoreController::class)->name('appointments.book.store')->middleware('auth');
Route::get('/appointments/book/{doctor:license_number?}/success', BookingSuccessController::class)->name('appointments.book.success');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/faq', FaqController::class)->name('faq');

Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/dashboard/payments/{payment}/pay', PaymentPayController::class)->name('dashboard.payments.pay');

    // Admin Console Routes
    Route::prefix('admin')->name('admin.')->middleware(['role:Admin'])->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

        // Doctors Management
        Route::get('/doctors', fn () => inertia('Admin/Doctors/Index'))->name('doctors.index');
        Route::get('/doctors/create', fn () => inertia('Admin/Doctors/Create'))->name('doctors.create');
        Route::get('/doctors/{id}/edit', fn () => inertia('Admin/Doctors/Edit'))->name('doctors.edit');
        Route::get('/doctors/{id}/schedule', fn () => inertia('Admin/Doctors/Schedule'))->name('doctors.schedule');

        // Patients Management
        Route::get('/patients', fn () => inertia('Admin/Patients/Index'))->name('patients.index');
        Route::get('/patients/{id}', fn () => inertia('Admin/Patients/Show'))->name('patients.show');

        // Departments Management
        Route::get('/departments', fn () => inertia('Admin/Departments/Index'))->name('departments.index');
        Route::get('/departments/create', fn () => inertia('Admin/Departments/Create'))->name('departments.create');
        Route::get('/departments/{slug}', fn () => inertia('Admin/Departments/Edit'))->name('departments.edit');

        // Appointments Management
        Route::get('/appointments', fn () => inertia('Admin/Appointments/Index'))->name('appointments.index');
        Route::get('/appointments/{id}', fn () => inertia('Admin/Appointments/Show'))->name('appointments.show');

        // Analytics & Reports
        Route::get('/reports', fn () => inertia('Admin/Reports/Index'))->name('reports.index');
        Route::get('/reports/revenue', fn () => inertia('Admin/Reports/Revenue'))->name('reports.revenue');

        // User & RBAC Management
        Route::get('/users', fn () => inertia('Admin/Users/Index'))->name('users.index');
        Route::get('/users/{id}', fn () => inertia('Admin/Users/Show'))->name('users.show');
        Route::get('/roles', fn () => inertia('Admin/Roles/Index'))->name('roles.index');
        Route::get('/activity-logs', fn () => inertia('Admin/ActivityLogs/Index'))->name('activity-logs.index');

        // Content & Broadcast Moderation
        Route::get('/reviews', fn () => inertia('Admin/Reviews/Index'))->name('reviews.index');
        Route::get('/announcements', fn () => inertia('Admin/Announcements/Index'))->name('announcements.index');

        // Payments & Invoices
        Route::get('/payments', fn () => inertia('Admin/Payments/Index'))->name('payments.index');
        Route::get('/payments/{id}', fn () => inertia('Admin/Payments/Show'))->name('payments.show');

        // Profile Settings (Admin Personal Settings)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // System Settings
        Route::get('/settings', fn () => inertia('Admin/Settings/Index'))->name('settings.index');
        Route::get('/settings/general', fn () => inertia('Admin/Settings/General'))->name('settings.general');
        Route::get('/settings/scheduling', fn () => inertia('Admin/Settings/Scheduling'))->name('settings.scheduling');
        Route::get('/settings/notifications', fn () => inertia('Admin/Settings/Notifications'))->name('settings.notifications');
        Route::get('/settings/holidays', fn () => inertia('Admin/Settings/Holidays'))->name('settings.holidays');
    });

    // Doctor Portal Routes
    Route::prefix('doctor')->name('doctor.')->middleware(['role:Doctor'])->group(function () {
        Route::get('/dashboard', fn () => inertia('Doctor/Dashboard'))->name('dashboard');

        // Appointments
        Route::get('/appointments', fn () => inertia('Doctor/Appointments/Index'))->name('appointments.index');
        Route::get('/appointments/{id}', fn () => inertia('Doctor/Appointments/Show'))->name('appointments.show');

        // Patient History
        Route::get('/patients/{id}/history', fn () => inertia('Doctor/Patients/History'))->name('patients.history');

        // Medical Records
        Route::get('/appointments/{id}/records/create', fn () => inertia('Doctor/Records/Create'))->name('records.create');
        Route::get('/records/{id}/edit', fn () => inertia('Doctor/Records/Edit'))->name('records.edit');

        // Prescriptions
        Route::get('/appointments/{id}/prescriptions/create', fn () => inertia('Doctor/Prescriptions/Create'))->name('prescriptions.create');
        Route::get('/prescriptions/{id}/supersede', fn () => inertia('Doctor/Prescriptions/Supersede'))->name('prescriptions.supersede');

        // Schedule & Availability
        Route::get('/schedule', fn () => inertia('Doctor/Schedule/Index'))->name('schedule.index');
        Route::get('/schedule/exceptions', fn () => inertia('Doctor/Schedule/Exceptions'))->name('schedule.exceptions');

        // Performance & Profile
        Route::get('/performance', fn () => inertia('Doctor/Performance/Index'))->name('performance.index');
        Route::get('/settings/profile', fn () => inertia('Doctor/Settings/Profile'))->name('settings.profile');
    });

    // Patient Portal Routes
    Route::prefix('patient')->name('patient.')->middleware(['role:Patient'])->group(function () {
        Route::get('/dashboard', fn () => inertia('Patient/Dashboard'))->name('dashboard');

        // Appointments
        Route::get('/appointments', fn () => inertia('Patient/Appointments/Index'))->name('appointments.index');
        Route::get('/appointments/{id}', fn () => inertia('Patient/Appointments/Show'))->name('appointments.show');
        Route::get('/appointments/{id}/reschedule', fn () => inertia('Patient/Appointments/Reschedule'))->name('appointments.reschedule');
        Route::get('/appointments/{id}/review', fn () => inertia('Patient/Appointments/Review'))->name('appointments.review');

        // Medical Records
        Route::get('/medical-records', fn () => inertia('Patient/Records/Index'))->name('records.index');
        Route::get('/medical-records/{id}', fn () => inertia('Patient/Records/Show'))->name('records.show');

        // Prescriptions
        Route::get('/prescriptions', fn () => inertia('Patient/Prescriptions/Index'))->name('prescriptions.index');
        Route::get('/prescriptions/{id}', fn () => inertia('Patient/Prescriptions/Show'))->name('prescriptions.show');

        // Notifications
        Route::get('/notifications', fn () => inertia('Patient/Notifications'))->name('notifications.index');

        // Settings
        Route::get('/settings/profile', fn () => inertia('Patient/Settings/Profile'))->name('settings.profile');
        Route::get('/settings/security', fn () => inertia('Patient/Settings/Security'))->name('settings.security');
        Route::get('/settings/notifications', fn () => inertia('Patient/Settings/Notifications'))->name('settings.notifications');

        // Payments
        Route::get('/payments', fn () => inertia('Patient/Payments/Index'))->name('payments.index');
        Route::get('/payments/{id}/checkout', fn () => inertia('Patient/Payments/Checkout'))->name('payments.checkout');
        Route::get('/appointments/{id}/pay', fn () => inertia('Patient/Payments/Checkout'))->name('appointments.pay');
    });
});

require __DIR__.'/settings.php';
