<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Image;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', 'HomeController@index')->name('home');


Auth::routes();

\Route::group(['prefix' => 'api'], function () {
    


    \Route::get('/posts', 'PostsController@index');

    Route::get('/user', function (Request $request) {
        if (!Auth::check())
            return response(['auth' => false], 200);

        return Responder::success(Auth::user(), function($user) {
                    return [
                        "id" => $user->id,
                        "language" => $user->language,
                        "name" => $user->name,
                        "image" => $user->getImage('small'),
                        "email" => $user->email,
                        //"role" => $user->getRoleNames()[0],
                        'permissions' => $user->permissions,
                        'created' => $user->created_at,
                    ];
                })->respond();
    });
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/image/{id}/{size?}', function ($id, $size = 'original') {
        $image = Image::find($id);
        return $image->serve($size);
    })->name('image');

    \Route::group(['prefix' => 'api'], function () {


//        Route::get('/checkAuth', function () {
//            if (Auth::check())
//                return response(['auth' => true], 200);
//            return response(['auth' => false], 401);
//        });

        \App\Http\Controllers\PostsController::Routes();
        \App\Http\Controllers\UsersController::Routes();
    });




    Route::get('/{vue_capture?}', function () {
        return view('welcome');
    })->where('vue_capture', '[\/\w\.-]*');
});
