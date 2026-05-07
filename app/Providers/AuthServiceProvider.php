<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// 🔥 Models
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Volunteer;

// 🔥 Policies
use App\Policies\CampaignPolicy;
use App\Policies\DonationPolicy;
use App\Policies\VolunteerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 🔐 Campaign authorization mapping
        Campaign::class => CampaignPolicy::class,
        Donation::class => DonationPolicy::class,
        Volunteer::class => VolunteerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // 🔥 Register policies
        $this->registerPolicies();

        // 🔥 Optional: future gates (for admin/global permissions)
        // Example:
        // Gate::define('isAdmin', function ($user) {
        //     return $user->role === 'admin';
        // });
    }
}