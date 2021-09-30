<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function showResetForm(Request $request): View
    {
        SEOTools::setTitle('Reset Password - ' . config('app.name'));
        SEOTools::setDescription('Reset your password to regain access to your URL-Pusher account.');
        SEOTools::opengraph()->setUrl(route('password.request'));
        SEOTools::setCanonical(route('password.request'));

        $token = $request->route()->parameter('token');

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
