<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Transformers\CommentTransformer;
use App\Http\Controllers\Controller;
use Flugg\Responder\Facades\Responder;
use Illuminate\Http\Request;
use Watson\Validating\ValidationException;

class CommentsController extends Controller
{

//    public function update(PostRequest $request, $id)
//    {
//        $this->post->where('id', '=', $id)->update($request->postData);
//        return \Responder::success(200);
//    }

    public function destroy($id)
    {
        try {
            $comment = Comment::withTrashed()->findOrFail($id);
            $owner = $comment->commentable;

            if($comment->trashed()){
                $comment->restore();
                $owner->no_of_comments_hidden--;
            }else{
                $comment->delete();
                $owner->no_of_comments_hidden++;
            }

            $result = $owner->save();

            if($result)
                return Responder::success($comment, new CommentTransformer)->respond();
            return Responder::error('Comment was not deleted! Try again!');
        }
        catch(\Exception $e){
            return Responder::error($e->getMessage());
        }
    }

    public function store($id, Request $request)
    {
        try {
            $commented = Comment::findOrFail($id);
            $comment = $commented->comment($request->all(), \Auth::user()->id);
            return Responder::success($comment, new CommentTransformer)
                ->meta([
                    'parent' => [
                        'comments_count' => $commented->comments->count()
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

    public function likeToggle($id, Request $request)
    {
        try {
            $comment = Comment::findOrFail($id)->like(\Auth::user()->id);
            return Responder::success($comment, new CommentTransformer)->respond();
        }catch(ValidationException $e){
          return $this->validationErrorResponse($e);
        }
        catch(\Exception $e){
            return Responder::error($e->getMessage());
        }
    }

    public static function Routes()
    {
        \Route::group(['prefix' => 'comments'], function () {
            \Route::patch('{id}/like', '\App\Http\Controllers\CommentsController@likeToggle');
            \Route::post('{id}/comment', '\App\Http\Controllers\CommentsController@store');
            \Route::delete('{id}', '\App\Http\Controllers\CommentsController@destroy');
        });
    }
}
