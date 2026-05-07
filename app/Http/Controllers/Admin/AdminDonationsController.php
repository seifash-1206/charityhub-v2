<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Campaign;
use App\Events\DonationPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminDonationsController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with(['user', 'campaign']);

        // Search by donor name, email, or tracking ID
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                                                   ->orWhere('email', 'like', "%{$search}%"))
                  ->orWhere('tracking_id', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter by campaign
        if ($campaignId = $request->get('campaign_id')) {
            $query->where('campaign_id', $campaignId);
        }

        // Filter by date range
        if ($from = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Sort
        $sortBy    = $request->get('sort', 'created_at');
        $sortDir   = $request->get('direction', 'desc');
        $allowedSorts = ['amount', 'created_at', 'status'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'created_at';

        $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');

        $donations = $query->paginate(20)->withQueryString();
        $campaigns = Campaign::orderBy('title')->get();

        $stats = [
            'total_paid'    => Donation::paid()->sum('amount'),
            'total_pending' => Donation::pending()->count(),
            'total_count'   => Donation::count(),
            'paid_count'    => Donation::paid()->count(),
        ];

        return view('admin.donations.index', compact('donations', 'campaigns', 'stats'));
    }

    public function approve($id)
    {
        $donation = Donation::with(['campaign', 'user'])->findOrFail($id);

        if ($donation->status === 'paid') {
            return back()->with('error', 'Donation is already approved.');
        }

        $donation->update(['status' => 'paid']);

        if ($donation->campaign) {
            $donation->campaign->increment('current_amount', $donation->amount);
            $donation->campaign->updateStatus();
        }

        // Fire event → sends email + PDF
        event(new DonationPaid($donation));

        Log::info('Admin approved donation', ['id' => $donation->id, 'amount' => $donation->amount]);

        return back()->with('success', 'Donation approved and confirmation email sent.');
    }

    public function reject($id)
    {
        $donation = Donation::findOrFail($id);

        if ($donation->status === 'failed') {
            return back()->with('error', 'Donation is already rejected.');
        }

        $donation->update(['status' => 'failed']);

        Log::info('Admin rejected donation', ['id' => $donation->id]);

        return back()->with('success', 'Donation rejected successfully.');
    }

    public function show(Donation $donation)
    {
        $donation->load(['user', 'campaign']);
        return view('admin.donations.show', compact('donation'));
    }
}
