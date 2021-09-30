<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        SEOTools::setTitle('Dashboard  - ' . config('app.name'));
        SEOTools::setDescription('URL-Pusher makes sharing your favorite content easier.');
        SEOTools::opengraph()->setUrl(route('home'));
        SEOTools::setCanonical(route('home'));

        return view('home', [
            'devices' => $request->user()->devices()->withDeviceToken()->get(),
            'urls' => $request->user()->urls()->latest()->limit(15)->with('device')->get()
        ]);
    }
}
