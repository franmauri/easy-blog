<?php

namespace App\Transformers;

use App\Community;
use Flugg\Responder\Transformers\Transformer;

class CommunityTransformer extends Transformer
{
  /**
   * A list of all available relations.
   *
   * @var array
   */
  protected $relations = ['languages'];

  /**
   * List of autoloaded default relations.
   *
   * @var array
   */
  protected $load = ['languages'];


  public function transform(Community $community): array
  {
    return [
      'id' => (int)$community->id,
      'name' => $community->name,
      'short_name' => $community->short_name,
    ];
  }

  public function includeLanguages(Community $community, $params)
  {
    return $this->resource($community->languages, new LanguageTransformer());
  }
}
