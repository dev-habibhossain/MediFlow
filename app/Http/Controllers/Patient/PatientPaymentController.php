<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PatientPaymentController extends Controller
{
    /**
     * List all payment records for the authenticated patient.
     */
    public function index(Request $request): Response
    {
        $patient = $this->resolvePatient($request);

        $payments = Payment::with(['appointment.doctor.user', 'appointment.department'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Payment $pay) {
                $appointment = $pay->appointment;
                $docUser = $appointment?->doctor?->user;
                $docName = $docUser?->name ? 'Dr. '.$docUser->name : 'Physician';
                $dept = $appointment?->department?->name
                    ?? $appointment?->doctor?->specialization
                    ?? 'General Medicine';

                $dateCarbon = $pay->paid_at ?? $pay->created_at;
                $dateFormatted = $dateCarbon ? Carbon::parse($dateCarbon)->format('M j, Y') : 'N/A';

                // Map payment status: paid/pending/refunded
                $status = match (strtolower((string) $pay->status)) {
                    'paid', 'completed' => 'paid',
                    'refunded' => 'refunded',
                    default => 'pending',
                };

                $invCode = '#INV-'.str_pad((string) $pay->id, 5, '0', STR_PAD_LEFT);
                $amount = '$'.number_format((float) $pay->amount, 2);

                $searchStr = strtolower(implode(' ', [
                    $invCode,
                    $docName,
                    $dept,
                ]));

                return [
                    'id' => $pay->id,
                    'invCode' => $invCode,
                    'doctorName' => $docName,
                    'desc' => $dept.' Consultation',
                    'date' => $dateFormatted,
                    'method' => 'Online / Card',
                    'status' => $status,
                    'amount' => $amount,
                    'amountRaw' => (float) $pay->amount,
                    'appointment_id' => $pay->appointment_id,
                    'searchStr' => $searchStr,
                ];
            });

        // Compute aggregate stats
        $totalPaid = $payments->where('status', 'paid')->sum('amountRaw');
        $totalPending = $payments->where('status', 'pending')->sum('amountRaw');
        $totalRefunded = $payments->where('status', 'refunded')->sum('amountRaw');
        $paidCount = $payments->where('status', 'paid')->count();
        $pendingCount = $payments->where('status', 'pending')->count();

        return Inertia::render('Patient/Payments/Index', [
            'payments' => $payments->values(),
            'stats' => [
                'total_paid' => '$'.number_format($totalPaid, 2),
                'paid_count' => $paidCount,
                'total_pending' => '$'.number_format($totalPending, 2),
                'pending_count' => $pendingCount,
                'total_refunded' => '$'.number_format($totalRefunded, 2),
                'refunded_count' => $payments->where('status', 'refunded')->count(),
            ],
        ]);
    }

    /**
     * Resolve (or auto-create) the Patient record for the authenticated user.
     */
    private function resolvePatient(Request $request): Patient
    {
        $user = $request->user();
        $patient = Patient::where('user_id', $user->id)->first();

        if (! $patient) {
            $patient = Patient::create([
                'user_id' => $user->id,
                'patient_code' => 'MDF-'.str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            ]);
        }

        return $patient;
    }
}
