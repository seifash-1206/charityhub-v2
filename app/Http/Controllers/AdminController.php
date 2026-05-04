<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * 🔐 Show admin verification page
     */
    public function showVerify()
    {
        // 🚫 Not logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 🚫 Not admin
        if ($user->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        return view('admin.verify');
    }

    /**
     * 🔥 Handle admin key verification
     */
    public function verify(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'admin_key' => 'required|string',
        ]);

        // 🚫 Not logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 🚫 Not admin
        if ($user->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        // 💣 WRONG KEY → LOGOUT
        if ($request->admin_key !== 'Admin1012') {

            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'The email or the password is wrong',
            ]);
        }

        // ✅ SUCCESS → SET SESSION (THIS IS THE IMPORTANT FIX)
        session()->put('admin_verified', true);

        return redirect()->route('dashboard');
    }
}