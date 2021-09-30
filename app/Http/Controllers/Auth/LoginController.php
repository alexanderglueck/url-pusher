<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(): View
    {
        SEOTools::setTitle('Login - ' . config('app.name'));
        SEOTools::setDescription('Sign in to access your recent pushes.');
        SEOTools::opengraph()->setUrl(route('login'));
        SEOTools::setCanonical(route('login'));

        return view('auth.login');
    }
}
