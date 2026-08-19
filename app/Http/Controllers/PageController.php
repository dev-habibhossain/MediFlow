<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Display the Privacy Policy page.
     */
    public function privacy(): Response
    {
        return Inertia::render('PrivacyPolicy');
    }

    /**
     * Display the Terms of Service page.
     */
    public function terms(): Response
    {
        return Inertia::render('TermsOfService');
    }
}
