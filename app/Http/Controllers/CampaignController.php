<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Show all campaigns
     */
    public function index()
    {
        $campaigns = Campaign::latest()->get();

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * 🔥 SHOW SINGLE CAMPAIGN (NO AUTH BLOCK)
     */
    public function show(Campaign $campaign)
    {
        return view('campaigns.show', compact('campaign'));
    }

    /**
     * Show create form
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
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|min:10',
            'goal_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date|after:today',
            'image' => 'nullable|image|max:2048',
        ]);

        // Upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $data['user_id'] = auth()->id();
        $data['current_amount'] = 0;

        Campaign::create($data);

        return redirect()
            ->route('campaigns.index')
            ->with('success', 'Campaign created successfully 🚀');
    }

    /**
     * Show edit form
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
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|min:10',
            'goal_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
        ]);

        // Replace image if new uploaded
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update($data);

        return redirect()
            ->route('campaigns.index')
            ->with('success', 'Campaign updated successfully ✨');
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
            ->with('success', 'Campaign deleted ❌');
    }

    /**
     * 🚫 REMOVE AUTO POLICY (CAUSE OF 403)
     */
    // public function __construct()
    // {
    //     $this->authorizeResource(Campaign::class, 'campaign');
    // }
}