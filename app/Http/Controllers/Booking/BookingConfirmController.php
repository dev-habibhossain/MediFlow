<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingConfirmController extends Controller
{
    /**
     * Display Step 2 of booking: Review appointment & patient information.
     */
    public function __invoke(Request $request, ?Doctor $doctor = null): Response
    {
        if (! $doctor) {
            $doctor = Doctor::with([
                'user:id,name,email,phone,avatar_path',
                'department:id,name,slug',
            ])->firstOrFail();
        } else {
            $doctor->load([
                'user:id,name,email,phone,avatar_path',
                'department:id,name,slug',
            ]);
        }

        return Inertia::render('Booking/Confirm', [
            'doctor' => $doctor,
        ]);
    }
}
