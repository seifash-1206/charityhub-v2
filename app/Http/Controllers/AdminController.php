<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\Campaign;

class AdminController extends Controller
{
    /**
     * 🔐 Show admin verification page
     */
    public function showVerify()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

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
        $request->validate([
            'admin_key' => 'required|string',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        // ❌ WRONG KEY → logout
        if ($request->admin_key !== 'Admin1012') {

            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Invalid admin key ❌',
            ]);
        }

        // ✅ SUCCESS
        session()->put('admin_verified', true);

        return redirect()->route('dashboard');
    }

    /**
     * 🧠 MAIN DASHBOARD (FINAL FIX 🔥)
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 📊 Stats
        $totalRaised = Donation::where('status', 'paid')->sum('amount');
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();

        // 📈 Recent campaigns (FIXED)
        $recentCampaigns = Campaign::latest()->take(5)->get();

        $user = Auth::user();

        // 💰 User portal donation panel is privacy-scoped.
        // Admins keep an all-donations view; regular users only see their own records.
        $donationsQuery = Donation::with(['user', 'campaign'])->latest();

        if ($user->role !== 'admin') {
            $donationsQuery->where('user_id', $user->id);
        }

        $donationCount = (clone $donationsQuery)->count();
        $donations = $donationsQuery->take(10)->get();

        return view('dashboard', compact(
            'totalRaised',
            'totalCampaigns',
            'activeCampaigns',
            'recentCampaigns',
            'donations',
            'donationCount'
        ));
    }
}
