<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectTo()
    {

        $campaign = DB::table('campaigns')->where([
            ['id', \User::find(\Request::user()->id)->user_meta->campaign_id],
            ['status', 1]
        ])->first();

        return $campaign->url;
    }


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {

        $user_id = \User::where('email',$request->{'login__email'})->whereHas('userMeta', function($query) use ($request) {
            return $query->where('meta_key','campaign_id')->where('meta_value',$request->{'_campaign'});
        })->whereHas('userMeta', function($query) use ($request) {
            return $query->where('meta_key','status')->whereIn('meta_value',[4,5]);
        })->value('id');

        $request->merge([
            'id' => $user_id,
            'email' => $request->{'login__email'},
            'password' => $request->{'login__password'},
        ]);

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        if( $request->ajax() ) {
            return \Response::json([
                'success' => false,
                'errors' => [
                    "These credentials do not match our records."
                ]
            ], 400);
        }

        return redirect( app('url')->previous().'#login' )->withErrors(['These credentials do not match our records.']);
        // return $this->sendFailedLoginResponse($request);
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
        if( $request->ajax() && !$user->hasVerifiedEmail() ) {
            \Auth::logout();
            return \Response::json([
                'success' => false,
                'errors' => [
                    "Please validate your account via the email sent to you."
                ]
            ], 400);
        }
        if (!$user->hasVerifiedEmail()) {
            \Auth::logout();
            return redirect()->to(app('url')->previous().'#login')->withErrors(['These credentials do not match our records.']);
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

        $campaign = DB::table('campaigns')->where([
            ['id', $request->cid],
        ])->first();

        return $this->loggedOut($request) ?: redirect($campaign->url);
    }


    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            // 'status' => 4,
            // 'campaign' => $request->{'_campaign'},
            'id' => $request->{'id'},
            'email' => $request->{'email'},
            'password' => $request->{'password'},
        ];
    }


}
