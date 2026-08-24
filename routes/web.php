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
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\DoctorMedicalRecordController;
use App\Http\Controllers\Doctor\DoctorPatientController;
use App\Http\Controllers\Doctor\DoctorPerformanceController;
use App\Http\Controllers\Doctor\DoctorPrescriptionController;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\Doctor\DoctorScheduleController;
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

// System & Utility Pages
Route::get('/search', [PageController::class, 'search'])->name('search');
Route::get('/maintenance', [PageController::class, 'maintenance'])->name('maintenance');
Route::get('/404', [PageController::class, 'error404'])->name('error.404');
Route::get('/403', [PageController::class, 'error403'])->name('error.403');
Route::get('/500', [PageController::class, 'error500'])->name('error.500');

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
    Route::prefix('doctor')->name('doctor.')->middleware(['role:Doctor'])->group(function () {
        Route::get('/dashboard', DoctorDashboardController::class)->name('dashboard');

        // Appointments
        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{id}', [DoctorAppointmentController::class, 'show'])->name('appointments.show');
        Route::patch('/appointments/{id}/status', [DoctorAppointmentController::class, 'updateStatus'])->name('appointments.update-status');

        // Patient History
        Route::get('/patients/{id}/history', [DoctorPatientController::class, 'history'])->name('patients.history');

        // Medical Records
        Route::get('/appointments/{id}/records/create', [DoctorMedicalRecordController::class, 'create'])->name('records.create');
        Route::post('/appointments/{id}/records', [DoctorMedicalRecordController::class, 'store'])->name('records.store');
        Route::get('/records/{id}/edit', [DoctorMedicalRecordController::class, 'edit'])->name('records.edit');
        Route::put('/records/{id}', [DoctorMedicalRecordController::class, 'update'])->name('records.update');

        // Prescriptions
        Route::get('/appointments/{id}/prescriptions/create', [DoctorPrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/appointments/{id}/prescriptions', [DoctorPrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::put('/prescriptions/{id}', [DoctorPrescriptionController::class, 'update'])->name('prescriptions.update');
        Route::delete('/prescriptions/{id}', [DoctorPrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
        Route::get('/prescriptions/{id}/supersede', [DoctorPrescriptionController::class, 'supersede'])->name('prescriptions.supersede');
        Route::post('/prescriptions/{id}/supersede', [DoctorPrescriptionController::class, 'storeSupersede'])->name('prescriptions.store-supersede');

        // Schedule & Availability
        Route::get('/schedule', [DoctorScheduleController::class, 'index'])->name('schedule.index');
        Route::post('/schedule', [DoctorScheduleController::class, 'update'])->name('schedule.update');
        Route::get('/schedule/exceptions', [DoctorScheduleController::class, 'exceptions'])->name('schedule.exceptions');
        Route::post('/schedule/exceptions', [DoctorScheduleController::class, 'storeException'])->name('schedule.exceptions.store');
        Route::delete('/schedule/exceptions/{id}', [DoctorScheduleController::class, 'destroyException'])->name('schedule.exceptions.destroy');

        // Performance & Profile
        Route::get('/performance', [DoctorPerformanceController::class, 'index'])->name('performance.index');
        Route::get('/settings/profile', [DoctorProfileController::class, 'edit'])->name('settings.profile');
        Route::post('/settings/profile', [DoctorProfileController::class, 'update'])->name('settings.profile.update');
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
