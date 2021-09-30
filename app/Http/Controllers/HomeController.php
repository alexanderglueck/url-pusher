<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        SEOTools::setTitle('Dashboard  - ' . config('app.name'));
        SEOTools::setDescription('URL-Pusher makes sharing your favorite content easier.');

        return view('home', [
            'devices' => $request->user()->devices()->withDeviceToken()->get(),
            'urls' => $request->user()->urls()->latest()->limit(15)->with('device')->get()
        ]);
    }
}
