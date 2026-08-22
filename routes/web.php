<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDepartmentController;
use App\Http\Controllers\Admin\AdminDoctorController;
use App\Http\Controllers\Admin\AdminPatientController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminUserController;
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
        Route::get('/doctors', [AdminDoctorController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/create', [AdminDoctorController::class, 'create'])->name('doctors.create');
        Route::post('/doctors', [AdminDoctorController::class, 'store'])->name('doctors.store');
        Route::get('/doctors/{id}', [AdminDoctorController::class, 'edit'])->name('doctors.show');
        Route::get('/doctors/{id}/edit', [AdminDoctorController::class, 'edit'])->name('doctors.edit');
        Route::put('/doctors/{id}', [AdminDoctorController::class, 'update'])->name('doctors.update');
        Route::get('/doctors/{id}/schedule', [AdminDoctorController::class, 'schedule'])->name('doctors.schedule');
        Route::post('/doctors/{id}/schedule', [AdminDoctorController::class, 'updateSchedule'])->name('doctors.schedule.update');
        Route::put('/doctors/{id}/schedule', [AdminDoctorController::class, 'updateSchedule'])->name('doctors.schedule.put');
        Route::delete('/doctors/{id}', [AdminDoctorController::class, 'destroy'])->name('doctors.destroy');

        // Patients Management
        Route::get('/patients', [AdminPatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/{id}', [AdminPatientController::class, 'show'])->name('patients.show');
        Route::delete('/patients/{id}', [AdminPatientController::class, 'destroy'])->name('patients.destroy');

        // Departments Management
        Route::get('/departments', [AdminDepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [AdminDepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [AdminDepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{slug}', [AdminDepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{slug}', [AdminDepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{slug}', [AdminDepartmentController::class, 'destroy'])->name('departments.destroy');

        // Appointments Management
        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{id}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
        Route::put('/appointments/{id}', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update');

        // Analytics & Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/revenue', [AdminReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('/reports/appointments', [AdminReportController::class, 'appointments'])->name('reports.appointments');
        Route::get('/reports/doctors', [AdminReportController::class, 'doctors'])->name('reports.doctors');

        // User & RBAC Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
        Route::put('/users/{id}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
        Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
        Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');

        // Content & Broadcast Moderation
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::put('/reviews/{id}/toggle', [AdminReviewController::class, 'toggleVisibility'])->name('reviews.toggle');
        Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');

        // Payments & Invoices
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{id}/refund', [AdminPaymentController::class, 'refund'])->name('payments.refund');

        // Profile Settings (Admin Personal Settings)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // System Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/general', [AdminSettingController::class, 'general'])->name('settings.general');
        Route::get('/settings/scheduling', [AdminSettingController::class, 'scheduling'])->name('settings.scheduling');
        Route::get('/settings/notifications', [AdminSettingController::class, 'notifications'])->name('settings.notifications');
        Route::get('/settings/holidays', [AdminSettingController::class, 'holidays'])->name('settings.holidays');
    });

    // Doctor Portal Routes
    Route::prefix('doctor')->name('doctor.')->group(function () {
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
});

require __DIR__.'/settings.php';
