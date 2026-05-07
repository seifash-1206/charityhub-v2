<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\DonationTrackingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCampaignController;
use App\Http\Controllers\Admin\AdminDonationsController;
use App\Http\Controllers\Admin\AdminVolunteerController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── ROOT → Dashboard ────────────────────────────────────────────────────
Route::get('/', fn() => redirect('/dashboard'));


// ── PUBLIC ROUTES ────────────────────────────────────────────────────────
// Stripe webhook (no CSRF — excluded in bootstrap/app.php)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');

// Stripe success redirect (public — Stripe redirects here)
Route::get('/donation/success', [DonationController::class, 'success'])
    ->name('donations.success');

// Public donation tracking
Route::get('/track', [DonationTrackingController::class, 'index'])
    ->name('donations.track');

Route::post('/track', [DonationTrackingController::class, 'search'])
    ->name('donations.track.search');

Route::get('/verify/{trackingId}', [DonationTrackingController::class, 'verify'])
    ->name('donations.verify');

// Test PDF (dev only)
Route::get('/test-pdf/{id}', function ($id) {
    $donation = Donation::with(['user', 'campaign'])->findOrFail($id);
    $pdf = Pdf::loadView('pdf.certificate', ['donation' => $donation]);
    return $pdf->stream('certificate.pdf');
})->name('donations.pdf.test');


// ── AUTHENTICATED USER ROUTES ─────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Campaigns (full resource, slug-based)
    Route::resource('campaigns', CampaignController::class);

    // Donate to a campaign
    Route::post('/campaigns/{campaign}/donate', [DonationController::class, 'store'])
        ->name('donations.store');

    // Download receipt PDF
    Route::get('/donations/{id}/receipt', function ($id) {
        $donation = Donation::with(['user', 'campaign'])->findOrFail($id);

        abort_unless($donation->user_id === auth()->id() || auth()->user()->role === 'admin', 403);

        $pdf = Pdf::loadView('pdf.certificate', ['donation' => $donation]);
        return $pdf->download("charityhub-receipt-{$donation->tracking_id}.pdf");
    })->name('donations.receipt');

    // My donations
    Route::get('/my-donations', [DonationTrackingController::class, 'myDonations'])
        ->name('donations.my');

    // Volunteers (full CRUD for users)
    Route::resource('volunteers', VolunteerController::class);

});


// ── ADMIN VERIFICATION ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/admin/verify', [AdminController::class, 'showVerify'])
        ->name('admin.verify');
    Route::post('/admin/verify', [AdminController::class, 'verify'])
        ->name('admin.verify.post');
});


// ── ADMIN PORTAL ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Campaigns management
        Route::resource('campaigns', AdminCampaignController::class)
            ->except(['create', 'store']);
        Route::post('/campaigns/{campaign}/status', [AdminCampaignController::class, 'updateStatus'])
            ->name('campaigns.status');

        // Donations management
        Route::get('/donations', [AdminDonationsController::class, 'index'])
            ->name('donations.index');
        Route::get('/donations/{donation}', [AdminDonationsController::class, 'show'])
            ->name('donations.show');
        Route::post('/donations/{id}/approve', [AdminDonationsController::class, 'approve'])
            ->name('donations.approve');
        Route::post('/donations/{id}/reject', [AdminDonationsController::class, 'reject'])
            ->name('donations.reject');

        // Volunteer management
        Route::resource('volunteers', AdminVolunteerController::class)
            ->except(['create', 'store']);
        Route::post('/volunteers/{volunteer}/status', [AdminVolunteerController::class, 'updateStatus'])
            ->name('volunteers.status');
        Route::post('/volunteers/{volunteer}/hours', [AdminVolunteerController::class, 'logHours'])
            ->name('volunteers.hours');

        // User management
        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])
            ->name('users.show');
        Route::post('/users/{user}/role', [AdminUserController::class, 'updateRole'])
            ->name('users.role');

        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])
            ->name('reports.index');

        // Legacy alias (keep backward compatibility with old admin.donations route)
        Route::get('/donations-legacy', [AdminDonationsController::class, 'index'])
            ->name('donations');

    });


// ── AUTH ROUTES ──────────────────────────────────────────────────────────
require __DIR__.'/auth.php';