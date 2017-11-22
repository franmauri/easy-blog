<?php

namespace App\Http\Controllers;

use App\User;
use App\Transformers\UserProfileTransformer;
use App\Transformers\UserTransformer;
use App\Http\Controllers\Controller;
use App\Serializers\paginationApiSerializer;
use Flugg\Responder\Facades\Responder;
use Illuminate\Http\Request;
use Watson\Validating\ValidationException;

class UsersController extends Controller {

    public function index(Request $request) {
        try {
            $query = User::with('image');
            if ($search = $request->get('search')) {
                $query->SearchByKeyword($search);
            }

            $users = $query
                    ->orderBy('users.name')
                    ->orderBy('users.created_at')
                    ->paginate($request->per_page);

            return Responder::success($users, new UserProfileTransformer)
                            ->serializer(paginationApiSerializer::class)
                            ->respond();
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function edit($id) {
        try {

            $user = User::findOrFail($id);
            return Responder::success($user, new UserProfileTransformer())
                            ->respond();
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function update(User $user, Request $request) {
        try {
            $user = User::updateProfile($user, $request->all());
            return Responder::success($user, new UserProfileTransformer())
                            ->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function create(Request $request) {

        try {
            $user = User::make($request->all());
            return Responder::success($user, new UserProfileTransformer())
                            ->respond();
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public function destroy($id) {
        try {

            $user = User::findOrFail($id);
            $result = $user->forceDelete();
            if ($result) {
                $user->syncPermissions([]);

                return Responder::success();
            }
            return Responder::error('User was not deleted! Try again!');
        } catch (\Exception $e) {
            return Responder::error($e->getMessage());
        }
    }

    public static function Routes() {
        \Route::group(['prefix' => 'users'], function () {
            \Route::get('/', '\App\Http\Controllers\UsersController@index');
            \Route::get('{id}', '\App\Http\Controllers\UsersController@edit');
            \Route::patch('{user}', '\App\Http\Controllers\UsersController@update');
            \Route::post('/', '\App\Http\Controllers\UsersController@create');
            \Route::delete('{user}', '\App\Http\Controllers\UsersController@destroy');
        });
    }

}
