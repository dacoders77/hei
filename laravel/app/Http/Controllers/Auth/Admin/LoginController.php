<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
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
    protected $redirectTo = '/admin/campaigns';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        // if( config('app.url') !== url('/') ) abort(404);
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);


        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }


        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            '_lcaptcha' => 'required|lcaptcha',
            '_mn' => 'honeypot',
            '_mt' => 'required|honeytime:1',
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if(isset($request->remember)) {
            //
            // set remember me expire time
            $rememberTokenExpireMinutes = env('REMEMBER_ME_LIFETIME', 43200);

            // first we need to get the "remember me" cookie's key, this key is generate by laravel randomly
            $rememberTokenName = str_replace('remember_web_','remember_admin_',Auth::getRecallerName());

            $cookieJar = $this->guard()->getCookieJar();

            if( $cookieJar->queued($rememberTokenName) ){
                $cookieValue = $cookieJar->queued($rememberTokenName)->getValue();
                $cookieJar->queue($rememberTokenName, $cookieValue, $rememberTokenExpireMinutes);
            }

        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/admin/login');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }
}
