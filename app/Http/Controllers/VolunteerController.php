<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\Campaign;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $query = Volunteer::with('campaign');

        if ($search = $request->get('search')) {
            // User portal search intentionally avoids email matching to prevent contact-data enumeration.
            $query->where('name', 'like', "%{$search}%");
        }

        if ($campaignId = $request->get('campaign_id')) {
            $query->where('campaign_id', $campaignId);
        }

        $volunteers = $query->latest()->paginate(12)->withQueryString();
        $campaigns  = Campaign::active()->orderBy('title')->get();

        $stats = [
            'total'  => Volunteer::count(),
            'active' => Volunteer::where('status', 'active')->count(),
        ];

        return view('volunteers.index', compact('volunteers', 'campaigns', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Volunteer::class);

        $campaigns = Campaign::active()->latest()->get();
        return view('volunteers.create', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Volunteer::class);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:volunteers,email'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'campaign_id'  => ['required', 'exists:campaigns,id'],
            'notes'        => ['nullable', 'string'],
            'availability' => ['required', 'in:weekdays,weekends,both,flexible'],
            'skills'       => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status']  = 'active';

        Volunteer::create($validated);

        return redirect()
            ->route('volunteers.index')
            ->with('success', 'Thank you for registering as a volunteer! 🎉');
    }

    public function edit(Volunteer $volunteer)
    {
        $this->authorize('update', $volunteer);

        $campaigns = Campaign::active()->latest()->get();
        return view('volunteers.edit', compact('volunteer', 'campaigns'));
    }

    public function update(Request $request, Volunteer $volunteer)
    {
        $this->authorize('update', $volunteer);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'campaign_id'  => ['required', 'exists:campaigns,id'],
            'notes'        => ['nullable', 'string'],
            'availability' => ['required', 'in:weekdays,weekends,both,flexible'],
            'skills'       => ['nullable', 'string', 'max:1000'],
        ]);

        $volunteer->update($validated);

        return redirect()
            ->route('volunteers.index')
            ->with('success', 'Volunteer profile updated successfully.');
    }

    public function destroy(Volunteer $volunteer)
    {
        $this->authorize('delete', $volunteer);

        $volunteer->delete();

        return redirect()
            ->route('volunteers.index')
            ->with('success', 'Volunteer registration removed.');
    }
}