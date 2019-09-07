<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Hash;
use Validator;
use User;
use Request;
use Campaign;
use Crypt;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            '_campaign' => ['required'],
            'sign_up__first_name' => ['required', 'string', 'max:255'],
            'sign_up__last_name' => ['required', 'string', 'max:255'],
            'sign_up__email' => ['required', 'string', 'email', 'max:255', 'campaign:'.$data['_campaign']],
            'sign_up__password' => ['required', 'string', 'min:6'],
            'sign_up__terms' => ['required'],
            'sign_up__recieve_comms' => ['required'],
        ],[
            'sign_up__email.campaign' => 'Sorry, registration is not possible for this email.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Model\User\User
     */
    protected function create(array $data)
    {
        // Get User if exists
        $user = User::where('email',$data['sign_up__email'])->whereHas('userMeta', function($query) use ($data) {
            return $query->where('meta_key','campaign_id')->where('meta_value',$data['_campaign']);
        })->whereHas('userMeta', function($query) use ($data) {
            return $query->where('meta_key','status')->where('meta_value',2);
        })->first();

        // Save user details
        $user->first_name = $data['sign_up__first_name'];
        $user->last_name = $data['sign_up__last_name'];
        $user->password = Hash::make($data['sign_up__password']);
        $user->save();

        // Save User Status
        $userMeta = UserMeta::firstOrNew([
            'user_id' => $user->id,
            'meta_key' => 'status',
        ]);
        $userMeta->meta_value = 4;
        $userMeta->save();

        // Set verify URL
        $data['verify_url'] = Campaign::find( $data['_campaign'] )->value('url') . '/email/verify/' . Crypt::encrypt($user->id);

        Mail::send('campaigns.mail.register_1', $data, function($message) use ($data) {
            $message->to($data['sign_up__email'], $data['sign_up__first_name'] . ' ' . $data['sign_up__last_name'])
                    ->subject('Thank you ' . $data['sign_up__first_name'] .' for joining the iPrimus Fuel Promotion');
        });

        return $user;
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $campaign = Campaign::find($request->{'_campaign'});

        // $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($campaign->url);
    }
}
