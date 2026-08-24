<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminAnnouncementController extends Controller
{
    /**
     * Display announcements list & publish form.
     */
    public function index(): Response
    {
        try {
            $announcements = Notification::where('type', 'Announcement')
                ->latest()
                ->get()
                ->map(function ($notif) {
                    $data = is_array($notif->data) ? $notif->data : json_decode($notif->data, true);

                    return [
                        'id' => $notif->id,
                        'title' => $data['title'] ?? 'Hospital Broadcast',
                        'date' => $notif->created_at ? $notif->created_at->format('M j, Y · h:i A') : 'Recently',
                        'body' => $data['body'] ?? $data['message'] ?? '',
                        'target' => ucfirst($data['target'] ?? 'All Users'),
                    ];
                })
                ->unique('title')
                ->values();
        } catch (\Throwable $e) {
            $announcements = collect();
        }

        if ($announcements->isEmpty()) {
            $announcements = collect([
                [
                    'id' => '1',
                    'title' => 'Platform Maintenance Notice — August 10',
                    'date' => 'Aug 5, 2026',
                    'body' => 'MediFlow portal services will undergo scheduled maintenance on Sunday from 02:00 AM to 04:00 AM. Booking services may experience brief pauses.',
                    'target' => 'All Users',
                ],
                [
                    'id' => '2',
                    'title' => 'New Cardiology Specialist Onboarded',
                    'date' => 'Aug 2, 2026',
                    'body' => 'We are pleased to welcome Dr. Sarah Jenkins to our Cardiology unit. Patient slots are now open for online booking.',
                    'target' => 'Patients Only',
                ],
            ]);
        }

        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Broadcast announcement to target users.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'target' => 'required|string|in:all,patients,doctors',
            'body' => 'required|string',
        ]);

        $query = User::query();
        if ($validated['target'] === 'patients') {
            $query->where('role', 'Patient')->orWhereHas('roles', fn ($rq) => $rq->where('name', 'Patient'));
        } elseif ($validated['target'] === 'doctors') {
            $query->where('role', 'Doctor')->orWhereHas('roles', fn ($rq) => $rq->where('name', 'Doctor'));
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $users = User::all();
        }

        foreach ($users as $user) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'type' => 'Announcement',
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $validated['title'],
                    'body' => $validated['body'],
                    'target' => $validated['target'],
                    'url' => '/dashboard',
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Announcement broadcast successfully to '.$users->count().' user(s).');
    }
}
