<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use App\Events\DonationPaid;

class AdminDonationController extends Controller
{
    /**
     * 📊 Show all donations (Admin panel)
     */
    public function index()
    {
        $donations = Donation::with(['user', 'campaign'])
            ->latest()
            ->paginate(10); // ✅ FIXED (pagination instead of get)

        return view('admin.donations', compact('donations'));
    }

    /**
     * ✅ Approve donation
     */
    public function approve($id)
    {
        $donation = Donation::with(['campaign', 'user'])->findOrFail($id);

        // ⚠️ prevent double approval
        if ($donation->status === 'paid') {
            return back()->with('error', 'Donation already approved');
        }

        // 🔥 mark as paid
        $donation->update([
            'status' => 'paid'
        ]);

        // 📈 update campaign total ONLY once
        if ($donation->campaign) {
            $donation->campaign->increment(
                'current_amount',
                $donation->amount
            );
        }

        // 🔥 FIRE EVENT (EMAIL + PDF)
        event(new DonationPaid($donation));

        Log::info('✅ Donation approved by admin', [
            'donation_id' => $donation->id,
            'amount' => $donation->amount
        ]);

        return back()->with('success', 'Donation approved successfully');
    }

    /**
     * ❌ Reject donation
     */
    public function reject($id)
    {
        $donation = Donation::findOrFail($id);

        // ⚠️ prevent double reject
        if ($donation->status === 'failed') {
            return back()->with('error', 'Donation already rejected');
        }

        // ❌ mark as failed
        $donation->update([
            'status' => 'failed'
        ]);

        Log::info('❌ Donation rejected by admin', [
            'donation_id' => $donation->id
        ]);

        return back()->with('success', 'Donation rejected successfully');
    }
}