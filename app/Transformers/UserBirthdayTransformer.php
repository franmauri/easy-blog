<?php

namespace App\Transformers;

use App\User;
use Flugg\Responder\Transformers\Transformer;

class UserBirthdayTransformer extends Transformer
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
      'id' => (int)$user->id,
      'name' => (string)$user->first_name,
      'lastname' => (string)$user->last_name,
      'image' => $user->getImage('small'),
      'birthday' => $user->birth_date
    ];
  }
}
