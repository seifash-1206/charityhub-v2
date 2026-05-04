<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\AdminDonationController;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔁 Redirect root → dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});


// 🔐 AUTH PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {

    // 📊 Dashboard (🔥 FIXED — uses AdminController)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🎯 Campaigns
    Route::resource('campaigns', CampaignController::class);

    // 💰 Donate
    Route::post('/campaigns/{campaign}/donate', [DonationController::class, 'store'])
        ->name('donations.store');

    // 🧾 Receipt (PDF download)
    Route::get('/donations/{id}/receipt', function ($id) {
        $donation = Donation::with(['user','campaign'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.receipt', [
            'donation' => $donation
        ]);

        return $pdf->download('receipt.pdf');
    })->name('donations.receipt');
});


// 💳 Stripe success redirect
Route::get('/donation/success', [DonationController::class, 'success'])
    ->name('donations.success');


// 🔥 Stripe webhook (ONLY ONCE — fixed)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);


// 🛡️ ADMIN VERIFY
Route::middleware('auth')->group(function () {

    Route::get('/admin/verify', [AdminController::class, 'showVerify'])
        ->name('admin.verify');

    Route::post('/admin/verify', [AdminController::class, 'verify'])
        ->name('admin.verify.post');
});


// 🛡️ ADMIN PANEL (donations)
Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/donations', [AdminDonationController::class, 'index'])
        ->name('admin.donations');

    Route::post('/donations/{id}/approve', [AdminDonationController::class, 'approve'])
        ->name('admin.donations.approve');

    Route::post('/donations/{id}/reject', [AdminDonationController::class, 'reject'])
        ->name('admin.donations.reject');
});


// 🧪 TEST PDF (optional)
Route::get('/test-pdf/{id}', function ($id) {
    $donation = Donation::findOrFail($id);

    $pdf = Pdf::loadView('pdf.certificate', [
        'donation' => $donation
    ]);

    return $pdf->stream('certificate.pdf');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/admin/donations', [AdminDonationController::class, 'index'])
        ->name('admin.donations');

    Route::post('/admin/donations/{id}/approve', [AdminDonationController::class, 'approve'])
        ->name('admin.donations.approve');

    Route::post('/admin/donations/{id}/reject', [AdminDonationController::class, 'reject'])
        ->name('admin.donations.reject');

});


require __DIR__.'/auth.php';