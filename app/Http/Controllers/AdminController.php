<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showVerify()
    {
        return view('admin.verify');
    }

    public function verify(Request $request)
    {
        if ($request->admin_key !== 'Admin1012') {

            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'The email or the password is wrong',
            ]);
        }

        // mark admin verified
        session(['admin_verified' => true]);

        return redirect()->route('dashboard');
    }
}