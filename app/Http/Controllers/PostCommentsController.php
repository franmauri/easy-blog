<?php

namespace App\Http\Controllers;

use App\Post;
use App\Transformers\CommentTransformer;
use App\Http\Controllers\Controller;
use Flugg\Responder\Facades\Responder;
use Illuminate\Http\Request;
use Watson\Validating\ValidationException;

class PostCommentsController extends Controller
{
    public function index($postId, Request $request)
    {

        $eagerLoad = ['comments.likes', 'comments.user'];

        if(!\Auth::user()->hasRole('user')){
            $eagerLoad['comments'] = function ($q) {
                $q->withTrashed();
            };
        }

        $query = Post::with($eagerLoad)
            ->findOrFail($postId)
            ->comments();

        if(!\Auth::user()->hasRole('user')){
            $query->withTrashed();
        }

        $comments = $query->paginate($request->limit);
        return Responder::success($comments, new CommentTransformer)->respond();
    }

    public function store($id, Request $request)
    {
        try {
            $post = Post::findOrFail($id);
            $comment = $post->comment($request->except('user'), $request->user()->id);
            return Responder::success($comment, new CommentTransformer)
                ->meta([
                    'post' => [
                        'comments_count' => $post->comments->count()
                    ]
                ])
                ->respond();
        }catch(ValidationException $e){
          return $this->validationErrorResponse($e);
        }
        catch(\Exception $e){
            return Responder::error($e->getMessage());
        }
    }

    public static function Routes()
    {
        \Route::group(['prefix' => 'posts'], function () {
            \Route::get('{id}/comments', '\App\Http\Controllers\PostCommentsController@index');
            \Route::post('{id}/comments', '\App\Http\Controllers\PostCommentsController@store');
        });
    }
}
