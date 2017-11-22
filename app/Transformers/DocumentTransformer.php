<?php

namespace App\Transformers;

use App\SharePoint;
use Flugg\Responder\Transformers\Transformer;

class DocumentTransformer extends Transformer
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

  public function transform(SharePoint $document): array
  {
    return [
      'id' => (int)$document->id,
      'icon' => $document->icon,
      'title' => $document->title,
      'editor' => $document->editor,
      'last_modified' => $document->modified_date,
      'type' => $document->type,
      'process' => $document->process,
      'section' => $document->section,
      'url' => $document->url,
    ];
  }
}
