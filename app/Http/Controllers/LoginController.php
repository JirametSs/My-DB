<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm(): View
    {
        return view('logins.form');
    }

    // Logout the user
    public function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();

        // Regenerate CSRF token
        session()->regenerateToken();

        return redirect()->route('login');
    }

    // Authenticate the user
    public function authenticate(Request $request): RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Get credentials from the request
        $credentials = $request->only('email', 'password');

        // Authenticate using the attempt() method
        if (Auth::attempt($credentials)) {
            // Regenerate the session ID
            session()->regenerate();
            
            // Redirect to the intended URL or to the 'products.list' route if not specified
            return redirect()->intended(route('products.list'));
        }

        // Redirect back with error message if authentication fails
        return redirect()->back()->withErrors([
            'credentials' => 'The provided credentials do not match our records.',
        ]);
    }
}