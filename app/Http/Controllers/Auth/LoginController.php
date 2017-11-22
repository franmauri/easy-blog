<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user) {


        //set user lang
        session([
            'lang' => $user->lang,
        ]);
//
//        //make the user lang the system lang
        \App::setLocale($user->lang);
        if ($request->ajax()) {

            return response()->json([
                        'auth' => auth()->check(),
                        'user' => $user,
                        'intended' => $this->redirectPath(),
                        'errors' => false,
            ]);
        }
    }

    public function logout(Request $request) {
        $this->guard()->logout();

        $request->session()->invalidate();


        if ($request->ajax()) {

            return response()->json([
                        'auth' => false,
                        'errors' => false,
            ]);
        }

        return redirect('/blog');
    }

}
