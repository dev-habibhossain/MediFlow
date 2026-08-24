<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminReviewController extends Controller
{
    /**
     * Display patient reviews for moderation.
     */
    public function index(): Response
    {
        $reviews = Review::with(['patient.user:id,name', 'doctor.user:id,name', 'doctor.department:id,name'])
            ->latest()
            ->get()
            ->map(function ($rev) {
                $pName = $rev->patient?->user?->name ?? 'Patient';
                $dName = 'Dr. '.($rev->doctor?->user?->name ?? 'Doctor');
                $dDept = $rev->doctor?->department?->name ?? 'General';

                return [
                    'id' => $rev->id,
                    'patient_name' => $pName,
                    'doctor_info' => "{$dName} ({$dDept})",
                    'rating' => number_format((float) ($rev->rating ?? 5.0), 1),
                    'comment' => $rev->comment ?? 'No comment provided.',
                    'visible' => (bool) ($rev->is_visible ?? true),
                ];
            });

        return Inertia::render('Admin/Reviews/Index', [
            'reviews' => $reviews,
        ]);
    }

    /**
     * Toggle visibility of review.
     */
    public function toggleVisibility(int $id): RedirectResponse
    {
        $review = Review::findOrFail($id);
        $review->update(['is_visible' => ! $review->is_visible]);

        return redirect()->back()->with('success', 'Review visibility updated.');
    }

    /**
     * Delete review.
     */
    public function destroy(int $id): RedirectResponse
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Review deleted.');
    }
}
