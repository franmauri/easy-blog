<?php

namespace App\Transformers;

use App\Language;
use App\User;
use Flugg\Responder\Transformers\Transformer;
use Illuminate\Support\Facades\Cache;

class AuthenticatedUserTransformer extends Transformer
{
  /**
   * A list of all available relations.
   *
   * @var array
   */
  protected $relations = [''];

  /**
   * Transform the model data into a generic array.
   *
   * @param  User $user
   * @return array
   */

  /**
   * List of autoloaded default relations.
   *
   * @var array
   */
  protected $load = [];

  public function transform(User $user): array
  {
    $communities = $this->getCommunities($user);
    $chosenCommunityId = session('community') ?: $user->primary_community->id;

    return [
      'id' => (int)$user->id,
      'name' => (string)$user->first_name . ' ' . $user->last_name,
      'first_name' => (string)$user->first_name,
      'last_name' => (string)$user->last_name,
      'email' => (string)$user->email,
      'phone' => (string)$user->phone,
      'birth_date' => $user->birth_date,
      'image' => $user->getImage('small'),
      'job' => (string)$user->job_title,
      'timezone' => (string)$user->timezone ?: 'Europe/Ljubljana',
      'display_name' => (string)(string)$user->roles()->first()->name,
      'permissions' => (array)$user->getAllPermissions()->pluck('name')->toArray(),
      'is_admin' => $user->hasRole('admin'),
      'communities' => array_values($communities->toArray()),
      'community_id' => $chosenCommunityId,
      'company_id' => optional($user->company)->id,
      'lang' => session('user_locale') ?: $user->default_lang,
      'languages' => $this->getLanguages($communities, $chosenCommunityId),
      'settings' => $user->settings()
    ];
  }

  public function getCommunities(User $user)
  {
    $allLanguages = Cache::get('languages', Language::all());

    $communities = $user->getCommunities()->map(function ($community) use ($user, $allLanguages) {
      $languages = $user->hasRole('admin') ? $allLanguages : $community->languages;

      //insert default lang at second place if not already
      $defaultLang = $languages->first(function ($lang, $key) use ($community) {
        return $lang->code == $community->primary_language->code && $key > 1;
      });

      if ($defaultLang) {
        $languages = $languages->sortBy('order');
        $languages = $languages->reject(function ($lang) use ($defaultLang) {
          return $lang->code == $defaultLang->code;
        });
        $languages->splice(1, 0, [$defaultLang]);
      }

      $data = $community->toApiFormat();
      $data['languages'] = $languages->map(function ($lang, $key) {
        return [
          'id' => $lang->id,
          'code' => $lang->code,
          'name' => $lang->name,
          'order' => $key + 1,
        ];
      })->toArray();

      return $data;
    });

    return $communities;

  }

  public function getLanguages($communities, $chosenCommunityId)
  {
    $community = $communities->first(function ($el) use ($chosenCommunityId) {
      return $el['id'] == $chosenCommunityId;
    });

    return $community['languages'];
  }
}
