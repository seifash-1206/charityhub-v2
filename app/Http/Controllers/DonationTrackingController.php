<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class DonationTrackingController extends Controller
{
    /**
     * Show the public tracking search page
     */
    public function index()
    {
        return view('donations.track');
    }

    /**
     * Search for a donation by tracking ID
     */
    public function search(Request $request)
    {
        $request->validate([
            'tracking_id' => 'required|string|max:100',
        ]);

        $donation = Donation::with(['user', 'campaign'])
            ->where('tracking_id', strtoupper(trim($request->tracking_id)))
            ->first();

        if (!$donation) {
            return back()->with('error', 'No donation found with tracking ID: ' . $request->tracking_id);
        }

        return view('donations.track', compact('donation'));
    }

    /**
     * Public verification page for a specific tracking ID (linkable/QR target)
     */
    public function verify(string $trackingId)
    {
        $donation = Donation::with(['user', 'campaign'])
            ->where('tracking_id', strtoupper(trim($trackingId)))
            ->first();

        if (!$donation) {
            abort(404, 'Donation not found.');
        }

        return view('donations.verify', compact('donation'));
    }

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
