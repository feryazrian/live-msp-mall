<?php

namespace Marketplace\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
use Marketplace\Http\Controllers\MsplifeController;

use Marketplace\Jobs\SendVerificationEmail;

use DB;
use Validator;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Marketplace\AuthAccessToken;
use Marketplace\User;
use Marketplace\UserAuth;
use Laravel\Socialite\Facades\Socialite;
use Marketplace\Otp;
use Marketplace\OtpsVerifiedLog;
use Marketplace\UserActivated;
use Marketplace\UserProvider;

/**
 * @group Authentication
 *
 * API untuk melakukan otorisasi user agar dapat mengakses ke API yang membutuhkan otorisasi berupa token.
 * 
 */
class AuthController extends Controller
{
	use AuthenticatesUsers;
	protected $maxAttempts = 5;
	protected $decayMinutes = 3;

	/**
	 * Determine if the user has too many failed login attempts.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return bool
	 */
	protected function hasTooManyLoginAttempts(Request $request)
	{
		return $this->limiter()->tooManyAttempts(
			$this->throttleKey($request),
			$this->maxAttempts,
			$this->decayMinutes
		);
	}

	/**
	 * Login
	 * Login menggunakan email atau no handphone untuk mendapatkan JWT token. Token yang didapat kemudian digunakan untuk mengakses API yang membutuhkan Authorization berupa token.
	 * Note: Sebelum login pastikan sudah melakukan register sebelumnya
	 * 
	 * @bodyParam username string required Username wajib diisi dan dapat berupa email atau No. Hp. Example: user@mail.com
	 * @bodyParam password string required password wajib diisi dan dapat minimal 8 karakter.
	 * @bodyParam client_id int required Client ID wajib diisi dan default [1 = 'web', 2 = 'mobile', 3 = 'android', 4 = 'ios' ] Example:1
	 * @bodyParam ip string IP address untuk disimpan sebagai log user Example: 127.0.0.1
	 * @bodyParam user_agent string User Agent untuk disimpan sebagai log user Example: Google Chrome
	 * 
	 * @responseFile responses/login.get.json
	 */
	public function authLogin(Request $request)
	{
		$username = $request->username;
		$password = $request->password;
		$clientId = $request->client_id;
		$ip = ($request->ip) ? $request->ip : $request->ip();
		$user_agent = ($request->user_agent) ? $request->user_agent : $request->server('HTTP_USER_AGENT');
		$isValidEmail = filter_var($username, FILTER_VALIDATE_EMAIL);
		// Validation
		if (!$clientId) {
			return response()->api(400, 'Client ID harus diisi');
		}
		if (!$username) {
			return response()->api(400, 'Username harus diisi');
		}
		if (!$password && $isValidEmail) {
			return response()->api(400, 'Password harus diisi');
		}

		// Fetch User
		$user = User::getIfUserExist($username);
		if (!$user && $isValidEmail) {
			$mspLife = new MsplifeController();
			$userMSP = $mspLife->login_validation('login_validation', $username, $password);

			// Validation
			if (!empty($userMSP->email)) {
				// Username
				$userMSP->username = str_slug($userMSP->username);
				$userMSP->username = str_replace('-', '_', $userMSP->username);

				// Create User if exists in MSP Life
				$user = User::savingNewUserMSPLife($userMSP);

				dispatch(new SendVerificationEmail($user));
			}

			// Verify msp life user activation
			if (!empty($userMSP->status) == 2) {
				$errors = array('activated' => [$userMSP->error]);
				return response()->api(400, 'User activation error', null, $errors);
			}

			// Verify msp life user email
			if (empty($userMSP->email)) {
				return response()->api(404, 'Kami tidak dapat menemukan identitas anda dengan data kami.');
			}
		}

		if ($user) {
			if ($this->hasTooManyLoginAttempts($request)) {
				$this->fireLockoutEvent($request);
				return response()->api(401, 'Terlalu banyak percobaan untuk masuk. Silahkan coba kembali dalam waktu ' . $this->decayMinutes . ' menit');
			}

			// Verify the password
			if (!Hash::check($password, $user->password)) {
				$this->incrementLoginAttempts($request);
				return response()->api(401, 'Terjadi kesalahan pada Formulir Anda! Harap Masukkan Password Anda dengan benar.');
			}

			// Verify user activated
			if ($user->activated < 1) {
				return response()->api(401, 'Anda harus mengaktifkan akun Anda sebelum Masuk! Periksa Email Anda untuk Link Aktivasi.');
			}

			// Generate JWT
			$exp = 3600;
			$expires_in = time() + $exp;
			$expires_date = date('Y-m-d H:i:s', $expires_in);
			$jwt = AuthAccessToken::jwtGenerate($user, $expires_in);
			$user->activatedUser = UserActivated::getUserActivatedIfNotExist($user->id);

			// JWT Validation
			if (!$jwt) {
				$errors = ['message' => 'JWT Generated failed', 'code' => 500];
				return response()->api(500, 'Oops!. Terjadi kesalahan pada sistem harap coba kembali', null, $errors);
			}

			DB::beginTransaction();
			try {
				// Saving User Auth
				UserAuth::savingAuthUser('login', $user->id, $ip, $user_agent);

				// Saving Auth Access
				$accessName = 'Personal Access Token';
				$login = AuthAccessToken::savingLog($jwt, $user->id, $clientId, $accessName, $expires_date);

				// Retrieve Access Token
				$items = [
					'token_type'	=> 'Bearer ',
					'access_token' 	=> $jwt,
					'expires_in'	=> $expires_in
				];

				DB::commit();
				return response()->api(200, 'Login Success', $items);
			} catch (\Exception $th) {
				//throw $th;
				$errors = ['message' => $th->getMessage()];
				DB::rollback();
				return response()->api(500, 'Oops!. Terjadi kesalahan pada sistem harap coba kembali', null, $errors);
			}
		} else {
			return response()->api(404, 'Oops! Kami tidak dapat menemukan identitas anda. Mohon lakukan registrasi dahulu.');
		}
	}

	/**
	 * Register
	 * Register berlaku jika user sudah melakukan request OTP dan OTP tersebut telah terverifikasi, kemudian melengkapi data diri sesuai field.
	 * 
	 * @bodyParam email string required Email wajib diisi. Example: user@mail.com
	 * @bodyParam phone string required Phone wajib diisi. Example: 08123456789
	 * @bodyParam name string required Name wajib diisi Nama lengkap user.
	 * @bodyParam password string required Password wajib diisi dan dapat minimal 8 karakter.
	 * @bodyParam birth_date string required Tanggal Lahir wajib diisi. Example: 1991-12-21
	 * @bodyParam birth_place string required Tempat Lahir wajib diisi.
	 * @bodyParam gender string required Jenis Kelamin wajib diisi. Value: [1 => 'Pria', 2 => 'Wanita'] Example: 1
	 * 
	 * @responseFile responses/login.get.json
	 */
	public function authRegister(Request $request)
	{
		// Validations
		$rules = [
			'name'     		=> 'required|min:3',
			'email'    		=> 'required|unique:users,email',
			'phone'    		=> 'required|unique:users,phone',
			'password' 		=> 'required|min:8',
			'gender'		=> 'required',
			'birth_date'	=> 'required',
			'birth_place'	=> 'required'
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			// Validation failed
			return response()->api(400, $validator->messages()->first());
		}

		$clientId = $request->client_id;
		$ip = ($request->ip) ? $request->ip : $request->ip();
		$user_agent = ($request->user_agent) ? $request->user_agent : $request->server('HTTP_USER_AGENT');
		$isValidEmail = filter_var($request->email, FILTER_VALIDATE_EMAIL);

		if (!$clientId) {
			return response()->api(400, 'Client ID harus diisi');
		}

		if (!$isValidEmail) {
			return response()->api(400, 'Email tidak valid');
		}

		$otpCheck = Otp::whereIn('user_provider', [$request->email, $request->phone])->where('used', 1)->where('action', 'register')->orderBy('created_at', 'DESC')->first();
		if (!$otpCheck) {
			return response()->api(400, 'Maaf anda harus melakukan aktivasi OTP terlebih dahulu.');
		}

		// Checking Username
		$username = explode('@', $request->email)[0];
		$checkUsername = User::checkIfUsernameExist($username);
		if ($checkUsername) {
			$username = $username . random_int(0, 999999);
		}

		// Checking User MSPLife
		if (env('APP_ENV') == 'production') {
			$mspLife = new MsplifeController;
			$operation = 'check_duplicate_username';
			$checkUsernameMSPLife = $mspLife->check_duplicate_username($operation, $username);
			// Validation Username
			if (empty($checkUsernameMSPLife->status) && !empty($checkUsernameMSPLife->error)) {
				return response()->api(400, $checkUsernameMSPLife->error);
			}

			$operation = 'check_duplicate_email';
			$checkEmailMSPLife = $mspLife->check_duplicate_email($operation, $request->email);
			// Validation Email
			if (empty($checkEmailMSPLife->status) && !empty($checkEmailMSPLife->error)) {
				return response()->api(400, $checkEmailMSPLife->error);
			}
		}

		DB::beginTransaction();
		try {
			$data = [
				'username'		=> $username,
				'name'			=> $request->name,
				'email'			=> $request->email,
				'phone'			=> $request->phone,
				'password'		=> bcrypt($request->password),
				'birth_date'	=> $request->birth_date,
				'birth_place'	=> $request->birth_place,
				'gender'		=> $request->gender,
			];
			$user = User::registerNewUser($data);

			if (!$user) {
				return response()->api(500, 'Opps! Something when wrong.');
			}

			// Generate JWT
			$exp = 3600;
			$expires_in = time() + $exp;
			$expires_date = date('Y-m-d H:i:s', $expires_in);
			$jwt = AuthAccessToken::jwtGenerate($user, $expires_in);
			$user->activatedUser = UserActivated::getUserActivatedIfNotExist($user->id);

			// JWT Validation
			if (!$jwt) {
				$errors = ['message' => 'JWT Generated failed', 'code' => 500];
				return response()->api(500, 'Oops!. Terjadi kesalahan pada sistem harap coba kembali', null, $errors);
			}

			// Saving User Auth
			UserAuth::savingAuthUser('register', $user->id, $ip, $user_agent);

			// Saving Auth Access
			$accessName = 'Personal Access Token';
			AuthAccessToken::savingLog($jwt, $user->id, $clientId, $accessName, $expires_date);

			// Set Phone Or Email Active
			$emailActive = ($otpCheck->user_provider == $request->email) ? 1 : 0;
			$phoneActive = ($otpCheck->user_provider == $request->phone) ? 1 : 0;
			if ($emailActive == 0) {
				$otpActive = Otp::where('user_provider', $request->email)->where('used', 1)->where('action', 'aktivasi')->orderBy('created_at', 'DESC')->first();
				$emailActive = ($otpActive) ? 1 : 0;
			}
			if ($phoneActive == 0) {
				$otpActive = Otp::where('user_provider', $request->phone)->where('used', 1)->where('action', 'aktivasi')->orderBy('created_at', 'DESC')->first();
				$phoneActive = ($otpActive) ? 1 : 0;
			}

			$activated = [
				'email' 	=> $emailActive,
				'phone' 	=> $phoneActive,
				'subscribed' => 1
			];
			UserActivated::where('user_id', $user->id)->update($activated);

			// Retrieve Access Token
			$items = [
				'token_type'	=> 'Bearer ',
				'access_token' 	=> $jwt,
				'expires_in'	=> $expires_in
			];

			DB::commit();
			return response()->api(200, 'register Success', $items);
		} catch (\Exception $th) {
			//throw $th;
			$errors = ['message' => $th->getMessage()];
			DB::rollback();
			return response()->api(500, 'Oops!. Terjadi kesalahan pada sistem harap coba kembali', null, $errors);
		}
	}

	/**
	 * Social Login
	 * Login / register menggunakan akun social media google atau facebook. API ini digunakan untuk mengenerate link url untuk login ke akun media sosial.
	 * 
	 * @queryParam name string required <b>Default </b>: (facebook, google). Example: facebook
	 * 
	 */
	public function authProvider(Request $request)
	{
		$provider = $request->name ? $request->name : 'google';
		$user = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
		return response()->api(200, 'Provider url generated', $user);
	}

	/**
	 * Register user by Provider and generate JWT to redirect Homepage
	 *
	 * @param object $data
	 * @param string $provider
	 * @param string $clieclientIdnt_id
	 * @param string $ip
	 * @param string $user_agent
	 * @return object
	 */
	public function registerNewUserProvider($data, $provider, $clientId, $ip, $user_agent)
	{
		$userProvider = UserProvider::where('provider_id', $data->id)->first();
		if (!$userProvider || !$userProvider->user) {
			// Checking Username
			$username = ($data->nickname) ? $data->nickname : explode('@', $data->email)[0];
			$checkUsername = User::checkIfUsernameExist($username);
			if ($checkUsername) {
				$username = $username . random_int(0, 999999);
			}
			$user = User::where('email', $data->email)->first();

			DB::beginTransaction();
			try {
				if (!$user) {
					// Saving New User Data
					$data = [
						'username'		=> $username,
						'name'			=> $data->name,
						'email'			=> $data->email,
						'email_token'	=> md5($data->email . 'x1O' . $data->name)
					];
					$user = User::registerNewUser($data);
				}

				// Saving Provider
				$dataProvider = [
					'user_id'		=> $user->id,
					'provider_id'	=> $data->id,
					'provider'		=> $provider
				];
				UserProvider::create($dataProvider);
				DB::commit();
			} catch (Exception $th) {
				//throw $th;
				DB::rollback();
				return false;
			}
		}
		if (!$user) {
			$user = $userProvider->user;
		}

		// Generate JWT
		$exp = 3600;
		$expires_in = time() + $exp;
		$expires_date = date('Y-m-d H:i:s', $expires_in);
		$jwt = AuthAccessToken::jwtGenerate($user, $expires_in);
		$user->activatedUser = UserActivated::getUserActivatedIfNotExist($user->id);

		// JWT Validation
		if (!$jwt) {
			return false;
		}

		DB::beginTransaction();
		try {
			// Saving User Auth
			UserAuth::savingAuthUser('provider', $user->id, $ip, $user_agent);

			// Saving Auth Access
			$accessName = 'Personal Access Token';
			AuthAccessToken::savingLog($jwt, $user->id, $clientId, $accessName, $expires_date);

			// Set Phone Or Email Active
			$activated = [
				'email' 	=> 1,
			];
			UserActivated::where('user_id', $user->id)->update($activated);

			DB::commit();
			return $jwt;
		} catch (\Exception $th) {
			//throw $th;
			DB::rollback();
			return false;
		}
	}

	/**
	 * Social Login Facebook Callback
	 * Callback untuk menerima data user ketika berhasil login dari Facebook.
	 */
	public function authProviderFacebookCallback(Request $request)
	{
		$data = Socialite::driver('facebook')->stateless()->user();
		$clientId = ($request->client_id) ? $request->client_id : 0;
		$ip = ($request->ip) ? $request->ip : $request->ip();
		$user_agent = ($request->user_agent) ? $request->user_agent : $request->server('HTTP_USER_AGENT');
		$provider = 'facebook';
		if (!$data) {
			return redirect()->route('home')->with(['args' => null]);
		}
		$register = $this->registerNewUserProvider($data, $provider, $clientId, $ip, $user_agent);
		if (!$register) {
			return redirect()->route('home')->with(['args' => null]);
		}
		return redirect()->route('home')->with(['args' => $register]);
	}

	/**
	 * Social Login Google Callback
	 * Callback untuk menerima data user ketika berhasil login dari Google.
	 */
	public function authProviderGoogleCallback(Request $request)
	{
		$data = Socialite::driver('google')->stateless()->user();
		$clientId = ($request->client_id) ? $request->client_id : 0;
		$ip = ($request->ip) ? $request->ip : $request->ip();
		$user_agent = ($request->user_agent) ? $request->user_agent : $request->server('HTTP_USER_AGENT');
		$provider = 'google';
		if (!$data) {
			return redirect()->route('home')->with(['args' => null]);
		}
		$register = $this->registerNewUserProvider($data, $provider, $clientId, $ip, $user_agent);
		if (!$register) {
			return redirect()->route('home')->with(['args' => null]);
		}
		return redirect()->route('home')->with(['args' => $register]);
	}


	/**
	 * Validation Request / Verify OTP code
	 *
	 * @param string $type
	 * @param string $action
	 * @param string $user
	 * @return object
	 */

	public function otpValidationRules($type, $action, $user)
	{
		$action = strtolower($action);
		$user = strtolower($user);

		$actionList = ['register', 'aktivasi', 'reset'];
		$actionCheck = in_array($action, $actionList);
		if (!$actionCheck) {
			return response()->api(400, 'Action not found');
		}

		$isValidEmail = Otp::isValidEmail($user);
		if (!$type) {
			$type = ($isValidEmail) ? 120 : 252;
		}
		$typeVal = ($type == 120) ? 'Email' : 'No. HP';

		if ($type == 252) {
			$phoneValid = Otp::isValidPhone($user);
			return response()->api(400, 'Oops! Sorry, saat ini kode OTP menggunakan no HP belum tersedia :)');
			if (!$phoneValid) {
				return response()->api(400, 'Format ' . $typeVal . ' yang anda masukkan tidak benar.');
			}
		} else {
			if (!$isValidEmail) {
				return response()->api(400, 'Format ' . $typeVal . ' yang anda masukkan tidak benar.');
			}
		}

		$userData = User::getIfUserExist($user);
		switch ($action) {
			case 'register':
				if ($userData) {
					return response()->api(400, $typeVal . ' yang anda masukkan sudah terdaftar.');
				}
				break;
			case 'reset':
				if (!$userData) {
					return response()->api(400, $typeVal . ' yang anda masukkan tidak terdaftar.');
				}
				break;
			default:
				break;
		}

		return response()->api(200, 'Valid data', ['type' => $type, 'action' => $action, 'user' => $user]);
	}

	/**
	 * Request OTP (One Time Password)
	 * API ini digunakan untuk melakukan generate kode OTP yang akan dikirimkan ke email / No. Hp user.
	 * 
	 * @bodyParam action string required <b>Default </b>: (register, aktivasi, reset). Example: register
	 * @bodyParam user string required Dapat berupa email atau No.Hp. Example: user@mail.com
	 * @bodyParam type int Type sebagai penanda user provider yang digunakan email / No. HP: 120 = email, 252 = phone. Example: 120
	 * 
	 * @response {
	 *     "code": 201,
	 *     "status": "OK",
	 *     "message": "Kode OTP berhasil dibuat dan telah dikirim ke user@mail.com"
	 * }
	 */
	public function authOTPRequest(Request $request)
	{
		// Validations
		$rules = [
			'action'  => 'required',
			'user'    => 'required',
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			// Validation failed
			return response()->api(400, $validator->messages()->first());
		}

		$validation = $this->otpValidationRules($request->type, $request->action, $request->user);
		if ($validation->original['code'] == 400) {
			// Validation failed
			return $validation;
		}

		$action = $validation->original['items']['action'];
		$user = $validation->original['items']['user'];
		$type = $validation->original['items']['type'];

		// Blocking if user attemp otp request > 5
		$blockRequest = Otp::blockOtpRequest($user, $action);
		if ($blockRequest) {
			return response()->api(400, 'Maaf. Anda sudah meminta kode otp untuk '. $action .' lebih dari 5x. Silahkan coba kembali dalam waktu 1x24 Jam.');
		}

		try {
			// Request Generate new OTP
			$otpRes = Otp::requestOtp($user, $type, $action);
			if (!empty($otpRes->timer) && $otpRes->status == 400) {
				return response()->api($otpRes->status, $otpRes->message, null, ['timer' => $otpRes->timer]);
			}
			return response()->api($otpRes->status, $otpRes->message);
		} catch (Exception $th) {
			return response()->api(500, $th->getMessage());
		}
	}

	/**
	 * Verify OTP (One Time Password)
	 * API ini digunakan untuk untuk memvalidasi kode otp yang diterima oleh user dengan yang ada di sistem.
	 * 
	 * @bodyParam action string required <b>Default </b>: (register, aktivasi, reset). Example: register
	 * @bodyParam user string required Dapat berupa email atau No.Hp. Example: user@mail.com
	 * @bodyParam otp int required Parameter otp hanya dibutuhkan ketika action <b>verify</b>. Example: 123456
	 * @bodyParam type int Type sebagai penanda user provider yang digunakan email / No. HP: 120 = email, 252 = phone. Example: 120
	 * 
	 * @response {
	 *     "code": 200,
	 *     "status": "OK",
	 *     "message": "Kode OTP berhasil divalidasi"
	 * }
	 */
	public function authOTPVerify(Request $request)
	{
		// Validations
		$rules = [
			'action'  	=> 'required',
			'user'    	=> 'required',
			'otp'		=> 'required'
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			// Validation failed
			return response()->api(400, $validator->messages()->first());
		}

		$validation = $this->otpValidationRules($request->type, $request->action, $request->user);
		if ($validation->original['code'] == 400) {
			// Validation failed
			return $validation;
		}

		$action = $validation->original['items']['action'];
		$user = $validation->original['items']['user'];
		$type = $validation->original['items']['type'];
		$otpVal = $request->otp;

		// Blocking if user attemp otp verify > 5
		$blockVerify = OtpsVerifiedLog::blockOtpVerify($user, $action);
		if ($blockVerify) {
			return response()->api(400, 'Maaf. Anda sudah memncoba verifikasi otp untuk '. $action .' lebih dari 5x. Silahkan coba kembali dalam waktu 1x24 Jam.');
		}

		try {
			// Verify and validate OTP Code
			$otpRes = Otp::verifyOtp($user, $otpVal, $type, $action);
			return response()->api($otpRes->status, $otpRes->message);
		} catch (Exception $th) {
			return response()->api(500, $th->getMessage());
		}
	}

	/**
	 * Logout
	 * Logout untuk menghapus dan menonaktifkan jwt user
	 */
	public function authLogout(Request $request)
	{
		$token = $request->bearerToken();
		$ip = ($request->ip) ? $request->ip : $request->ip();
		$user_agent = ($request->user_agent) ? $request->user_agent : $request->server('HTTP_USER_AGENT');
		// Decode Token and get user
		$decoded = AuthAccessToken::jwtDecode($token);
		$diffTime = $decoded->exp - time();
		if ($diffTime < 1) {
			return response()->api(401, 'Token sudah kadaluarsa');
		}

		$userId = decrypt($decoded->jti);
		$user = User::find($userId);
		if (!$user) {
			return response()->api(401, 'Invalid authorization user');
		}

		DB::beginTransaction();
		try {
			// Logout
			Auth::logout();
			$request->session()->flush();
			$request->session()->regenerate();

			// Set old token expiry
			AuthAccessToken::where('id', $token)->update(['expires_at' => date('Y-m-d H:i:s')]);

			// Saving User Auth
			UserAuth::savingAuthUser('Logout', $user->id, $ip, $user_agent);

			DB::commit();
			return response()->api(200, 'Logout successful');
		} catch (Exception $th) {
			//throw $th;
			$errors = ['message' => $th->getMessage()];
			DB::rollback();
			return response()->api(500, 'Oops!. Terjadi kesalahan pada sistem harap coba kembali', null, $errors);
		}
	}

	/**
	 * Refresh
	 * Refresh token untuk mengenerate token baru dan mengexpired token lama
	 * 
	 * @bodyParam client_id int required Client ID wajib diisi dan default [1 = 'web', 2 = 'mobile', 3 = 'android', 4 = 'ios' ] Example:1
	 * @bodyParam ip string IP address untuk disimpan sebagai log user Example: 127.0.0.1
	 * @bodyParam user_agent string User Agent untuk disimpan sebagai log user Example: Google Chrome
	 */
	public function authRefresh(Request $request)
	{
		$token = $request->bearerToken();
		$clientId = $request->client_id;
		$ip = ($request->ip) ? $request->ip : $request->ip();
		$user_agent = ($request->user_agent) ? $request->user_agent : $request->server('HTTP_USER_AGENT');
		if (!$clientId) {
			return response()->api(400, 'Client ID harus diisi');
		}

		$decoded = AuthAccessToken::jwtDecode($token);
		$diffTime = $decoded->exp - time();
		if ($diffTime < 1) {
			return response()->api(401, 'Token sudah kadaluarsa');
		}

		$userId = decrypt($decoded->jti);
		$user = User::find($userId);
		if (!$user) {
			return response()->api(401, 'Invalid authorization user');
		}

		// Generate JWT
		$exp = 3600;
		$expires_in = time() + $exp;
		$expires_date = date('Y-m-d H:i:s', $expires_in);
		$jwt = AuthAccessToken::jwtGenerate($user, $expires_in);

		// JWT Validation
		if (!$jwt) {
			$errors = ['message' => 'JWT Generated failed', 'code' => 500];
			return response()->api(500, 'Oops!. Terjadi kesalahan pada sistem harap coba kembali', null, $errors);
		}

		DB::beginTransaction();
		try {
			// Set old token expiry
			AuthAccessToken::where('id', $token)->update(['expires_at' => date('Y-m-d H:i:s')]);

			// Saving User Auth
			UserAuth::savingAuthUser('Token Refresh', $user->id, $ip, $user_agent);

			// Saving Auth Access
			$accessName = 'Personal Access Token';
			AuthAccessToken::savingLog($jwt, $user->id, $clientId, $accessName, $expires_date);

			// Retrieve Access Token
			$items = [
				'token_type'	=> 'Bearer ',
				'access_token' 	=> $jwt,
				'expires_in'	=> $expires_in
			];

			DB::commit();
			return response()->api(200, 'Token refresh successful', $items);
		} catch (\Exception $th) {
			//throw $th;
			$errors = ['message' => $th->getMessage()];
			DB::rollback();
			return response()->api(500, 'Oops!. Terjadi kesalahan pada sistem harap coba kembali', null, $errors);
		}
	}

	/**
	 * Change Password
	 * Change Password digunakan untuk user yang ingin mengganti password dengan syarat sudah login terlebih dahulu.
	 * 
	 * @bodyParam client_id int required Client ID wajib diisi dan default [1 = 'web', 2 = 'mobile', 3 = 'android', 4 = 'ios' ] Example:1
	 * @bodyParam old_password string required Password Lama wajib diisi min:8 Karakter Example: loremipsum
	 * @bodyParam new_password string required Password Baru wajib diisi min:8 Karakter Example: loremipsum2
	 * @bodyParam ip string IP address untuk disimpan sebagai log user Example: 127.0.0.1
	 * @bodyParam user_agent string User Agent untuk disimpan sebagai log user Example: Google Chrome
	 * 
	 * @response {
	 *     "code": 200,
	 *     "status": "OK",
	 *     "message": "Password anda berhasil di perbaharui."
	 * }
	 */
	public function changePassword(Request $request)
	{
		$oldPassword = $request->old_password;
		$newPassword = $request->new_password;
		$token = $request->bearerToken();
		$clientId = $request->client_id;
		$ip = ($request->ip) ? $request->ip : $request->ip();
		$user_agent = ($request->user_agent) ? $request->user_agent : $request->server('HTTP_USER_AGENT');
		if (!$oldPassword) {
			return response()->api(400, 'Password Lama harus diisi');
		}
		if (!$newPassword) {
			return response()->api(400, 'Password Baru harus diisi');
		}
		if (!$clientId) {
			return response()->api(400, 'Client ID harus diisi');
		}

		// Decode JWT
		$decoded = AuthAccessToken::jwtDecode($token);
		$diffTime = $decoded->exp - time();
		if ($diffTime < 1) {
			return response()->api(401, 'Token sudah kadaluarsa');
		}

		// Get User Detail
		$userId = decrypt($decoded->jti);
		$user = User::find($userId);
		if (!$user) {
			return response()->api(401, 'Invalid authorization user');
		}

		// Validation Password
		if (!Hash::check($oldPassword, $user->password)) {
			$this->incrementLoginAttempts($request);
			return response()->api(401, 'Terjadi kesalahan pada Formulir Anda! Harap Masukkan Password lama Anda dengan benar.');
		}

		DB::beginTransaction();
		try {
			// Update New Password
			$data = [
				'password'	=> bcrypt($newPassword),
			];
			User::where('id', $userId)->update($data);

			// Saving User Auth
			UserAuth::savingAuthUser('Change Password', $userId, $ip, $user_agent);

			DB::commit();
			return response()->api(200, 'Password anda berhasil di perbaharui.');
		} catch (Exception $th) {
			//throw $th;
			$errors = ['message' => $th->getMessage()];
			DB::rollback();
			return response()->api(500, 'Oops!. Terjadi kesalahan pada sistem harap coba kembali', null, $errors);
		}
	}

	/**
	 * Reset Password
	 * Reset Password digunakan untuk user yang ingin mengganti password tapi tidak login dan harus verifikasi OTP terlebih dahulu.
	 * 
	 * @bodyParam client_id int required Client ID wajib diisi dan default [1 = 'web', 2 = 'mobile', 3 = 'android', 4 = 'ios' ] Example:1
	 * @bodyParam email string required Email wajib diisi Example: user@mail.com
	 * @bodyParam old_password string required Password Lama wajib diisi min:8 Karakter Example: loremipsum
	 * @bodyParam new_password string required Password Baru wajib diisi min:8 Karakter Example: loremipsum2
	 * @bodyParam ip string IP address untuk disimpan sebagai log user Example: 127.0.0.1
	 * @bodyParam user_agent string User Agent untuk disimpan sebagai log user Example: Google Chrome
	 * 
	 * @response {
	 *     "code": 200,
	 *     "status": "OK",
	 *     "message": "Password anda berhasil di perbaharui."
	 * }
	 */
	public function resetPassword(Request $request)
	{
		$email = $request->email;
		$oldPassword = $request->old_password;
		$newPassword = $request->new_password;
		$token = $request->bearerToken();
		$clientId = $request->client_id;
		$ip = ($request->ip) ? $request->ip : $request->ip();
		$user_agent = ($request->user_agent) ? $request->user_agent : $request->server('HTTP_USER_AGENT');
		if (!$email) {
			return response()->api(400, 'Email harus diisi');
		}
		if (!$oldPassword) {
			return response()->api(400, 'Password Lama harus diisi');
		}
		if (!$newPassword) {
			return response()->api(400, 'Password Baru harus diisi');
		}
		if (!$clientId) {
			return response()->api(400, 'Client ID harus diisi');
		}

		// Get User Detail
		$user = User::getIfUserExist($email);
		if (!$user) {
			return response()->api(401, 'Maaf kami tidak dapat menemukan user anda');
		}

		// Validation Password
		if (!Hash::check($oldPassword, $user->password)) {
			$this->incrementLoginAttempts($request);
			return response()->api(401, 'Terjadi kesalahan pada Formulir Anda! Harap Masukkan Password lama Anda dengan benar.');
		}

		DB::beginTransaction();
		try {
			// Update New Password
			$data = [
				'password'	=> bcrypt($newPassword),
			];
			User::where('id', $user->id)->update($data);

			// Saving User Auth
			UserAuth::savingAuthUser('Change Password', $user->id, $ip, $user_agent);

			DB::commit();
			return response()->api(200, 'Password anda berhasil di perbaharui.');
		} catch (Exception $th) {
			//throw $th;
			$errors = ['message' => $th->getMessage()];
			DB::rollback();
			return response()->api(500, 'Oops!. Terjadi kesalahan pada sistem harap coba kembali', null, $errors);
		}
	}
}
