<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;

class DashboardController extends Controller
{
    public function index()
    {
        // 💰 Total donations (ONLY approved/paid)
        $totalRaised = Donation::where('status', 'paid')->sum('amount');

        // 📊 Total campaigns
        $totalCampaigns = Campaign::count();

        // 🔥 Active campaigns (NOT completed + active status)
        $activeCampaigns = Campaign::where('status', 'active')
            ->whereColumn('current_amount', '<', 'goal_amount')
            ->count();

        // 📦 Recent campaigns
        $recentCampaigns = Campaign::latest()->take(3)->get();

        return view('dashboard', compact(
            'totalRaised',
            'totalCampaigns',
            'activeCampaigns',
            'recentCampaigns'
        ));
    }
}