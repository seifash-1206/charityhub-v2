<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Inspire quote (built-in)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─────────────────────────────────────────────────────────────
// SCHEDULER: Auto-update campaign statuses every 30 minutes
// ─────────────────────────────────────────────────────────────
Schedule::command('campaigns:update-status')
    ->everyThirtyMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/campaign-status-updates.log'));
