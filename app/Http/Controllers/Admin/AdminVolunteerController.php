<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\Campaign;
use Illuminate\Http\Request;

class AdminVolunteerController extends Controller
{
    public function index(Request $request)
    {
        $query = Volunteer::with('campaign');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($campaignId = $request->get('campaign_id')) {
            $query->where('campaign_id', $campaignId);
        }

        if ($availability = $request->get('availability')) {
            $query->where('availability', $availability);
        }

        $volunteers = $query->latest()->paginate(20)->withQueryString();
        $campaigns  = Campaign::orderBy('title')->get();

        $stats = [
            'total'    => Volunteer::count(),
            'active'   => Volunteer::where('status', 'active')->count(),
            'pending'  => Volunteer::where('status', 'pending')->count(),
            'inactive' => Volunteer::where('status', 'inactive')->count(),
        ];

        return view('admin.volunteers.index', compact('volunteers', 'campaigns', 'stats'));
    }

    public function show(Volunteer $volunteer)
    {
        $volunteer->load('campaign');
        return view('admin.volunteers.show', compact('volunteer'));
    }

    public function edit(Volunteer $volunteer)
    {
        $campaigns = Campaign::orderBy('title')->get();
        return view('admin.volunteers.edit', compact('volunteer', 'campaigns'));
    }

    public function update(Request $request, Volunteer $volunteer)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => "required|email|unique:volunteers,email,{$volunteer->id}",
            'phone'        => 'nullable|string|max:20',
            'campaign_id'  => 'required|exists:campaigns,id',
            'notes'        => 'nullable|string',
            'status'       => 'required|in:active,inactive,pending',
            'availability' => 'required|in:weekdays,weekends,both,flexible',
            'hours_logged' => 'nullable|numeric|min:0',
            'skills'       => 'nullable|string|max:1000',
        ]);

        $volunteer->update($data);

        return redirect()
            ->route('admin.volunteers.index')
            ->with('success', "Volunteer \"{$volunteer->name}\" updated.");
    }

    public function destroy(Volunteer $volunteer)
    {
        $name = $volunteer->name;
        $volunteer->delete();

        return redirect()
            ->route('admin.volunteers.index')
            ->with('success', "Volunteer \"{$name}\" deleted.");
    }

    /**
     * Quick status update
     */
    public function updateStatus(Request $request, Volunteer $volunteer)
    {
        $request->validate(['status' => 'required|in:active,inactive,pending']);
        $volunteer->update(['status' => $request->status]);

        return back()->with('success', "Status updated to {$request->status}.");
    }

    /**
     * Log volunteer hours
     */
    public function logHours(Request $request, Volunteer $volunteer)
    {
        $data = $request->validate(['hours' => 'required|numeric|min:0.5|max:24']);
        $volunteer->increment('hours_logged', $data['hours']);

        return back()->with('success', "{$data['hours']} hours logged for {$volunteer->name}.");
    }
}
