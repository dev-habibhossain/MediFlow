<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    /**
     * Display the Contact Us page.
     */
    public function index(): Response
    {
        $contactDetails = [
            'address' => Setting::where('key', 'hospital_address')->value('value') ?? '120 Harbor Ave, Suite 300, Riverside',
            'phone' => Setting::where('key', 'support_phone')->value('value') ?? '(555) 340-2199',
            'email' => Setting::where('key', 'support_email')->value('value') ?? 'support@mediflow.com',
            'hours' => 'Mon–Fri: 8:00 AM – 8:00 PM · Sat: 9:00 AM – 4:00 PM',
        ];

        return Inertia::render('Contact', [
            'contactDetails' => $contactDetails,
        ]);
    }

    /**
     * Process a contact form submission inquiry.
     */
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you! Your message has been received and our desk team will contact you shortly.');
    }
}
