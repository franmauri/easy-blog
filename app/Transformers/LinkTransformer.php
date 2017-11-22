<?php

namespace App\Transformers;


use App\Link;
use App\User;
use Flugg\Responder\Transformers\Transformer;

class LinkTransformer extends Transformer
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
   * @param Link $link
   * @return array
   */
    public function transform(Link $link):array
    {
        $data= [
            'id' => (int) $link->id,
            'name' => $link->name,
            'link' => $link->link,
            'icon' => $link->icon,
            'created_at' => $link->created_at,
            'isAdmin' => $link->user_id ? User::find($link->user->id)->hasRole('admin') : false
        ];
        if (!empty($link->user) && 'object' == gettype($link->user)) {
            $data['user']=[
                'id' => $link->user->id,
                'name' => $link->user->fullName,
                'image' => $link->user->getImage('small'),
                'isAdmin' => User::find($link->user->id)->hasRole('admin') ? true : false
            ];
        }
        
        return $data;
        
    }
}
