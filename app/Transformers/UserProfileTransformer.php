<?php

namespace App\Transformers;

use App\User;
use Flugg\Responder\Transformers\Transformer;

class UserProfileTransformer extends Transformer
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
   * @param  User $user
   * @return array
   */
  public function transform(User $user): array
  {
    return [
      'id' => (int)$user->id,
      'name' => (string)$user->name,
      
      'email' => (string)$user->email,
      
      'role' => collect($user->getRoleNames())->first(),
      'is_admin' => $user->hasRole('admin'),
      
      'lang' => $user->default_lang,
      
    ];
  }
}
