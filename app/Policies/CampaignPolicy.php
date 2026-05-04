<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    /**
     * Anyone logged in can view campaigns list
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Anyone (even guests) can view campaign
     */
    public function view(?User $user, Campaign $campaign): bool
    {
        return true;

        // 🔥 OPTIONAL (if you want later):
        // return $campaign->status === 'active';
    }

    /**
     * 🔥 ONLY VERIFIED ADMIN CAN CREATE
     */
    public function create(User $user): bool
    {
        return $this->isVerifiedAdmin($user);
    }

    /**
     * 🔥 ONLY VERIFIED ADMIN CAN UPDATE
     */
    public function update(User $user, Campaign $campaign): bool
    {
        return $this->isVerifiedAdmin($user);
    }

    /**
     * 🔥 ONLY VERIFIED ADMIN CAN DELETE
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        return $this->isVerifiedAdmin($user);
    }

    /**
     * 🔥 ONLY VERIFIED ADMIN CAN RESTORE
     */
    public function restore(User $user, Campaign $campaign): bool
    {
        return $this->isVerifiedAdmin($user);
    }

    /**
     * 🔥 ONLY VERIFIED ADMIN CAN FORCE DELETE
     */
    public function forceDelete(User $user, Campaign $campaign): bool
    {
        return $this->isVerifiedAdmin($user);
    }

    /**
     * 🔐 ADMIN CHECK (safe check)
     */
    private function isAdmin(User $user): bool
    {
        return isset($user->role) && $user->role === 'admin';
    }

    /**
     * 🔐 VERIFIED ADMIN (role + session key)
     */
    private function isVerifiedAdmin(User $user): bool
    {
        return $this->isAdmin($user) && session('admin_verified') === true;
    }
}