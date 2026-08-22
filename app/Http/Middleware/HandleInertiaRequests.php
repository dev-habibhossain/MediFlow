<?php

namespace App\Http\Middleware;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'notifications' => fn () => $this->getAdminNotifications($request),
        ];
    }

    /**
     * Get dynamic operational notifications for Admin users.
     */
    protected function getAdminNotifications(Request $request): array
    {
        $user = $request->user();
        if (! $user || ($user->role !== 'Admin' && ! $user->hasRole('Admin'))) {
            return [];
        }

        $list = [];

        // 1. Doctors on Leave or status updates
        try {
            $leaveDoctors = Doctor::with('user')->where('status', 'leave')->latest()->take(2)->get();
            foreach ($leaveDoctors as $doc) {
                $list[] = [
                    'id' => 'doc-leave-'.$doc->id,
                    'title' => 'Doctor On Leave Alert',
                    'message' => 'Dr. '.($doc->user->name ?? 'Physician').' is currently marked on leave',
                    'url' => route('admin.doctors.edit', $doc->id),
                    'type' => 'doctor',
                    'bg_class' => 'bg-amber',
                    'time' => 'Schedule review',
                ];
            }
        } catch (\Throwable $e) {
            // Ignore missing table gracefully
        }

        // 2. Recent appointments
        try {
            $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])->latest()->take(3)->get();
            foreach ($recentAppointments as $app) {
                $list[] = [
                    'id' => 'app-'.$app->id,
                    'title' => 'Appointment #'.($app->appointment_code ?? $app->id),
                    'message' => ($app->patient?->user?->name ?? 'Patient').' booked visit ('.ucfirst($app->status ?? 'scheduled').')',
                    'url' => route('admin.appointments.show', $app->id),
                    'type' => 'appointment',
                    'bg_class' => 'bg-blue',
                    'time' => $app->created_at ? $app->created_at->diffForHumans() : 'Recently',
                ];
            }
        } catch (\Throwable $e) {
            // Ignore missing table gracefully
        }

        // 3. Recent patient reviews
        try {
            $recentReviews = Review::latest()->take(2)->get();
            foreach ($recentReviews as $rev) {
                $list[] = [
                    'id' => 'rev-'.$rev->id,
                    'title' => 'New Patient Review',
                    'message' => 'Patient submitted a '.$rev->rating.'★ rating for doctor',
                    'url' => route('admin.reviews.index'),
                    'type' => 'review',
                    'bg_class' => 'bg-green',
                    'time' => $rev->created_at ? $rev->created_at->diffForHumans() : 'Recently',
                ];
            }
        } catch (\Throwable $e) {
            // Ignore missing table gracefully
        }

        return array_slice($list, 0, 6);
    }
}
