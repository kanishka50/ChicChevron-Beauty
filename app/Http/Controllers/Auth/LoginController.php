<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Get the intended URL, but filter out API/AJAX routes
        $intended = session()->pull('url.intended', '/');

        // If intended URL is an API route or cart count, redirect to home instead
        if (str_contains($intended, '/count') || str_contains($intended, '/api/')) {
            $intended = '/';
        }

        return redirect($intended);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
