<?php

namespace Marketplace\Http\Controllers\Auth;

use Marketplace\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

use Marketplace\Jobs\SendVerificationEmail;
use Illuminate\Support\Facades\Crypt;
//use Illuminate\Support\Facades\DB;

use Auth;
use Cache;
use Validator;
use Socialite;

use Marketplace\User;
use Marketplace\UserAuth;
use Marketplace\UserProvider;
use Marketplace\Blacklist;
use Marketplace\Notification;
use Marketplace\PointReferral;
use DirkGroenen\Pinterest\Pinterest;

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
        $data['username'] = str_slug($data['username']);
        $data['username'] = str_replace('-','_',$data['username']);
        
        return Validator::make($data, [
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // Username Filter
        $data['username'] = str_slug($data['username']);
        $data['username'] = str_replace('-','_',$data['username']);

        // Cache Referral
        $referral = null;
        
		if (Cache::has('referral')) {
			$referral = Cache::get('referral');
        }

        // User Create
        $user = new User;
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->email_token = md5($data['email'].'x1O'.$data['name']);
        $user->referral_id = $referral;
        $user->save();

        // Point Referral
		if (!empty($referral)) {

            // Check Referral
            $check = User::where('id', $referral)
                ->first();
            
            if (!empty($check))
            {
                // Plus Point
                $key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
                $operation = 'update_mspoint';
                $point = 5;

                $username = $check->username;
                $user_id = $check->id;
                $referral_id = $user->id;
    
                // Plus
                $response = new \Marketplace\Http\Controllers\MsplifeController;
                $response = $response->update_mspoint($operation, $username, $point);
                    
                // Insert
                $insert = new PointReferral;
                $insert->user_id = $user_id;
                $insert->referral_id = $referral_id;
                $insert->point = $point;
                $insert->save();
            }
		}

        return $user;
    }

    /**
    * Handle a registration request for the application.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function register(Request $request)
    {
        // Validation
        $validator = $this->validator($request->all());

        // Username Validation
        $blacklist = Blacklist::where('type', 'username')
            ->where('content', $request->username)
            ->first();

        if (!empty($blacklist)) {
            return redirect('/register')
                ->with('warning', 'Username yang anda masukkan tidak dapat digunakan! Harap gunakan Username lain.')
                ->withErrors($validator)
                ->withInput();
        }

        // Username Check
        // Username
        $request->username = str_slug($request->username);
        $request->username = str_replace('-','_',$request->username);
        
		// Initialization
		$key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
		$operation = 'check_duplicate_username';
        $error = 'Silahkan Hubungin Admin Monspace [A]';

		$username = $request->username;
		$email = $request->email;

		// Check Username
        $response = new \Marketplace\Http\Controllers\MsplifeController;
        $response = $response->check_duplicate_username($operation, $username);
		
        // Validation Username
		if (empty($response->status))
		{
            if (!empty($response->error))
            {
                $error = $response->error;
            }

            return redirect('/register')
                ->with('warning', $error)
                ->withErrors($validator)
                ->withInput();
        }
        
		// Initialization
		$operation = 'check_duplicate_email';
        $error = 'Silahkan Hubungin Admin Monspace [A]';

		// Check Email
        $response = new \Marketplace\Http\Controllers\MsplifeController;
        $response = $response->check_duplicate_email($operation, $email);
		
		// Validation Email
		if (empty($response->status))
		{
            if (!empty($response->error))
            {
                $error = $response->error;
            }

            return redirect('/register')
                ->with('warning', $error)
                ->withErrors($validator)
                ->withInput();
        }
        
        // Validation
        $this->validator($request->all())->validate();

        // User Create
        $user = $this->create($request->all());

        // Notification
        $notif_welcome = new Notification;
        $notif_welcome->sender_id = 1;
        $notif_welcome->receiver_id = $user->id;
        $notif_welcome->type = 'system_welcome';
        $notif_welcome->save();

        $notif_profile_info = new Notification;
        $notif_profile_info->sender_id = 1;
        $notif_profile_info->receiver_id = $user->id;
        $notif_profile_info->type = 'system_profile_info';
        $notif_profile_info->save();

        // Send Email Activation
        dispatch(new SendVerificationEmail($user));

        // Redirection
        return redirect('login')->with([
            'status' => 'Selangkah lagi menjadi bagian dari kami! Kami telah mengirim Activation Code, harap segera cek Email Anda.',
        ]);
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        // Initialization
        if ($request->is('facebook'))
        {
            $provider = 'facebook';
        }

        if ($request->is('twitter'))
        {
            $provider = 'twitter';
        }

        if ($request->is('google'))
        {
            $provider = 'google';
        }

        // Provider Driver
        if($provider == 'facebook' || $provider == 'twitter' || $provider == 'google')
        {
            return Socialite::driver($provider)->redirect();
        }

        // Return Redirect
        return redirect('/login');
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        // Cancel
        if (!empty($request->error)) {
            if ($request->error == 'access_denied')
            {
                return redirect('/');
            }
        }
        
        // Initialization
        if ($request->is('facebook/*'))
        {
            $provider = 'facebook';
        }

        if ($request->is('twitter/*'))
        {
            $provider = 'twitter';
        }

        if ($request->is('google/*'))
        {
            $provider = 'google';
        }
        
        // Provider Driver
        if($provider == 'facebook' || $provider == 'twitter' || $provider == 'google')
        {
            if(empty($request->tokenprovider))
            {
                try
                {    
                    if($provider == 'twitter')
                    {
                        $user = Socialite::driver($provider)->user();

                        $userToken = $user->token;
                        $userId = $user->getId();
                        $userEmail = $user->getEmail();
                        $userName = $user->getName();
                        $userUsername = $user->getNickname();

                        $userArray = array(
                            'provider' => $provider,
                            'token' => $userToken,
                            'id' => $userId,
                            'email' => $userEmail,
                            'name' => $userName,
                            'username' => $userUsername,
                        );

                        $userEncode = Crypt::encryptString(serialize($userArray));
                        $tokenProvider = $userEncode;
                    }

                    if($provider != 'twitter')
                    {
                        $user = Socialite::driver($provider)->stateless()->user();

                        $tokenProvider = $user->token;
                        $refreshToken = $user->refreshToken; // not always provided
                        $expiresIn = $user->expiresIn;
                    }
                }
                catch (Exception $e)
                {
                    return redirect($provider);
                }

                return redirect($provider.'/'.$tokenProvider);
            }

            $userToken = $request->tokenprovider;

            if($provider == 'twitter')
            {
                $userDecode = Crypt::decryptString($userToken);
                $user = unserialize($userDecode);
                
                $userId = $user['id'];
                $userEmail = $user['email'];
                $userName = $user['name'];
                $userUsername = $user['username'];
            }

            if($provider != 'twitter')
            {
                $user = Socialite::driver($provider)->userFromToken($userToken);
                
                $userId = $user->getId();
                $userEmail = $user->getEmail();
                $userName = $user->getName();
                $userUsername = $user->getNickname();
            }

            $userProvider = UserProvider::where('provider_id', $userId)->first();
            if(!empty($userProvider))
            {
                $userCheck = User::where('provider_id', $userProvider->id)->first();
                if(!empty($userCheck))
                {
                    $userAuth = new UserAuth;
                    $userAuth->type = 'login-'.$userProvider->provider;
                    $userAuth->user_id = $userCheck->id;
                    $userAuth->user_ip = $request->ip();
                    $userAuth->user_agent = $request->server('HTTP_USER_AGENT');
                    $userAuth->save();

                    Auth()->login($userCheck);
                    return redirect('/');
                }
            }

            if (empty($userUsername))
            {
                $userUsername = str_slug($userName);
                $userUsername = str_replace('-','_',$userUsername);
            }

            return view('auth.social')->with([
                'provider' => $provider,
                'userToken' => $userToken,
                'userId' => $userId,
                'userEmail' => $userEmail,
                'userName' => $userName,
                'userUsername' => $userUsername,
            ]);
        }

        // Return Redirect
        return redirect('login');
    }

    public function callbackPinterest(Request $request)
    {
        // Initialization Token
        $pinterest = new Pinterest('4877999456334654667', '8327bfb4b6a6c4fbb7c964c3afd2091e14e1f1d88021e94bd847157093e66af0');

        // Check Token
        if(empty($request->tokenprovider))
        {
            try
            {
                $token = $pinterest->auth->getOAuthToken($request->code);
                $tokenProvider = $token->access_token;
                $pinterest->auth->setOAuthToken($token->access_token);
            }
            catch (Exception $e)
            {
                return redirect('pinterest');
            }

            return redirect('pinterest'.'/'.$tokenProvider);
        }

        // Initialization
        $userToken = $request->tokenprovider;

        $pinterest->auth->setOAuthToken($userToken);
        $me = $pinterest->users->me();

        $userId = $me->id;
        $userEmail = '';
        $userName = $me->first_name.' '.$me->last_name;
        $userUsername = $me->username;

        // Check
        $userProvider = UserProvider::where('provider_id', $userId)->first();
        if(!empty($userProvider))
        {
            $userCheck = User::where('provider_id', $userProvider->id)->first();

            if(!empty($userCheck))
            {
                // Log User
                $userAuth = new UserAuth;
                $userAuth->type = 'login-'.$userProvider->provider;
                $userAuth->user_id = $userCheck->id;
                $userAuth->user_ip = $request->ip();
                $userAuth->user_agent = $request->server('HTTP_USER_AGENT');
                $userAuth->save();

                // User Login
                Auth()->login($userCheck);
                return redirect('/');
            }
        }

        // Return View
        return view('auth.social')->with([
            'provider' => 'pinterest',
            'userToken' => $userToken,
            'userId' => $userId,
            'userEmail' => $userEmail,
            'userName' => $userName,
            'userUsername' => $userUsername,
        ]);
    }

    public function handlePinterest(Request $request)
    {
        // Initialization
        $pinterest = new Pinterest('4877999456334654667', '8327bfb4b6a6c4fbb7c964c3afd2091e14e1f1d88021e94bd847157093e66af0');
        
        $loginurl = $pinterest->auth->getLoginUrl(url('pinterest/callback'), array('read_public'));

        // Return Redirect
        return redirect($loginurl);
    }

    public function submitProvider(Request $request)
    {
        // Validation
        $validator = $this->validator($request->all());

        // Username Validation
        $blacklist = Blacklist::where('type', 'username')
            ->where('content', $request->username)
            ->first();

        if(!empty($blacklist)) {
            return redirect('/'.$request->provider.'/'.$request->provider_token)
                ->with('warning', 'Username yang anda masukkan tidak dapat digunakan! Harap gunakan Username lain.')
                ->withErrors($validator)
                ->withInput();
        }

        // Username Check
        // Username
        $request->username = str_slug($request->username);
        $request->username = str_replace('-','_',$request->username);
        
		// Initialization
		$key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
		$operation = 'check_duplicate_username';
        $error = 'Silahkan Hubungin Admin Monspace [A]';
        
		$username = $request->username;
		$email = $request->email;

		// Check Username
        $response = new \Marketplace\Http\Controllers\MsplifeController;
        $response = $response->check_duplicate_username($operation, $username);
		
        // Validation Username
		if (empty($response->status))
		{
            if (!empty($response->error))
            {
                $error = $response->error;
            }

            return redirect('/'.$request->provider.'/'.$request->provider_token)
                ->with('warning', $error)
                ->withErrors($validator)
                ->withInput();
        }
        
		// Initialization
		$operation = 'check_duplicate_email';

		// Check Email
        $response = new \Marketplace\Http\Controllers\MsplifeController;
        $response = $response->check_duplicate_email($operation, $email);
		
		// Validation Email
		if (empty($response->status))
		{
            if (!empty($response->error))
            {
                $error = $response->error;
            }
            
            return redirect('/'.$request->provider.'/'.$request->provider_token)
                ->with('warning', $error)
                ->withErrors($validator)
                ->withInput();
        }
        
        // Validation
        $this->validator($request->all())->validate();

        //DB::beginTransaction();

        // User Create
        $user_create = $this->create($request->all());

        $user_id = $user_create->id;

        $provider_create = new UserProvider;
        $provider_create->user_id = $user_id;
        $provider_create->provider_id = $request->provider_id;
        $provider_create->provider = $request->provider;
        $provider_create->save();

        $notif_welcome = new Notification;
        $notif_welcome->sender_id = 1;
        $notif_welcome->receiver_id = $user_id;
        $notif_welcome->type = 'system_welcome';
        $notif_welcome->save();

        $notif_profile_info = new Notification;
        $notif_profile_info->sender_id = 1;
        $notif_profile_info->receiver_id = $user_id;
        $notif_profile_info->type = 'system_profile_info';
        $notif_profile_info->save();

        $provider_id = $provider_create->id;
        $userProvider = User::where('id', $user_id)->update(['provider_id' => $provider_id]);

        $user = User::where('provider_id', $provider_id)->first();

        //DB::commit();

        // Send Email Activation
        dispatch(new SendVerificationEmail($user));

        // User Login
        Auth()->login($user);

        // Return Redirect
        return redirect('/');
    }
}
