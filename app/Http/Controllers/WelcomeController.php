<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()) {
            return redirect()->route('home');
        }

        return view('welcome');
    }
}
