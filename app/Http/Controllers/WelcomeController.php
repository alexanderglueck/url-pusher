<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        if ($request->user()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Welcome');
    }

    public function features(): Response
    {
        return Inertia::render('Features');
    }

    public function howItWorks(): Response
    {
        return Inertia::render('HowItWorks');
    }

    public function faq(): Response
    {
        return Inertia::render('Faq');
    }
}
