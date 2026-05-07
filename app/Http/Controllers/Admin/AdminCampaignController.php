<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::with(['user', 'donations']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $campaigns = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'     => Campaign::count(),
            'active'    => Campaign::where('status', 'active')->count(),
            'completed' => Campaign::where('status', 'completed')->count(),
            'expired'   => Campaign::where('status', 'expired')->count(),
        ];

        return view('admin.campaigns.index', compact('campaigns', 'stats'));
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['user', 'donations.user', 'volunteers']);
        return view('admin.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        $users = User::orderBy('name')->get();
        return view('admin.campaigns.edit', compact('campaign', 'users'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'title'          => 'required|string|min:3|max:255',
            'description'    => 'required|min:10',
            'goal_amount'    => 'required|numeric|min:1',
            'current_amount' => 'required|numeric|min:0',
            'deadline'       => 'nullable|date',
            'status'         => 'required|in:draft,active,completed,expired',
            'image'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        } else {
            unset($data['image']);
        }

        $campaign->update($data);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', "Campaign \"{$campaign->title}\" updated successfully.");
    }

    public function destroy(Campaign $campaign)
    {
        $title = $campaign->title;
        $campaign->delete();

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', "Campaign \"{$title}\" deleted.");
    }

    /**
     * Force-update status of a specific campaign
     */
    public function updateStatus(Request $request, Campaign $campaign)
    {
        $request->validate(['status' => 'required|in:draft,active,completed,expired']);
        $campaign->update(['status' => $request->status]);

        return back()->with('success', "Campaign status updated to {$request->status}.");
    }
}
