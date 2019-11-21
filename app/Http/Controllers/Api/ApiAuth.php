<?php

namespace Marketplace\Http\Controllers\Api;

use Marketplace\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Marketplace\Jobs\SendVerificationEmail;

use Marketplace\User;
use Marketplace\UserAuth;
use Marketplace\Blacklist;
use Marketplace\UserProvider;

use Auth;
use Curl;
use Hash;
use Validator;

class ApiAuth extends Controller
{
	use SendsPasswordResetEmails;
	
    public function login(Request $request)
    {
    	$items = array();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails())
        {
        	$items = $validator->errors();

	    	$responses = array(
	    		'status_code' => 207,
	    		'status_message' => 'Validation Error',
	    		'errors' => $items,
	    	);
        }

        $user = User::where('email', $request->email)
        	->first();

        if (empty($responses) AND empty($user))
        {
            // Initialization
            $key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
            $operation = 'login_validation';

            $email = $request->email;
            $password = $request->password;

            // Check MSP User
            $response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&email='.$email.'&password='.$password)
                ->asJson()
                ->get();
            
            // Validation
            if (!empty($response->email))
            {
                // Username
                $response->username = str_slug($response->username);
				$response->username = str_replace('-','_',$response->username);
				
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

            if ($response->status == 2)
            {
                $items = array('activated' => [$response->error]);

				$responses = array(
			    	'status_code' => 207,
			    	'status_message' => 'Validation Error',
			    	'errors' => $items,
			    );
			}

			if (empty($responses) AND empty($response->email))
			{
				$responses = array(
					'status_code' => 203,
					'status_message' => 'Not Found',
					'notif' => 'Identitas tersebut tidak cocok dengan data kami.',
					'items' => $items,
				);
			}
        }

        if (empty($responses) AND !empty($user))
        {
	        if (empty($responses) AND !Hash::check($request->password, $user->password))
	        {
                $items = array('password' => ['Terjadi kesalahan pada Formulir Anda! Harap Masukkan Password Anda dengan benar.']);

				$responses = array(
			    	'status_code' => 207,
			    	'status_message' => 'Validation Error',
			    	'errors' => $items,
			    );
	        }

        	if (empty($responses) AND $user->activated < 1)
        	{
                $items = array('activated' => ['Anda harus mengaktifkan akun Anda sebelum Masuk! Periksa Email Anda untuk Link Aktivasi.']);

				$responses = array(
			    	'status_code' => 207,
			    	'status_message' => 'Validation Error',
			    	'errors' => $items,
			    );
        	}
        }

        if (empty($responses))
        {
        	$item = $user;

            $userAuth = new UserAuth;
            $userAuth->type = 'login';
            $userAuth->user_id = $user->id;
            $userAuth->user_ip = $request->ip();
            $userAuth->user_agent = $request->server('HTTP_USER_AGENT');
            $userAuth->save();

    		$created = array(
    			'human' => $item->created_at->diffForHumans(),
    			'millisecond' => strtotime($item->created_at) * 1000,
    			'created_at' => $item->created_at,
    		);
    		$updated = array(
    			'human' => $item->updated_at->diffForHumans(),
    			'millisecond' => strtotime($item->updated_at) * 1000,
    			'updated_at' => $item->updated_at,
			);

			// Merchant
			$merchant = 0;
			$address = null;

			if (!empty($item->merchant_id))
			{
				// Status
				$merchant = 1;

				// Address
				if (!empty($item->merchant->address_id))
				{
					$address = $item->merchant->address->address.', '.$item->merchant->address->desa->name.', '.$item->merchant->address->kecamatan->name.', '.$item->merchant->address->kabupaten->name.', '.$item->merchant->address->provinsi->name.', '.$item->merchant->address->postal_code;
				}
			}

			// Address
			$address = null;
			if (!empty($item->merchant->address_id))
			{
				$address = $item->merchant->address->address.', '.$item->merchant->address->desa->name.', '.$item->merchant->address->kecamatan->name.', '.$item->merchant->address->kabupaten->name.', '.$item->merchant->address->provinsi->name.', '.$item->merchant->address->postal_code;
			}

			// Data
        	$items[] = array(
        		'id' => $item->id,
        		'name' => $item->name,
        		'username' => $item->username,
                'email' => $item->email,
        		'photo' => asset('uploads/photos/medium-'.$item->photo),
        		'phone' => $item->phone,
        		'place_birth' => $item->place_birth,
        		'date_birth' => $item->date_birth,
        		'bio' => $item->bio,
				'merchant' => $merchant,
				'address' => $address,
        		'api_msp' => $item->api_msp,
        		'api_app' => $item->api_app,

        		'created_at' => $created,
        		'updated_at' => $updated,
        	);

	    	$responses = array(
	    		'status_code' => 200,
	    		'status_message' => 'OK',
	    		'items' => $items,
	    	);
        }

        return response()->json($responses, $responses['status_code']);
    }

    public function register(Request $request)
    {
		// Initialization
    	$items = array();

        $request->username = str_slug($request->username);
        $request->username = str_replace('-','_',$request->username);

		// Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails())
        {
        	$items = $validator->errors();

	    	$responses = array(
	    		'status_code' => 207,
	    		'status_message' => 'Validation Error',
	    		'errors' => $items,
	    	);
		}
		
        // Username Validation
        $blacklist = Blacklist::where('type', 'username')
            ->where('content', $request->username)
            ->first();

        if(empty($responses) AND !empty($blacklist)) {
			$items = array('username' => ['Username yang anda masukkan tidak dapat digunakan! Harap gunakan Username lain.']);

			$responses = array(
				'status_code' => 207,
				'status_message' => 'Validation Error',
				'errors' => $items,
			);
        }

        // Username Check
		// Initialization
		$key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
		$operation = 'check_duplicate_username';

		$username = $request->username;
		$email = $request->email;

		// Check Username
		$response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&username='.$username)
        	->asJson()
			->get();
		
        // Validation Username
		if (empty($responses) AND empty($response->status))
		{
            if (empty($response->error))
            {
                $response->error = 'Silahkan Hubungin Admin Monspace [A]';
			}
			
			$items = array('username' => [$response->error]);

			$responses = array(
				'status_code' => 207,
				'status_message' => 'Validation Error',
				'errors' => $items,
			);
        }
        
		// Initialization
		$operation = 'check_duplicate_email';

		// Check Email
		$response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&email='.$email)
        	->asJson()
			->get();
		
		// Validation Email
		if (empty($responses) AND empty($response->status))
		{
            if (empty($response->error))
            {
                $response->error = 'Silahkan Hubungin Admin Monspace [A]';
			}
			
			$items = array('email' => [$response->error]);

			$responses = array(
				'status_code' => 207,
				'status_message' => 'Validation Error',
				'errors' => $items,
			);
        }

		// Create User
        if (empty($responses))
        {
			$user = new User;
			$user->name = $request->name;
			$user->username = $request->username;
			$user->email = $request->email;
			$user->password = bcrypt($request->password);
			$user->email_token = md5($request->email.'x1O'.$request->name);
			$user->api_app = 1;
			$user->save();

			// Send Email Activation
			dispatch(new SendVerificationEmail($user));

	        $user = User::where('email', $user->email)
	        	->first();

	        $item = $user;

            $userAuth = new UserAuth;
            $userAuth->type = 'register';
            $userAuth->user_id = $user->id;
            $userAuth->user_ip = $request->ip();
            $userAuth->user_agent = $request->server('HTTP_USER_AGENT');
            $userAuth->save();

    		$created = array(
    			'human' => $item->created_at->diffForHumans(),
    			'millisecond' => strtotime($item->created_at) * 1000,
    			'created_at' => $item->created_at,
    		);
    		$updated = array(
    			'human' => $item->updated_at->diffForHumans(),
    			'millisecond' => strtotime($item->updated_at) * 1000,
    			'updated_at' => $item->updated_at,
    		);

			// Merchant
			$merchant = 0;
			$address = null;

			if (!empty($item->merchant_id))
			{
				// Status
				$merchant = 1;

				// Address
				if (!empty($item->merchant->address_id))
				{
					$address = $item->merchant->address->address.', '.$item->merchant->address->desa->name.', '.$item->merchant->address->kecamatan->name.', '.$item->merchant->address->kabupaten->name.', '.$item->merchant->address->provinsi->name.', '.$item->merchant->address->postal_code;
				}
			}

			// Data
	    	$items[] = array(
        		'id' => $item->id,
        		'name' => $item->name,
        		'username' => $item->username,
                'email' => $item->email,
        		'photo' => asset('uploads/photos/medium-'.$item->photo),
        		'phone' => $item->phone,
        		'place_birth' => $item->place_birth,
        		'date_birth' => $item->date_birth,
        		'bio' => $item->bio,
				'merchant' => $merchant,
				'address' => $address,
        		'api_msp' => $item->api_msp,
        		'api_app' => $item->api_app,
	        	
	    		'created_at' => $created,
	    		'updated_at' => $updated,
	    	);

	    	$responses = array(
	    		'status_code' => 200,
	    		'status_message' => 'OK',
                'notif' => 'Selangkah lagi menjadi bagian dari kami! Kami telah mengirim Activation Code, harap segera cek Email Anda.',
	    		'items' => $items,
	    	);
	    }

        return response()->json($responses, $responses['status_code']);
	}
	
    public function social(Request $request)
    {
		// Initialization
		$items = array();
		$notif = null;

        $request->username = str_slug($request->username);
        $request->username = str_replace('-','_',$request->username);

		// Check
		$check = UserProvider::where('provider', $request->provider)
			->where('provider_id', $request->provider_id)
			->first();

		if (!empty($check))
		{
			// Validation
			$validator = Validator::make($request->all(), [
				'provider' => 'required',
				'provider_id' => 'required',
			]);
			
			if ($validator->fails())
			{
				$items = $validator->errors();
	
				$responses = array(
					'status_code' => 207,
					'status_message' => 'Validation Error',
					'errors' => $items,
				);
			}
		}

		if (empty($check))
		{
			// Validation
			$validator = Validator::make($request->all(), [
				'provider' => 'required',
				'provider_id' => 'required',
				'name' => 'required|max:255',
				'username' => 'required|max:255|unique:users',
				'email' => 'required|email|max:255|unique:users',
				'password' => 'required|min:8|confirmed',
			]);

			if ($validator->fails())
			{
				$items = $validator->errors();

				$responses = array(
					'status_code' => 207,
					'status_message' => 'Validation Error',
					'errors' => $items,
				);
			}
			// Username Validation
			$blacklist = Blacklist::where('type', 'username')
				->where('content', $request->username)
				->first();

			if (empty($responses) AND !empty($blacklist)) {
				$items = array('username' => ['Username yang anda masukkan tidak dapat digunakan! Harap gunakan Username lain.']);

				$responses = array(
					'status_code' => 207,
					'status_message' => 'Validation Error',
					'errors' => $items,
				);
			}

			// Username Check
			// Initialization
			$key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
			$operation = 'check_duplicate_username';

			$username = $request->username;
			$email = $request->email;

			// Check Username
			$response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&username='.$username)
				->asJson()
				->get();
			
			// Validation Username
			if (empty($responses) AND empty($response->status))
			{
				if (empty($response->error))
				{
					$response->error = 'Silahkan Hubungin Admin Monspace [A]';
				}

				$items = array('username' => [$response->error]);

				$responses = array(
					'status_code' => 207,
					'status_message' => 'Validation Error',
					'errors' => $items,
				);
			}
			
			// Initialization
			$operation = 'check_duplicate_email';

			// Check Email
			$response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&email='.$email)
				->asJson()
				->get();
			
			// Validation Email
			if (empty($responses) AND empty($response->status))
			{
				if (empty($response->error))
				{
					$response->error = 'Silahkan Hubungin Admin Monspace [A]';
				}
				
				$items = array('email' => [$response->error]);

				$responses = array(
					'status_code' => 207,
					'status_message' => 'Validation Error',
					'errors' => $items,
				);
			}
		}

		// Create User
        if (empty($responses))
        {
			// Check
			$user = UserProvider::where('provider', $request->provider)
				->where('provider_id', $request->provider_id)
				->first();
			
			if (empty($user))
			{
				//DB::beginTransaction();

				// Insert
				$user = new User;
				$user->name = $request->name;
				$user->username = $request->username;
				$user->email = $request->email;
				$user->password = bcrypt($request->password);
				$user->email_token = md5($request->email.'x1O'.$request->name);
				$user->api_app = 1;
				$user->save();

				$user_id = $user->id;

				// Send Email Activation
				dispatch(new SendVerificationEmail($user));

				// Insert Provider
				$provider = new UserProvider;
				$provider->user_id = $user_id;
				$provider->provider_id = $request->provider_id;
				$provider->provider = $request->provider;
				$provider->save();
		
				$provider_id = $provider->id;

				// Update Provider
				$userProvider = User::where('id', $user_id)
					->update([
						'provider_id' => $provider_id
				]);

				//DB::commit();

				// Check
				$user = UserProvider::where('provider', $request->provider)
					->where('provider_id', $request->provider_id)
					->first();

				// Notif
				$notif = 'Selangkah lagi menjadi bagian dari kami! Kami telah mengirim Activation Code, harap segera cek Email Anda.';
			}

	        $item = $user->user;

            $userAuth = new UserAuth;
            $userAuth->type = 'social';
            $userAuth->user_id = $item->id;
            $userAuth->user_ip = $request->ip();
            $userAuth->user_agent = $request->server('HTTP_USER_AGENT');
            $userAuth->save();

    		$created = array(
    			'human' => $item->created_at->diffForHumans(),
    			'millisecond' => strtotime($item->created_at) * 1000,
    			'created_at' => $item->created_at,
    		);
    		$updated = array(
    			'human' => $item->updated_at->diffForHumans(),
    			'millisecond' => strtotime($item->updated_at) * 1000,
    			'updated_at' => $item->updated_at,
    		);

			// Merchant
			$merchant = 0;
			$address = null;

			if (!empty($item->merchant_id))
			{
				// Status
				$merchant = 1;

				// Address
				if (!empty($item->merchant->address_id))
				{
					$address = $item->merchant->address->address.', '.$item->merchant->address->desa->name.', '.$item->merchant->address->kecamatan->name.', '.$item->merchant->address->kabupaten->name.', '.$item->merchant->address->provinsi->name.', '.$item->merchant->address->postal_code;
				}
			}

			// Data
	    	$items[] = array(
        		'id' => $item->id,
        		'name' => $item->name,
        		'username' => $item->username,
                'email' => $item->email,
        		'photo' => asset('uploads/photos/medium-'.$item->photo),
        		'phone' => $item->phone,
        		'place_birth' => $item->place_birth,
        		'date_birth' => $item->date_birth,
        		'bio' => $item->bio,
				'merchant' => $merchant,
				'address' => $address,
				'provider' => array(
					'id' => $item->provider->id,
					'provider' => $item->provider->provider,
					'provider_id' => $item->provider->provider_id,
				),
        		'api_msp' => $item->api_msp,
        		'api_app' => $item->api_app,
	        	
	    		'created_at' => $created,
	    		'updated_at' => $updated,
	    	);

	    	$responses = array(
	    		'status_code' => 200,
	    		'status_message' => 'OK',
                'notif' => $notif,
	    		'items' => $items,
	    	);
	    }

        return response()->json($responses, $responses['status_code']);
	}
	
    public function check(Request $request)
    {
    	$items = array();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails())
        {
        	$items = $validator->errors();

	    	$responses = array(
	    		'status_code' => 207,
	    		'status_message' => 'Validation Error',
	    		'errors' => $items,
	    	);
        }

        $user = User::where('id', $request->user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

        if (empty($responses))
        {
        	$item = $user;

    		$created = array(
    			'human' => $item->created_at->diffForHumans(),
    			'millisecond' => strtotime($item->created_at) * 1000,
    			'created_at' => $item->created_at,
    		);
    		$updated = array(
    			'human' => $item->updated_at->diffForHumans(),
    			'millisecond' => strtotime($item->updated_at) * 1000,
    			'updated_at' => $item->updated_at,
    		);

			// Merchant
			$merchant = 0;
			$address = null;

			if (!empty($item->merchant_id))
			{
				// Status
				$merchant = 1;

				// Address
				if (!empty($item->merchant->address_id))
				{
					$address = $item->merchant->address->address.', '.$item->merchant->address->desa->name.', '.$item->merchant->address->kecamatan->name.', '.$item->merchant->address->kabupaten->name.', '.$item->merchant->address->provinsi->name.', '.$item->merchant->address->postal_code;
				}
			}

			// Data
        	$items[] = array(
        		'id' => $item->id,
        		'name' => $item->name,
        		'username' => $item->username,
                'email' => $item->email,
        		'photo' => asset('uploads/photos/medium-'.$item->photo),
        		'phone' => $item->phone,
        		'place_birth' => $item->place_birth,
        		'date_birth' => $item->date_birth,
        		'bio' => $item->bio,
				'merchant' => $merchant,
				'address' => $address,
        		'api_msp' => $item->api_msp,
        		'api_app' => $item->api_app,

        		'created_at' => $created,
        		'updated_at' => $updated,
        	);

	    	$responses = array(
	    		'status_code' => 200,
	    		'status_message' => 'OK',
	    		'items' => $items,
	    	);
        }

        return response()->json($responses, $responses['status_code']);
    }

    public function forgot(Request $request)
    {
        $items = array();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails())
        {
            $items = $validator->errors();

            $responses = array(
                'status_code' => 207,
                'status_message' => 'Validation Error',
                'errors' => $items,
            );
        }

        $user = User::where('email', $request->email)
            ->first();

        if (empty($responses) AND !empty($user))
        {
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );

            $item = $user;

            $created = array(
                'human' => $item->created_at->diffForHumans(),
                'millisecond' => strtotime($item->created_at) * 1000,
                'created_at' => $item->created_at,
            );
            $updated = array(
                'human' => $item->updated_at->diffForHumans(),
                'millisecond' => strtotime($item->updated_at) * 1000,
                'updated_at' => $item->updated_at,
            );

			// Merchant
			$merchant = 0;
			$address = null;

			if (!empty($item->merchant_id))
			{
				// Status
				$merchant = 1;

				// Address
				if (!empty($item->merchant->address_id))
				{
					$address = $item->merchant->address->address.', '.$item->merchant->address->desa->name.', '.$item->merchant->address->kecamatan->name.', '.$item->merchant->address->kabupaten->name.', '.$item->merchant->address->provinsi->name.', '.$item->merchant->address->postal_code;
				}
			}

			// Data
            $items[] = array(
        		'id' => $item->id,
        		'name' => $item->name,
        		'username' => $item->username,
                'email' => $item->email,
        		'photo' => asset('uploads/photos/medium-'.$item->photo),
        		'phone' => $item->phone,
        		'place_birth' => $item->place_birth,
        		'date_birth' => $item->date_birth,
        		'bio' => $item->bio,
				'merchant' => $merchant,
				'address' => $address,
        		'api_msp' => $item->api_msp,
        		'api_app' => $item->api_app,

                'created_at' => $created,
                'updated_at' => $updated,
            );

            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'notif' => 'Kami sudah mengirim email yang berisi tautan untuk mereset kata sandi Anda!',
                'items' => $items,
            );
        }

        if (empty($responses))
        {
            $responses = array(
                'status_code' => 203,
                'status_message' => 'Not Found',
                'notif' => 'Identitas tersebut tidak cocok dengan data kami.',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
	}
}
