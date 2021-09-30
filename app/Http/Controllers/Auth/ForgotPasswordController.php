<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm(): View
    {
        SEOTools::setTitle('Reset Password - ' . config('app.name'));
        SEOTools::setDescription('Reset your password to regain access to your URL-Pusher account.');
        SEOTools::opengraph()->setUrl(route('password.request'));
        SEOTools::setCanonical(route('password.request'));

        return view('auth.passwords.email');
    }
}
