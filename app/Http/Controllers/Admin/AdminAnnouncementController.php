<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminAnnouncementController extends Controller
{
    /**
     * Display announcements list & publish form.
     */
    public function index(): Response
    {
        $announcements = Notification::latest('created_at')
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title ?? 'Hospital Broadcast',
                    'date' => $notif->created_at ? $notif->created_at->format('M j, Y') : 'Recently',
                    'body' => $notif->message ?? $notif->body ?? '',
                ];
            });

        if ($announcements->isEmpty()) {
            $announcements = collect([
                [
                    'id' => 1,
                    'title' => 'Platform Maintenance Notice — August 10',
                    'date' => 'Aug 5, 2026',
                    'body' => 'MediFlow portal services will undergo scheduled maintenance on Sunday from 02:00 AM to 04:00 AM. Booking services may experience brief pauses.',
                ],
            ]);
        }

        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Broadcast announcement to users.
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
            $query->whereHas('roles', fn ($rq) => $rq->where('name', 'Patient'));
        } elseif ($validated['target'] === 'doctors') {
            $query->whereHas('roles', fn ($rq) => $rq->where('name', 'Doctor'));
        }

        $userIds = $query->pluck('id');

        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => $validated['title'],
                'message' => $validated['body'],
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Announcement broadcast successfully.');
    }
}
