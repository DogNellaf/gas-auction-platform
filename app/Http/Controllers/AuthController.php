<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function register(): RedirectResponse
    {
        return redirect()->route('register');
    }
}
