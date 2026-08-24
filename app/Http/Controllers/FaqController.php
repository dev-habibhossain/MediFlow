<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Inertia\Inertia;
use Inertia\Response;

class FaqController extends Controller
{
    /**
     * Display the Frequently Asked Questions page with database-driven content.
     */
    public function __invoke(): Response
    {
        $faqs = Faq::where('is_published', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return Inertia::render('Faq', [
            'faqs' => $faqs,
        ]);
    }
}
