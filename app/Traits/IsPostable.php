<?php

namespace App\Traits;

use App\Post;

trait IsPostable
{
    public function posts()
    {
        return $this->morphMany(Post::class, 'postable');
    }

    public function getPostAttribute()
    {
        return $this->posts()->withTrashed()->first();
    }

    public function eCreated()
    {
        if(!$this->posts()->count()){
            $this->posts()->create([]);
        }
    }

    public function eDeleting()
    {
        if($this->post)
            $this->post->delete();
    }
}