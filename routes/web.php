<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Booking\BookingConfirmController;
use App\Http\Controllers\Booking\BookingSelectSlotController;
use App\Http\Controllers\Booking\BookingSuccessController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
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
Route::get('/appointments/book/{doctor:license_number?}/success', BookingSuccessController::class)->name('appointments.book.success');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/faq', FaqController::class)->name('faq');

Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
