<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;

class DonationPolicy
{
    public function view(User $user, Donation $donation): bool
    {
        return $this->isAdmin($user) || $donation->user_id === $user->id;
    }

    private function isAdmin(User $user): bool
    {
        return isset($user->role) && $user->role === 'admin';
    }
}
