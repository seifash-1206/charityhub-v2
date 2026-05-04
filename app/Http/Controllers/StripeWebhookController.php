<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use App\Events\DonationPaid;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 🔥 Raw payload + signature
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        // 🔐 Get webhook secret
        $endpointSecret = config('services.stripe.webhook_secret') 
            ?? env('STRIPE_WEBHOOK_SECRET');

        if (!$endpointSecret) {
            Log::error('❌ Stripe webhook secret missing');
            return response('Webhook secret not configured', 500);
        }

        // 🔥 Validate event
        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\Exception $e) {
            Log::error('❌ Stripe webhook error: ' . $e->getMessage());
            return response('Webhook error', 400);
        }

        // ===============================
        // 🎯 HANDLE ONLY CHECKOUT SUCCESS
        // ===============================

        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            // 🔥 DEBUG
            Log::info('🔥 Webhook hit', [
                'session_id' => $session->id,
                'payment_status' => $session->payment_status,
                'amount_total' => $session->amount_total,
            ]);

            // 🔍 Find donation by session ID
            $donation = Donation::where('transaction_id', $session->id)->first();

            // 💣 FALLBACK (if mismatch)
            if (!$donation && isset($session->metadata->donation_id)) {
                $donation = Donation::find($session->metadata->donation_id);

                Log::info('⚡ Fallback donation lookup used', [
                    'donation_id' => $session->metadata->donation_id
                ]);
            }

            if (!$donation) {
                Log::warning('❌ Donation not found', [
                    'session_id' => $session->id
                ]);
                return response('Donation not found', 200);
            }

            // 💰 Mark as paid ONLY if Stripe confirms
            if ($session->payment_status === 'paid' && $donation->status !== 'paid') {

                $donation->update(['status' => 'pending']);

                // 📈 Update campaign
                if ($donation->campaign) {
                    $donation->campaign->increment(
                        'current_amount',
                        $donation->amount
                    );
                }

                // 🔥 FIRE EVENT (PDF + EMAIL)
                event(new DonationPaid($donation));

                Log::info('💵 Donation marked as PAID + event fired', [
                    'donation_id' => $donation->id,
                ]);
            }
        }

        return response('Webhook handled', 200);
    }
}