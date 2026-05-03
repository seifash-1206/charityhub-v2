<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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

});

Route::get('/admin/verify', function () {
    return view('admin.verify');
})->middleware('auth')->name('admin.verify');

Route::post('/admin/verify', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'verifyAdmin'])
    ->middleware('auth')
    ->name('admin.verify.post');

Route::middleware('auth')->group(function () {
    Route::get('/admin/verify', [App\Http\Controllers\AdminController::class, 'showVerify'])
        ->name('admin.verify');

    Route::post('/admin/verify', [App\Http\Controllers\AdminController::class, 'verify']);
});    


// 🔑 Auth routes (login, register, etc.)
require __DIR__.'/auth.php';