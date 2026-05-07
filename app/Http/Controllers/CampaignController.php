<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    /**
     * List all campaigns with filtering
     */
    public function index(Request $request)
    {
        $query = Campaign::with('donations');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Auto-update statuses on list load (lightweight)
        Campaign::where('status', 'active')
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->update(['status' => 'expired']);

        $campaigns = $query->latest()->paginate(12)->withQueryString();

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Show a single campaign (by slug)
     */
    public function show(Campaign $campaign)
    {
        // Live update status before showing
        $campaign->updateStatus();
        $campaign->load(['user', 'donations' => fn($q) => $q->where('status', 'paid')->latest()->take(5)]);

        return view('campaigns.show', compact('campaign'));
    }

    /**
     * Create form (admin only via policy)
     */
    public function create()
    {
        return view('campaigns.create');
    }

    /**
     * Store campaign
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|min:3|max:255',
            'description' => 'required|min:10',
            'goal_amount' => 'required|numeric|min:1',
            'deadline'    => 'nullable|date|after:today',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $data['user_id']        = auth()->id();
        $data['current_amount'] = 0;
        $data['status']         = 'active';
        // slug is auto-generated in model boot

        $campaign = Campaign::create($data);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully! 🚀');
    }

    /**
     * Edit form
     */
    public function edit(Campaign $campaign)
    {
        $this->authorize('update', $campaign);
        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update campaign
     */
    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $data = $request->validate([
            'title'       => 'required|string|min:3|max:255',
            'description' => 'required|min:10',
            'goal_amount' => 'required|numeric|min:1',
            'deadline'    => 'nullable|date',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        } else {
            unset($data['image']);
        }

        $campaign->update($data);
        $campaign->updateStatus();

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully! ✨');
    }

    /**
     * Delete campaign
     */
    public function destroy(Campaign $campaign)
    {
        $this->authorize('delete', $campaign);

        $campaign->delete();

        return redirect()
            ->route('campaigns.index')
            ->with('success', 'Campaign deleted.');
    }
}