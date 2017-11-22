<?php

namespace App\Policies;

use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    public function view(User $user, Post $post)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create posts');
    }

    public function update(User $user, Post $post)
    {
        return $user->hasPermissionTo('update posts') && $post->postable->user->id === $user->id;
    }

    public function delete(User $user, Post $post)
    {
        return $user->hasPermissionTo('delete posts') && $post->postable->user->id === $user->id;
    }
}
