<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login successful',
                'user' => auth()->user()->only('name', 'email'),
            ]);
        }

        return response()->json([
            'message' => 'Login failed',
            'errors' => [
                'email' => ['These credentials do not match our records.'],
            ],
        ], 422);
    }
}
