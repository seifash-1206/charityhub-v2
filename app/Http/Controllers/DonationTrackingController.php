<?php

namespace App\Http\Controllers;

use App\Models\Donation;

class DonationTrackingController extends Controller
{
    /**
     * My donations page (authenticated user)
     */
    public function myDonations()
    {
        $donations = Donation::with('campaign')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('donations.my', compact('donations'));
    }
}
