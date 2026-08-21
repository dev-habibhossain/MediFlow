<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingStoreController extends Controller
{
    /**
     * Store a newly created appointment and payment record in storage.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'appointment_date' => ['required', 'string'],
            'start_time' => ['required', 'string'],
            'reason' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:clinic,stripe'],
        ]);

        $user = $request->user();

        $patient = Patient::firstOrCreate(
            ['user_id' => $user->id],
            [
                'patient_code' => 'PAT-'.strtoupper(Str::random(6)),
                'date_of_birth' => '1990-01-01',
                'address' => '120 Harbor Ave',
                'emergency_contact_phone' => $user->phone ?? '555-0199',
            ]
        );

        $doctor = Doctor::with('department')->findOrFail($validated['doctor_id']);
        $appointmentCode = 'MDF-'.rand(10000, 99999);

        $appointmentDate = date('Y-m-d', strtotime($validated['appointment_date'])) ?: now()->format('Y-m-d');
        $startTime = date('H:i:s', strtotime($validated['start_time'])) ?: '10:00:00';
        $endTime = date('H:i:s', strtotime($validated['start_time']) + 1800) ?: '10:30:00';

        $appointment = Appointment::create([
            'appointment_code' => $appointmentCode,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'department_id' => $doctor->department_id,
            'appointment_date' => $appointmentDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'reason' => $validated['reason'] ?? 'General Consultation',
            'status' => 'confirmed',
            'consultation_fee_snapshot' => $doctor->consultation_fee,
            'confirmed_at' => now(),
        ]);

        $isPaid = $validated['payment_method'] === 'stripe';

        Payment::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $patient->id,
            'amount' => $doctor->consultation_fee,
            'currency' => 'USD',
            'status' => $isPaid ? 'paid' : 'pending',
            'stripe_payment_intent_id' => $isPaid ? 'pi_stripe_demo_'.Str::random(12) : null,
            'paid_at' => $isPaid ? now() : null,
        ]);

        return redirect()->route('appointments.book.success', [
            'doctor' => $doctor->license_number,
            'code' => $appointmentCode,
        ]);
    }
}
