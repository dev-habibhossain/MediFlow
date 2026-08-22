<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Seed the application's FAQ entries from existing content.
     */
    public function run(): void
    {
        $faqs = [
            // Booking & Consultations
            [
                'category' => 'booking',
                'question' => 'Do I need to call to book an appointment?',
                'answer' => "No — every doctor's real-time availability is visible on their profile. Pick a slot and confirm online in under two minutes.",
                'keywords' => 'phone booking online schedule slot',
                'sort_order' => 1,
            ],
            [
                'category' => 'booking',
                'question' => 'Can I reschedule or cancel an appointment?',
                'answer' => 'Yes, up to 2 hours before your scheduled time, directly from your dashboard — no need to call in.',
                'keywords' => 'reschedule cancel change time slot',
                'sort_order' => 2,
            ],
            [
                'category' => 'booking',
                'question' => 'How do same-day consultations work?',
                'answer' => 'Open slots appear on doctor schedules in real time when cancellations occur. You can reserve available same-day slots through the Doctors directory or visit our 24/7 Urgent Unit.',
                'keywords' => 'same day urgent walk in visit consultation',
                'sort_order' => 3,
            ],
            [
                'category' => 'booking',
                'question' => 'Can I book an appointment for a family member?',
                'answer' => 'Yes! Inside your MediFlow patient account, you can add household dependents and select them during appointment checkout.',
                'keywords' => 'family child dependent parent account',
                'sort_order' => 4,
            ],

            // Records & Prescriptions
            [
                'category' => 'records',
                'question' => 'Will I be able to see my prescriptions and records?',
                'answer' => 'Every diagnosis, note and prescription from your visits is stored in your account and downloadable at any time.',
                'keywords' => 'prescriptions notes history diagnosis download pdf portal',
                'sort_order' => 5,
            ],
            [
                'category' => 'records',
                'question' => 'How long does it take to receive lab test results?',
                'answer' => 'Standard blood panels and express diagnostics are processed within 4 to 12 hours. Reports are delivered directly to your portal with an automated notification.',
                'keywords' => 'lab results blood test x-ray report turnaround time portal',
                'sort_order' => 6,
            ],

            // Fees & Insurance
            [
                'category' => 'billing',
                'question' => "How do I know a doctor's consultation fee upfront?",
                'answer' => 'Every doctor profile lists their consultation fee clearly before you book — no surprises at checkout.',
                'keywords' => 'consultation fee pricing transparent cost upfront surprise billing',
                'sort_order' => 7,
            ],
            [
                'category' => 'billing',
                'question' => 'What payment methods do you accept?',
                'answer' => 'We support major credit cards, debit cards, and Stripe online payments securely during or after your appointment.',
                'keywords' => 'payment credit card debit stripe online pay methods',
                'sort_order' => 8,
            ],
            [
                'category' => 'billing',
                'question' => 'How do health insurance claims work?',
                'answer' => 'We support direct billing with major partner insurance networks. Present your card during check-in or enter policy details online to generate pre-formatted claim receipts.',
                'keywords' => 'insurance health coverage claim reimbursement direct billing health card',
                'sort_order' => 9,
            ],

            // Clinic Policies
            [
                'category' => 'policies',
                'question' => 'What should I bring for my first clinic visit?',
                'answer' => 'Please bring a government-issued photo ID, your insurance card, and any relevant past medical records. Arriving 10 minutes prior allows for quick reception check-in.',
                'keywords' => 'first visit ID arrival documents check in front desk',
                'sort_order' => 10,
            ],
            [
                'category' => 'policies',
                'question' => 'Do you offer virtual/telehealth consultations?',
                'answer' => 'Yes. Many of our specialists offer video consultations. Look for the Telehealth badge on the doctor directory to book a virtual appointment.',
                'keywords' => 'telehealth online consultation video call virtual doctor',
                'sort_order' => 11,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['is_published' => true])
            );
        }
    }
}
