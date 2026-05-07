<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Volunteer;

class VolunteerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Volunteer $volunteer): bool
    {
        return $this->isAdmin($user) || $volunteer->user_id === $user->id;
    }

    public function delete(User $user, Volunteer $volunteer): bool
    {
        return $this->isAdmin($user) || $volunteer->user_id === $user->id;
    }

    private function isAdmin(User $user): bool
    {
        return isset($user->role) && $user->role === 'admin';
    }
}
