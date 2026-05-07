<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Volunteer;
use App\Models\User;

class AdminReportController extends Controller
{
    public function index()
    {
        // ── Donation stats ─────────────────────────────
        $totalRaised     = Donation::paid()->sum('amount');
        $totalDonations  = Donation::count();
        $paidDonations   = Donation::paid()->count();
        $avgDonation     = $paidDonations > 0 ? $totalRaised / $paidDonations : 0;

        // ── Monthly breakdown (12 months) ─────────────
        $monthlyDonations = Donation::paid()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Top donors ────────────────────────────────
        $topDonors = Donation::paid()
            ->with('user')
            ->selectRaw('user_id, SUM(amount) as total_donated, COUNT(*) as donation_count')
            ->groupBy('user_id')
            ->orderByDesc('total_donated')
            ->take(10)
            ->get();

        // ── Top campaigns ─────────────────────────────
        $topCampaigns = Campaign::withSum(
            ['donations as raised' => fn($q) => $q->where('status', 'paid')],
            'amount'
        )
        ->orderByDesc('raised')
        ->take(10)
        ->get();

        // ── Campaign status breakdown ──────────────────
        $campaignStats = [
            'active'    => Campaign::where('status', 'active')->count(),
            'completed' => Campaign::where('status', 'completed')->count(),
            'expired'   => Campaign::where('status', 'expired')->count(),
            'draft'     => Campaign::where('status', 'draft')->count(),
        ];

        // ── Volunteer stats ───────────────────────────
        $totalVolunteers   = Volunteer::count();
        $activeVolunteers  = Volunteer::where('status', 'active')->count();
        $totalHoursLogged  = Volunteer::sum('hours_logged');

        return view('admin.reports.index', compact(
            'totalRaised',
            'totalDonations',
            'paidDonations',
            'avgDonation',
            'monthlyDonations',
            'topDonors',
            'topCampaigns',
            'campaignStats',
            'totalVolunteers',
            'activeVolunteers',
            'totalHoursLogged'
        ));
    }
}
