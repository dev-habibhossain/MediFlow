<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentPayController extends Controller
{
    /**
     * Mark an unpaid due payment as paid (Stripe Sandbox).
     */
    public function __invoke(Request $request, Payment $payment): RedirectResponse
    {
        $user = $request->user();

        if (! $user->patient || $payment->patient_id !== $user->patient->id) {
            abort(403, 'Unauthorized action.');
        }

        $payment->update([
            'status' => 'paid',
            'stripe_payment_intent_id' => 'pi_stripe_demo_'.Str::random(12),
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Payment of $'.$payment->amount.' completed successfully via Stripe!');
    }
}
