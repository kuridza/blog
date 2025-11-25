<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Comment;
use App\Models\User;

class CategoryPolicy
{
    public function create(User $user): bool
    {
        return (bool) $user;
    }

    public function update(User $user): bool
    {
        return $user->role === 'ADMIN';
    }

    public function delete(User $user): bool
    {
        return $user->role === 'ADMIN';
    }

    public function moderate(User $user, Comment $comment): bool
    {
        return $user->role === 'ADMIN';
    }
}
