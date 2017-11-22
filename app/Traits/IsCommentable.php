<?php

namespace App\Traits;

use App\Comment;

trait IsCommentable {

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at', 'desc');
    }

    public function comment($data, $userOrId) {
        $comment = Comment::make($data, $userOrId);
        $this->comments()->save($comment);
        $this->no_of_comments = $this->comments->count();
        $this->save();
        $this->makeItARootOrAChild($comment);
        return $comment->fresh();
    }

    private function makeItARootOrAChild($comment) {
        if (get_class($this) == Comment::class) {
            $comment->makeChildOf($this);
        } else {
            $comment->makeRoot();
        }
    }

}
