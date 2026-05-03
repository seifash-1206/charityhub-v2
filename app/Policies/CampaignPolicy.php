<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    /**
     * Anyone logged in can see campaigns list
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Anyone (even guest later) can view campaign
     */
    public function view(?User $user, Campaign $campaign): bool
    {
        return true;
    }

    /**
     * 🔥 ONLY ADMIN CAN CREATE
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * 🔥 ONLY ADMIN CAN UPDATE
     */
    public function update(User $user, Campaign $campaign): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * 🔥 ONLY ADMIN CAN DELETE
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * 🔥 ONLY ADMIN CAN RESTORE
     */
    public function restore(User $user, Campaign $campaign): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * 🔥 ONLY ADMIN CAN FORCE DELETE
     */
    public function forceDelete(User $user, Campaign $campaign): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * 🔐 ADMIN CHECK
     */
    private function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }
}