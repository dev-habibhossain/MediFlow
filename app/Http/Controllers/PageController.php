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

    /**
     * Display the Global Search Results page.
     */
    public function search(): Response
    {
        return Inertia::render('Search', [
            'q' => request('q', ''),
        ]);
    }

    /**
     * Display the System Maintenance page.
     */
    public function maintenance(): Response
    {
        return Inertia::render('Maintenance');
    }

    /**
     * Display 404 Not Found error page.
     */
    public function error404(): Response
    {
        return Inertia::render('Error', ['status' => 404]);
    }

    /**
     * Display 403 Forbidden error page.
     */
    public function error403(): Response
    {
        return Inertia::render('Error', ['status' => 403]);
    }

    /**
     * Display 500 Server Error page.
     */
    public function error500(): Response
    {
        return Inertia::render('Error', ['status' => 500]);
    }
}
