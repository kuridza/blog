<?php

namespace App\Policies;

use App\Models\Ad;
use App\Models\User;

class AdPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Ad $ad): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return (bool) $user;
    }

    public function update(User $user, Ad $ad): bool
    {
        if ($user->role === 'ADMIN') {
            return true;
        }

        return $user->id === $ad->user_id;
    }

    public function delete(User $user, Ad $ad): bool
    {
        if ($user->role === 'ADMIN') {
            return true;
        }

        return $user->id === $ad->user_id;
    }

    public function moderate(User $user, Ad $add): bool
    {
        return $user->role === 'ADMIN';
    }
}
