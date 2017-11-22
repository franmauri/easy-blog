<?php

namespace App\Transformers;

use App\Comment;
use Flugg\Responder\Transformers\Transformer;

class CommentTransformer extends Transformer
{
  /**
   * A list of all available relations.
   *
   * @var array
   */
  protected $relations = ['comments'];

  /**
   * List of autoloaded default relations.
   *
   * @var array
   */
  protected $load = ['comments'];

  /**
   * Transform the model data into a generic array.
   *
   * @param Comment $comment
   * @return array
   */
  public function transform(Comment $comment): array
  {
    return [
      'id' => (int)$comment->id,
      'user' => [
        'id' => $comment->user->id,
        'name' => $comment->user->fullName,
        'image' => $comment->user->getImage('small')
      ],
      'text' => $comment->text,
      'image' => $comment->getImage('medium'),
      'created_at' => $comment->created_at,
      'hidden' => $comment->deleted_at ? true : false,
      'likes_count' => (int)$comment->no_of_likes,
      'comments_count' => (int)$comment->no_of_comments,
      'parent_id' => (int)$comment->parent_id,
      'liked_by_current_user' => $comment->likes->pluck('id')->contains(\Auth::user()->id)
    ];
  }

  public function includeComments(Comment $comment)
  {
    return $this->resource($comment->comments);
  }
}
