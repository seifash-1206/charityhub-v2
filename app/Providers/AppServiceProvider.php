<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-fix missing slugs for existing campaigns
        try {
            $campaignsWithoutSlugs = \App\Models\Campaign::whereNull('slug')->orWhere('slug', '')->get();
            foreach ($campaignsWithoutSlugs as $campaign) {
                $base = \Illuminate\Support\Str::slug($campaign->title ?: 'campaign');
                $campaign->slug = $base . '-' . $campaign->id;
                $campaign->saveQuietly();
            }

            // Auto-fix missing tracking_ids for existing donations
            $donationsWithoutTrackingIds = \App\Models\Donation::whereNull('tracking_id')->orWhere('tracking_id', '')->get();
            foreach ($donationsWithoutTrackingIds as $donation) {
                $donation->tracking_id = 'DON-' . $donation->created_at->format('Y') . '-' . strtoupper(uniqid());
                $donation->saveQuietly();
            }
        } catch (\Throwable $e) {
            // Ignore if DB isn't ready
        }
    }
}
