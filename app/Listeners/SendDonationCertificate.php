<?php

namespace App\Listeners;

use App\Events\DonationPaid;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDonationCertificate
{
    /**
     * Handle the event.
     */
    public function handle(DonationPaid $event): void
    {
        $donation = $event->donation;

        // 🔒 SAFETY CHECKS
        if (!$donation) {
            Log::error('❌ Donation missing in event');
            return;
        }

        if (!$donation->user || !$donation->user->email) {
            Log::error('❌ User or email missing', [
                'donation_id' => $donation->id
            ]);
            return;
        }

        try {
            // 📄 Generate PDF
            $pdf = Pdf::loadView('pdf.certificate', [
                'donation' => $donation
            ]);

            // 🎯 Prepare data
            $userName = $donation->user->name;
            $amount = number_format($donation->amount, 2);
            $campaign = $donation->campaign->title ?? 'Unknown Campaign';
            $trackingId = $donation->tracking_id ?? 'N/A';
            $transactionId = $donation->transaction_id ?? 'N/A';

            // 📧 SEND EMAIL (MAX VERSION 🔥)
            Mail::send([], [], function ($message) use (
                $donation,
                $pdf,
                $userName,
                $amount,
                $campaign,
                $trackingId,
                $transactionId
            ) {

                $message->to($donation->user->email)
                    ->subject('🎉 Donation Approved — CharityHub')
                    ->html("
                    <div style='font-family:Arial,sans-serif;background:#f4f6f9;padding:40px'>
                        <div style='max-width:600px;margin:auto;background:white;border-radius:12px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.1)'>

                            <!-- HEADER -->
                            <div style='background:linear-gradient(135deg,#2563eb,#16a34a);padding:25px;text-align:center;color:white'>
                                <h1 style='margin:0'>CharityHub 💙</h1>
                                <p style='margin:5px 0 0'>Donation Confirmation</p>
                            </div>

                            <!-- BODY -->
                            <div style='padding:30px'>
                                <h2 style='margin-top:0'>Hey {$userName} 👋</h2>

                                <p>Your donation has been 
                                <strong style='color:#16a34a;'>successfully approved</strong>.</p>

                                <div style='margin:25px 0;padding:20px;background:#f1f5f9;border-radius:10px'>
                                    <p style='margin:5px 0'>💰 <strong>\${$amount}</strong></p>
                                    <p style='margin:5px 0'>🎯 {$campaign}</p>
                                </div>

                                <hr>

                                <h3>🔍 Transaction Details</h3>

                                <p><strong>Tracking ID:</strong><br>{$trackingId}</p>

                                <p><strong>Transaction ID:</strong><br>
                                <span style='font-size:12px;color:#555'>{$transactionId}</span></p>

                                <p style='margin-top:20px'>
                                    Keep this email for your records.
                                </p>

                            </div>

                            <!-- FOOTER -->
                            <div style='background:#f1f5f9;padding:20px;text-align:center;font-size:13px;color:#555'>
                                CharityHub • Secure Donations Platform
                            </div>

                        </div>
                    </div>
                    ")
                    ->attachData($pdf->output(), 'CharityHub_Certificate.pdf');
            });

            // ✅ SUCCESS LOG
            Log::info('✅ Certificate sent successfully', [
                'donation_id' => $donation->id,
                'tracking_id' => $trackingId,
                'transaction_id' => $transactionId,
                'email' => $donation->user->email
            ]);

        } catch (\Throwable $e) {

            // ❌ ERROR LOG
            Log::error('❌ Certificate sending failed', [
                'error' => $e->getMessage(),
                'donation_id' => $donation->id
            ]);
        }
    }
}