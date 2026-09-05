<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingSelectSlotController extends Controller
{
    /**
     * Display Step 1 of booking: Select date, time slot, and consultation mode.
     */
    public function __invoke(Request $request, ?Doctor $doctor = null): Response
    {
        if (! $doctor) {
            $doctor = Doctor::with([
                'user:id,name,email,avatar_path',
                'department:id,name,slug',
            ])->firstOrFail();
        } else {
            $doctor->load([
                'user:id,name,email,avatar_path',
                'department:id,name,slug',
            ]);
        }

        $doctor->load([
            'schedules' => function ($query) {
                $query->where('is_active', true)->orderBy('day_of_week');
            },
            'scheduleExceptions' => function ($query) {
                $query->where('exception_date', '>=', now()->format('Y-m-d'));
            },
            'appointments' => function ($query) {
                $query->where('appointment_date', '>=', now()->format('Y-m-d'))
                    ->whereNotIn('status', ['cancelled'])
                    ->select('id', 'doctor_id', 'appointment_date', 'start_time', 'end_time', 'status');
            },
        ]);

        $availableDoctors = Doctor::where('status', 'active')
            ->with(['user:id,name', 'department:id,name'])
            ->get();

        return Inertia::render('Booking/SelectSlot', [
            'doctor' => $doctor,
            'availableDoctors' => $availableDoctors,
        ]);
    }
}
