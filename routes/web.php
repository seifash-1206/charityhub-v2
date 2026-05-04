<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\StripeWebhookController;
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

    // 📊 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🎯 Campaigns (FULL CRUD)
    Route::resource('campaigns', CampaignController::class);

    // 💰 Donations (NEW 🔥)
    Route::post('/campaigns/{campaign}/donate', [DonationController::class, 'store'])
        ->name('donations.store');
});


// 🛡️ ADMIN VERIFICATION (CLEANED — NO DUPLICATES)
Route::middleware('auth')->group(function () {

    Route::get('/admin/verify', [AdminController::class, 'showVerify'])
        ->name('admin.verify');

    Route::post('/admin/verify', [AdminController::class, 'verify'])
        ->name('admin.verify.post');

});


Route::get('/donation/success', [DonationController::class, 'success'])
    ->name('donations.success');


Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handle']);
// 🔑 Auth routes (login, register, etc.)



Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);


Route::get('/test-pdf/{id}', function ($id) {
    $donation = App\Models\Donation::findOrFail($id);

    $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.certificate', [
        'donation' => $donation
    ]);

    return $pdf->stream('certificate.pdf');
});


Route::get('/donations/{id}/receipt', function ($id) {
    $donation = Donation::with(['user','campaign'])->findOrFail($id);

    $pdf = Pdf::loadView('pdf.receipt', [
        'donation' => $donation
    ]);

    return $pdf->download('receipt.pdf');
})->middleware('auth')->name('donations.receipt');

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/donations', [App\Http\Controllers\AdminDonationController::class, 'index'])
        ->name('admin.donations');

    Route::post('/admin/donations/{id}/approve', [App\Http\Controllers\AdminDonationController::class, 'approve']);

    Route::post('/admin/donations/{id}/رفض', [App\Http\Controllers\AdminDonationController::class, 'reject']);

});
require __DIR__.'/auth.php';