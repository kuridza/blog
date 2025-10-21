<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function create(User $user): bool
    {
        return (bool) $user;
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->role === 'ADMIN';
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->role === 'ADMIN') {
            return true;
        }
        // Only comment owners can delete
        return $user->id === $comment->user_id;
    }

    public function moderate(User $user, Comment $comment): bool
    {
        return $user->role === 'ADMIN' || $user->role === 'MOD';
    }
}
