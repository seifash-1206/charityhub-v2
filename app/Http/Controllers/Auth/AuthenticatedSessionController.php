<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * LOGIN LOGIC (CLEAN + SECURE)
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 🔐 Step 1: normal authentication
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'The email or the password is wrong',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // 🔥 Step 2: check if admin → redirect to admin verification page
        if ($user->role === 'admin') {
            return redirect()->route('admin.verify');
        }

        // ✅ normal user
        return redirect()->intended(route('dashboard'));
    }

    /**
     * LOGOUT
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}