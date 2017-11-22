<?php

namespace App\Transformers;

use App\User;
use Flugg\Responder\Transformers\Transformer;

class LikeTransformer extends Transformer
{
  /**
   * A list of all available relations.
   *
   * @var array
   */
  protected $relations = [];

  /**
   * List of autoloaded default relations.
   *
   * @var array
   */
  protected $load = [];

  /**
   * Transform the model data into a generic array.
   *
   * @param User $user
   * @return array
   */
  public function transform(User $user): array
  {
    return [
      'user' => [
        'id' => $user->id,
        'name' => $user->fullName,
        'image' => $user->getImage('small')
      ]
    ];
  }
}
