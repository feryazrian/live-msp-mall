<?php

namespace Marketplace\Http\Controllers\Auth;

use Marketplace\Http\Controllers\Controller;
use Marketplace\Http\Controllers\LifePointController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Marketplace\Jobs\SendVerificationEmail;

use Marketplace\User;
use Marketplace\UserAuth;
use Marketplace\LifePoint;

use Auth;

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

    protected function login(Request $request)
    {
        // Check
        $user = User::where('email', $request->email)->first();
        

       
        if (!empty($user))
        {
            if($user->activated < 1) { 
                return redirect('login')->with([
                    'warning' => 'Anda harus mengaktifkan akun Anda sebelum Masuk! Periksa Email Anda untuk Link Aktivasi.',
                ]);
            }
            $lifePoint = LifePoint::where('user_id',$user->id)->first();
            if($lifePoint == null){
                $myLifePoint = new LifePointController ;
                $myLifePoint = $myLifePoint->create_new($user);
            }
        }
       

        // MSP Life
        if (empty($user))
        { 
            // Initialization
            $operation = 'login_validation';

            $email = $request->email;
            $password = $request->password;

            // Check MSP User
            $response = new \Marketplace\Http\Controllers\MsplifeController;
            $response = $response->login_validation($operation, $email, $password);
            
            // Validation
            if (empty($response->status))
            {
                return redirect('login')
                    ->with(['warning' => 'Identitas tersebut tidak cocok dengan data kami.'])
                    ->withInput();
            }

            if ($response->status == 2)
            {
                return redirect('login')
                    ->with(['warning' => $response->error])
                    ->withInput();
            }

            if (!empty($response->email))
            {
                // Username
                $response->username = str_slug($response->username);
                $response->username = str_replace('-','_',$response->username);

                // Check Username
                $username_validation = User::where('username', $response->username)->first();
                
                if (!empty($username_validation))
                {
                    return redirect('login')
                        ->with(['warning' => 'Username yang anda masukkan telah di gunakan!'])
                        ->withInput();
                }

                // Check Email
                $email_validation = User::where('email', $response->email)->first();
                
                if (!empty($email_validation))
                {
                    return redirect('login')
                        ->with(['warning' => 'Email yang anda masukkan telah di gunakan!'])
                        ->withInput();
                }

                // Create User
                $user = new User;
                $user->name = $response->name;
                $user->username = $response->username;
                $user->email = $response->email;
                $user->password = bcrypt($response->pass);
                $user->email_token = md5($response->email.'x1O'.$response->name);
                $user->api_msp = 1;
                $user->save();

                $user = User::where('email', $response->email)->first();

                dispatch(new SendVerificationEmail($user));
            }
        }

        // Validation
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
       
        if ($this->attemptLogin($request)) {
            // User Login Log
            $userAuth = new UserAuth;
            $userAuth->type = 'login';
            $userAuth->user_id = $user->id;
            $userAuth->user_ip = $request->ip();
            $userAuth->user_agent = $request->server('HTTP_USER_AGENT');
            $userAuth->save();

            return $this->sendLoginResponse($request);
        }

        
        

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
