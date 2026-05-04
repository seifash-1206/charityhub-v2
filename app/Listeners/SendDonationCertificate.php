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

        // 🔥 SAFETY CHECKS (VERY IMPORTANT)
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

            // 📧 Send Email (logged in your case)
            Mail::send([], [], function ($message) use ($donation, $pdf) {
                $message->to($donation->user->email)
                    ->subject('Your Donation Certificate 🎉')
                    ->html('<h2>Thank you for your donation ❤️</h2>')
                    ->attachData($pdf->output(), 'certificate.pdf');
            });

            // ✅ SUCCESS LOG
            Log::info('✅ Certificate sent successfully', [
                'donation_id' => $donation->id,
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