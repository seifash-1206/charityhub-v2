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
     * 🟢 Show login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * 🔐 LOGIN LOGIC (FINAL + STABLE VERSION)
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // ❌ Step 1: Check credentials
        if (!Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            return back()->withErrors([
                'email' => 'The email or the password is wrong',
            ])->onlyInput('email');
        }

        // 🔄 Step 2: Regenerate session (security)
        $request->session()->regenerate();

        $user = Auth::user();

        // 🔥 Step 3: RESET admin verification EVERY login (CRITICAL FIX)
        session()->forget('admin_verified');

        // 👑 Step 4: If admin → go to verification page
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.verify');
        }

        // 👤 Step 5: Normal user → dashboard
        return redirect()->intended(route('dashboard'));
    }

    /**
     * 🔓 LOGOUT
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // 🔒 destroy session safely
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}