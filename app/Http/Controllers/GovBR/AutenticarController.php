<?php

namespace App\Http\Controllers\GovBR;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AutenticarController extends Controller
{
    public function getAuth(): RedirectResponse
    {
        return Socialite::driver('govbr')->redirect();
    }

    public function setAuth()
    {
        dd('teste');
    }
}
