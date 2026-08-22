<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;
use App\Models\Department;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
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

        $departments = Department::where('is_active', true)->select('id', 'name', 'slug')->get();

        return Inertia::render('Contact', [
            'contactDetails' => $contactDetails,
            'departments' => $departments,
        ]);
    }

    /**
     * Process a contact form submission inquiry and store in database.
     */
    public function submit(ContactFormRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you! Your message has been saved into our system and our care desk team will respond shortly.');
    }
}
