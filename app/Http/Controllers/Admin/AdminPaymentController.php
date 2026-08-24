<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminPaymentController extends Controller
{
    /**
     * Display financial payments registry.
     */
    public function index(Request $request): Response
    {
        $search = $request->query('search');

        $query = Payment::with([
            'patient.user:id,name',
            'appointment.doctor.department:id,name',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('patient.user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        $transactions = $query->latest()->get()->map(function ($payment) {
            $statusText = $payment->status === 'refunded'
                ? 'Refunded'
                : 'Paid ('.ucfirst($payment->payment_method ?? 'Stripe').')';

            $deptName = $payment->appointment?->doctor?->department?->name ?? 'Medical';
            $code = $payment->appointment?->appointment_code ?? ('MDF-'.$payment->appointment_id);

            return [
                'id' => '#INV-'.($payment->transaction_id ?? $payment->id),
                'patient_name' => $payment->patient?->user?->name ?? 'Patient',
                'service_details' => "{$deptName} Consultation (#{$code})",
                'amount' => '$'.number_format((float) $payment->amount, 2),
                'timestamp' => $payment->created_at ? $payment->created_at->format('M j, Y · h:i A') : 'Recently',
                'status' => $statusText,
            ];
        });

        return Inertia::render('Admin/Payments/Index', [
            'transactions' => $transactions,
            'filters' => [
                'search' => $search ?? '',
            ],
        ]);
    }

    /**
     * Issue refund for transaction.
     */
    public function refund(int $id): RedirectResponse
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'refunded']);

        return redirect()->back()->with('success', 'Payment status updated to refunded.');
    }
}
