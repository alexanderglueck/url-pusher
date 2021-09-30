<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()) {
            return redirect()->route('home');
        }

        SEOTools::setTitle(config('app.name'));
        SEOTools::setDescription('URL-Pusher allows you to quickly push URLs from your browser to an Android phone and vice versa');

        return view('welcome');
    }
}
