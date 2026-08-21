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

        // System Settings
        Route::get('/settings', fn () => inertia('Admin/Settings/Index'))->name('settings.index');
        Route::get('/settings/general', fn () => inertia('Admin/Settings/General'))->name('settings.general');
        Route::get('/settings/scheduling', fn () => inertia('Admin/Settings/Scheduling'))->name('settings.scheduling');
        Route::get('/settings/notifications', fn () => inertia('Admin/Settings/Notifications'))->name('settings.notifications');
        Route::get('/settings/holidays', fn () => inertia('Admin/Settings/Holidays'))->name('settings.holidays');
    });
});

require __DIR__.'/settings.php';
