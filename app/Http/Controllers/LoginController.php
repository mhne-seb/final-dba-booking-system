<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('Login'); // Matches your Login.blade.php filename [cite: 3143]
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        // 1. Validate the incoming request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Attempt to log the user in
        // Note: Auth::attempt automatically hashes the 'password' input 
        // and compares it to the hashed password in your 'employee' table.
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect to the dashboard upon success
            return redirect()->intended('dashboard');
        }

        // 3. If login fails, redirect back with an error message 
        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}