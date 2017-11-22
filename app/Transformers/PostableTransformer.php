<?php

namespace App\Transformers;

use App\Article;
use App\SharePointSync;
use App\Bison\Gallery\Models\Gallery;
use App\Bison\Gallery\Models\Menu;
use Flugg\Responder\Transformers\Transformer;
use League\Fractal\ParamBag;

class PostableTransformer extends Transformer
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
   * @param  Postable $post
   * @return array
   */
  public function transform($postable): array
  {
    if (get_class($postable) == Article::class) {

      $title = $postable->title ?: $postable->getTranslation('title', 'en');
      $content = $postable->content ?: $postable->getTranslation('content', 'en');

      //try other languages
      if (!$content) {
        foreach (\Cache::get('languages', collect([])) as $lang) {
          if ($lang == app()->getLocale() || $lang == 'en') continue;

          $content = $postable->getTranslation('content', $lang);
          if ($content) {
            $title = $postable->getTranslation('title', $lang);
            break;
          }
        }
      }

      return [
        'id' => $postable->id,
        'title' => $title,
        'body' => $content,
        'image' => $postable->getImage('large'),
        'languages' => $postable->getTranslations('content')
      ];

    } else if (get_class($postable) == Gallery::class) {

      $imageCount = $postable->photos->count();
      $take = $imageCount > 4 ? 4 : $imageCount;

      return [
        'id' => $postable->id,
        'name' => $postable->name,
        'description' => $postable->description,
        'images' => $postable->photos->take($take)->map->url('medium'),
        'image_count' => $imageCount
      ];
    } else if (get_class($postable) == 'App\Menu') {

      $menus = json_decode($postable->menu);

      return [
        'id' => $postable->id,
        'menu' => $menus,
      ];

    } else if (get_class($postable) == SharePointSync::class) {

      return [
        'id' => $postable->id,
        'updated' => $postable->updated,
        'created' => $postable->created,
        'deleted' => $postable->deleted,
      ];
    }
  }
}
