<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return redirect()->route('home');
        }

        SEOTools::setTitle(config('app.name'));
        SEOTools::setDescription('URL-Pusher allows you to quickly push URLs from your browser to an Android phone and vice versa');
        SEOTools::opengraph()->setUrl(route('welcome'));
        SEOTools::setCanonical(route('welcome'));

        return view('welcome');
    }
}
