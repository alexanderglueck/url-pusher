<?php

namespace App\Http\Controllers;

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
        return view('home', [
            'devices' => $request->user()->devices()->withDeviceToken()->get(),
            'urls' => $request->user()->urls()->latest()->limit(15)->with('device')->get()
        ]);
    }
}
