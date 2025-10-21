<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return (bool) $user;
    }

    public function update(User $user, Post $post): bool
    {
//        return true;
        return $user->id === $post->user_id || $user->role === 'ADMIN';
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->role === 'ADMIN') {
            return true;
        }
        // Moderator cannot delete others' posts
        if ($user->role === 'MOD') {
            return $user->id === $post->user_id;
        }
        return $user->id === $post->user_id;
    }

    public function moderate(User $user, Post $post): bool
    {
        return $user->role === 'ADMIN' || $user->role === 'MOD';
    }
}
