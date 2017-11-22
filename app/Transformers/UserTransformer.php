<?php

namespace App\Transformers;

use App\User;
use Flugg\Responder\Transformers\Transformer;

class UserTransformer extends Transformer
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
      'name' => (string)$user->first_name . ' ' . $user->last_name,
      'first_name' => (string)$user->first_name,
      'last_name' => (string)$user->last_name,
      'image' => $user->getImage('small'),
      'job' => (string)$user->job_title,
      'email' => (string)$user->email,
      'phone' => (string)$user->phone,
      'company' => (string)$user->company_name,
      'department_name' => (string)$user->department_name,
      'created_at' => (string)$user->created_at
    ];
  }
}
