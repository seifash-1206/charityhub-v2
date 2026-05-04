<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Campaign;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    public function store(Request $request, Campaign $campaign)
    {
        // ✅ Validate
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        // ✅ Auth check
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 🔑 Get Stripe key
        $stripeSecret = config('services.stripe.secret');

        // 🔥 DEBUG (THIS IS WHAT I TOLD YOU TO ADD)
        $stripeSecret = config('services.stripe.secret');

        if (!$stripeSecret) {
            Log::error('Stripe key missing from config');
            return back()->with('error', 'Payment configuration error ❌');
        }

        Stripe::setApiKey($stripeSecret);

        // 🔥 Set API key
        Stripe::setApiKey($stripeSecret);

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',

                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $campaign->title,
                        ],
                        'unit_amount' => (int) ($validated['amount'] * 100),
                    ],
                    'quantity' => 1,
                ]],

                'success_url' => route('donations.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('campaigns.show', $campaign),
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe session error: ' . $e->getMessage());
            return back()->with('error', 'Payment failed ❌');
        }

        Donation::create([
            'user_id' => auth()->id(),
            'campaign_id' => $campaign->id,
            'amount' => $validated['amount'],
            'status' => 'pending',
            'payment_method' => 'stripe',
            'transaction_id' => $session->id,
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('campaigns.index')
                ->with('error', 'Invalid session ❌');
        }

        $donation = Donation::where('transaction_id', $sessionId)->first();

        if (!$donation) {
            return redirect()->route('campaigns.index')
                ->with('error', 'Donation not found ❌');
        }

        return redirect()
            ->route('campaigns.show', $donation->campaign)
            ->with('success', 'Payment processing... 🎉');
    }
}