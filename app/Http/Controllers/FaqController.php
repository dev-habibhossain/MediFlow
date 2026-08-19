<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class FaqController extends Controller
{
    /**
     * Display the Frequently Asked Questions page.
     */
    public function __invoke(): Response
    {
        $faqs = [
            [
                'question' => 'Do I need to call to book an appointment?',
                'answer' => "No — every doctor's real-time availability is visible on their profile. Pick a slot and confirm online in under two minutes.",
            ],
            [
                'question' => 'Can I reschedule or cancel an appointment?',
                'answer' => 'Yes, up to 2 hours before your scheduled time, directly from your dashboard — no need to call in.',
            ],
            [
                'question' => 'Will I be able to see my prescriptions and records?',
                'answer' => 'Every diagnosis, note and prescription from your visits is stored in your account and downloadable at any time.',
            ],
            [
                'question' => "How do I know a doctor's consultation fee upfront?",
                'answer' => 'Every doctor profile lists their consultation fee clearly before you book — no surprises at checkout.',
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We support major credit cards, debit cards, and Stripe online payments securely during or after your appointment.',
            ],
        ];

        return Inertia::render('Faq', [
            'faqs' => $faqs,
        ]);
    }
}
