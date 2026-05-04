<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;

class AdminDonationController extends Controller
{
    /**
     * 📊 Show all donations (Admin panel)
     */
    public function index()
    {
        $donations = Donation::with(['user', 'campaign'])
            ->latest()
            ->get();

        return view('admin.donations', compact('donations'));
    }

    /**
     * ✅ Approve donation
     */
    public function approve($id)
    {
        $donation = Donation::with('campaign')->findOrFail($id);

        if ($donation->status === 'paid') {
            return back()->with('error', 'Already approved');
        }

        // 🔥 mark as paid
        $donation->update([
            'status' => 'paid'
        ]);

        // 📈 update campaign total
        if ($donation->campaign) {
            $donation->campaign->increment(
                'current_amount',
                $donation->amount
            );
        }

        Log::info('Donation approved by admin', [
            'donation_id' => $donation->id
        ]);

        return back()->with('success', 'Donation approved ✅');
    }

    /**
     * ❌ Reject donation
     */
    public function reject($id)
    {
        $donation = Donation::findOrFail($id);

        if ($donation->status === 'failed') {
            return back()->with('error', 'Already rejected');
        }

        $donation->update([
            'status' => 'failed'
        ]);

        Log::info('Donation rejected by admin', [
            'donation_id' => $donation->id
        ]);

        return back()->with('success', 'Donation rejected ❌');
    }
}