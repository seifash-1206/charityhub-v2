<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\Volunteer;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ── Stats ─────────────────────────────────────
        $totalRaised     = Donation::paid()->sum('amount');
        $totalCampaigns  = Campaign::count();
        $activeCampaigns = Campaign::active()->count();
        $totalDonors     = Donation::paid()->distinct('user_id')->count('user_id');
        $totalVolunteers = Volunteer::count();
        $pendingDonations = Donation::pending()->count();
        $totalUsers       = User::count();

        // ── Monthly donation trend (last 6 months) ─────
        $monthlyData = Donation::paid()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Recent campaigns ──────────────────────────
        $recentCampaigns = Campaign::with('donations')
            ->latest()
            ->take(5)
            ->get();

        // ── Recent donations ──────────────────────────
        $recentDonations = Donation::with(['user', 'campaign'])
            ->latest()
            ->take(8)
            ->get();

        // ── Top campaigns by amount ───────────────────
        $topCampaigns = Campaign::with('donations')
            ->withSum(['donations as paid_total' => fn($q) => $q->where('status', 'paid')], 'amount')
            ->orderByDesc('paid_total')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRaised',
            'totalCampaigns',
            'activeCampaigns',
            'totalDonors',
            'totalVolunteers',
            'pendingDonations',
            'totalUsers',
            'monthlyData',
            'recentCampaigns',
            'recentDonations',
            'topCampaigns'
        ));
    }
}
