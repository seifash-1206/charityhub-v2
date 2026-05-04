<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;

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
        // 🎯 HANDLE CHECKOUT SUCCESS
        // ===============================

        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            Log::info('🔥 Webhook hit', [
                'session_id' => $session->id,
                'payment_status' => $session->payment_status,
            ]);

            // 🔍 Find donation
            $donation = Donation::where('transaction_id', $session->id)->first();

            // 💣 fallback using metadata
            if (!$donation && isset($session->metadata->donation_id)) {
                $donation = Donation::find($session->metadata->donation_id);
            }

            if (!$donation) {
                Log::warning('❌ Donation not found', [
                    'session_id' => $session->id
                ]);
                return response('Donation not found', 200);
            }

            // ✅ Only confirm payment → set to pending
            if ($session->payment_status === 'paid' && $donation->status !== 'pending') {

                $donation->update([
                    'status' => 'pending'
                ]);

                Log::info('💰 Donation payment confirmed → pending approval', [
                    'donation_id' => $donation->id
                ]);
            }
        }

        return response('Webhook handled', 200);
    }
}