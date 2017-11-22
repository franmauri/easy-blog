<?php

namespace App\Transformers;

use App\Language;
use Flugg\Responder\Transformers\Transformer;

class LanguageTransformer extends Transformer
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

  public function transform(Language $language): array
  {
    $languages = language()->all();

    return [
      'id' => $language->id,
      'code' => $language->code,
      'short_name' => $language->name,
      'name' => $languages[$language->code],
      'order' => $language->order
    ];

  }
}
