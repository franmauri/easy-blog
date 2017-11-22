<?php

namespace App\Transformers;

use App\Birthday;
use Flugg\Responder\Transformers\Transformer;

class BirthdayTransformer extends Transformer
{
  /**
   * A list of all available relations.
   *
   * @var array
   */
  protected $relations = ['*'];

  /**
   * List of autoloaded default relations.
   *
   * @var array
   */
  protected $load = [];

  /**
   * Transform the model data into a generic array.
   *
   * @param  Birthday $birthday
   * @return array
   */
  public function transform(Birthday $birthday): array
  {
    return [
      'id' => (int)$birthday->id,
      'message' => $birthday->message,
      'unread' => $birthday->unread,
      'thanks' => $birthday->thanks,
      'user' => $birthday->getUser($birthday->from_id),
      'created_at' => $birthday->created_at
    ];
  }
}
