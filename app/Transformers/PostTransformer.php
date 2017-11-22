<?php

namespace App\Transformers;

use App\Language;
use App\Post;
use Flugg\Responder\Transformers\Transformer;
use Illuminate\Support\Facades\Cache;
use League\Fractal\ParamBag;

class PostTransformer extends Transformer {
    /**
     * A list of all available relations.
     *
     * @var array
     */
    //protected $relations = ['comments', 'likes'];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    //protected $load = ['comments', 'likes'];

    /**
     * Transform the model data into a generic array.
     *
     * @param  Post $post
     * @return array
     */
    public function transform(Post $post): array {
        return [
            'id' => (int) $post->id,
            'user' => [
                'id' => $post->user->id,
                'name' => $post->user->name,
                'image' => $post->user->getImage('small'),
                'lang'=>$post->user->default_lang
            ],
            'title' => $post->title,
            'content' => $post->content,
            
            'title_lang' => $post->title_lang,
            'content_lang' => $post->content_lang,
            
            'excerpt'=>$post->excerpt,
            'languages' => array_keys((array) json_decode($post->title)),
            'no_of_likes' => $post->no_of_likes,
            'current_user_like' => $post->current_user_like,
            'active_title'=>(array)json_decode($post->title),
            //'comments_count' => \Auth::user()->hasRole('user') ? $post->no_of_comments - $post->no_of_comments__hidden : $post->no_of_comments,
            'date' => $this->theDate($post->created_at),
            'image' => $post->getImage('original', 'base64'),
            'image_url' => $post->getImage('original', 'url'),
                //'liked_by_current_user' => $post->likes->pluck('id')->contains(\Auth::user()->id),
        ];
    }
    
    function theDate($date){
        
        return date_format($date, 'g:ia \o\n l jS F Y');
        
    }

//  public function includeComments(Post $post, $params)
//  {
//    $limit = array_get($params, 'limit') ?: [2, 1];
//
//    //maybe do a query here
//
//    $comments = $post->comments;
//
//    if ($limit) {
//      list($limit, $page) = $limit;
//      $comments = $comments
//        ->forPage($page, $limit);
//    }
//
//    return $this->resource($comments);
//  }
//
//  public function includeLikes(Post $post, $params)
//  {
//    $limit =  array_get($params, 'limit') ?: [5, 1];
//
//    //maybe do a query here
//    $likes = $post->likes;
//
//    if ($limit) {
//      list($limit, $page) = $limit;
//      $likes = $likes
//        ->forPage($page, $limit);
//    }
//
//    return $this->resource($likes, new LikeTransformer);
}
