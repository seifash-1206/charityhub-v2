<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCampaigns = Campaign::count();
        $totalRaised = Campaign::sum('current_amount');
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $recentCampaigns = Campaign::latest()->take(3)->get();

        return view('dashboard', compact(
            'totalCampaigns',
            'totalRaised',
            'activeCampaigns',
            'recentCampaigns'
        ));
    }
}