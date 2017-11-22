<?php

namespace App\Transformers;

use App\SharePoint;
use Flugg\Responder\Transformers\Transformer;

class SharePointTransformer extends Transformer
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
  public function transform(SharePoint $sharePoint): array
  {
    return [
      'id' => (int)$sharePoint->id,
      'title' => (string)$sharePoint->title,
      'icon' => (string)$sharePoint->icon,
      'modified' => (string)$sharePoint->modified,
      'editor' => (string)$sharePoint->editor,
      'url' => (string)$sharePoint->url,
      'process' => (string)$sharePoint->process,
      'section' => (string)$sharePoint->section,
      'folder' => (string)$sharePoint->folder,
      'unique_id' => (string)$sharePoint->unique_id,
      'created_at' => (string)$sharePoint->created_at
    ];
  }
}
