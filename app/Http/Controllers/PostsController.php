<?php

namespace App\Http\Controllers;

use App\Post;
use App\Transformers\PostTransformer;
use App\Http\Controllers\Controller;
use App\Serializers\paginationApiSerializer;
use Flugg\Responder\Facades\Responder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Watson\Validating\ValidationException;
use Carbon\Carbon;

//use Faker\Generator as Faker;

class PostsController extends Controller {

    public function index(Request $request) {
        try {

            $eagerLoad = ['comments.user', 'comments.comments.user', 'likes'];
            //if (\Auth::user() && !\Auth::user()->hasRole('user')) {
            $eagerLoad['comments'] = function ($q) {
                $q->withTrashed();
            };
            //}
            //$currentLang = '"' . getUserLang() . '"';
            $currentLang = 'en';
            $query = Post::with($eagerLoad)->whereRaw("BINARY LOWER(title) LIKE BINARY '%$currentLang%'");
            $sortBy = 'new';
            if ($request->has('sortBy')) {
                $sortBy = $request->get('sortBy');
            }
            switch ($sortBy) {
                case 'hot':
                    $posts = $query->orderBy('no_of_likes', 'desc');
                    break;
                case 'new':
                    $posts = $query->orderBy('created_at', 'desc');
                    break;
                case 'old':
                    $posts = $query->orderBy('created_at', 'asc');
                    break;
            }

            $posts = $posts->paginate($request->per_page);

            return Responder::success($posts, new PostTransformer)->serializer(paginationApiSerializer::class)->respond();
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function create(Request $request) {
        try {
            $post = Post::make($request->all(), Auth::user());
            return Responder::success($post, new PostTransformer)->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function edit(Post $post) {
        try {
            return Responder::success($post, new PostTransformer)->respond();
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function update(Post $post, Request $request) {
        try {
            $post = Post::updatePost($post, $request->all());
            return Responder::success($post, new PostTransformer)->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function destroy(Post $post) {
        try {
            $result = $post->forceDelete();
            if ($result)
                return Responder::success();
            return Responder::error('Post was not deleted! Try again!');
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function likeToggle(Post $post, Request $request) {
        try {


            $like = intVal(!(bool) strpos($request->path(), 'dislike'));
            $post->like(Auth::user()->id, $like);

            return Responder::success($post, function($post) {
                        return [
                            "no_of_likes" => $post->no_of_likes,
                        ];
                    });
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

//    public function find()
//    {
//        return $this->post->search($this->request->q)->get();
//    }

    public static function Routes() {
        \Route::group(['prefix' => 'posts'], function () {


            \Route::get('{post}', '\App\Http\Controllers\PostsController@edit');
            \Route::delete('{post}', '\App\Http\Controllers\PostsController@destroy')->middleware('can:delete,post');
            \Route::patch('{post}', '\App\Http\Controllers\PostsController@update'); //->middleware('can:update,post');
            \Route::patch('{post}/like', '\App\Http\Controllers\PostsController@likeToggle');
            \Route::patch('{post}/dislike', '\App\Http\Controllers\PostsController@likeToggle');
            \Route::post('/', '\App\Http\Controllers\PostsController@create'); //->middleware('can:create,App\Post');
        });
    }

}
