<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;

use Marketplace\Provinsi;
use Marketplace\Kabupaten;
use Marketplace\Kecamatan;
use Marketplace\Desa;
use Marketplace\KodePos;
use Marketplace\TransactionProduct;

use Marketplace\User;
use Marketplace\UserAuth;
use Marketplace\Message;

use Marketplace\Events\CounterNotification;
use Marketplace\Jobs\SendTransactionEmail;

use Auth;
use Curl;
use Cache;

class TestController extends Controller
{
	public function curl()
	{
		// Initialization
		$key = '88f62d72d2ebcf359baf6a4ea5554ba8';
		$waybill = '016310002619819';
		$courier = strtolower('JNE');
		$response = null;

		// Check User
		$response = Curl::to('https://pro.rajaongkir.com/api/waybill')
			->withData(array(
				'key' => $key,
				'waybill' => $waybill,
				'courier' => $courier
			))
        	->asJson()
			->post();
				
		if (!empty($response))
		{
			//echo env('RAJAONGKIR_APIKEY');
			// Return Json
			return response()->json($response, 200);
		}
	}
	public function mail()
	{
		// Send Transaction Email
		$transactionProduct = TransactionProduct::where('transaction_id', 173)
			->where('status', '1')
			->groupBy('user_id')
			->get();

		foreach ($transactionProduct as $item)
		{
			dispatch(new SendTransactionEmail(1, $item, $item->user));
		}
	}
	public function pusher()
	{
		// Pusher
		$user = User::where('id', 2)->first();
        event(new CounterNotification(Auth::user()->id));
	}

	public function auto(Request $request)
	{
		$user = User::inRandomOrder()->first();

		if (!empty($user))
		{
			Auth::loginUsingId($user->id);

			$userAuth = new UserAuth;
			$userAuth->type = 'login';
			$userAuth->user_id = $user->id;
			$userAuth->user_ip = $request->ip();
			$userAuth->user_agent = $request->server('HTTP_USER_AGENT');
			$userAuth->save();
		}

		return redirect('/');
	}
	public function distance()
	{
		$apiKey = 'AIzaSyDELjVje1P1QniFTmzGaf5tY0PqX8WrZn8';
		
		// Change address format
		$formattedAddrFrom    = str_replace(' ', '+', 'Kecamatan Kraton, Kabupaten Pasuruan, Jawa Timur, Indonesia 67151');
		$formattedAddrTo     = str_replace(' ', '+', 'Apartemen Educity Pakuwon City, Kota Surabaya, Jawa Timur, Indonesia 60112');

		$unit = 'k';
		
		// Geocoding API request with start address
		$geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
		$outputFrom = json_decode($geocodeFrom);
		if(!empty($outputFrom->error_message)){
			return $outputFrom->error_message;
		}
		
		// Geocoding API request with end address
		$geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
		$outputTo = json_decode($geocodeTo);
		if(!empty($outputTo->error_message)){
			return $outputTo->error_message;
		}
		
		// Get latitude and longitude from the geodata
		$latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
		$longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
		$latitudeTo        = $outputTo->results[0]->geometry->location->lat;
		$longitudeTo    = $outputTo->results[0]->geometry->location->lng;
		
		// Calculate distance between latitude and longitude
		$theta    = $longitudeFrom - $longitudeTo;
		$dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
		$dist    = acos($dist);
		$dist    = rad2deg($dist);
		$miles    = $dist * 60 * 1.1515;
		
		// Convert unit and return distance
		$unit = strtoupper($unit);
		if ($unit == "K") {
			return round($miles * 1.609344, 2).' km';
		} elseif($unit == "M") {
			return round($miles * 1609.344, 2).' meters';
		} else {
			return round($miles, 2).' miles';
		}
	}
	public function index()
	{
		$reviewCount = 1;
		$ratingCount = 5;

		$transactionProductComplete = TransactionProduct::where('product_id', 27)
			->where('status', 5)
			->get();
		
		foreach ($transactionProductComplete as $productReview)
		{
			foreach ($productReview->review_buyer as $reviewBuyer)
			{
				$reviewCount += 1;
				$ratingCount += $reviewBuyer->rating;
			}
		}
		
		$ratingCount = floor($ratingCount / $reviewCount);
	}
	public function api()
	{
		echo '[package name]<br>';
		echo strtolower('id.monspacemall.android');
		echo '<br>[api key]<br>';
		echo md5(strtolower('id.monspacemall.android'));
		echo '<br><br>';

		echo '[debug sha1]<br>';
		echo strtolower('2b8c7338bde8a854c95e92ff1c7b4f1ed9e76bdc');
		echo '<br>[api secret debug]<br>';
		echo md5(strtolower('2b8c7338bde8a854c95e92ff1c7b4f1ed9e76bdc'));
		echo '<br><br>';
		
		echo '[release sha1]<br>';
		echo strtolower('38703371a554131e5b78ab9003cfb5645ded0008');
		echo '<br>[api secret release]<br>';
		echo md5(strtolower('38703371a554131e5b78ab9003cfb5645ded0008'));
		echo '<br><br>';
	}
	public function login()
	{
		// Initialization
		$key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
		$operation = 'login_validation';

		$email = 'user356@gmail.com';
		$password = 'msplifedemo';

		// Check User
		$response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&email='.$email.'&password='.$password)
        	->asJson()
			->get();
		
		// Validation
		if (!empty($response->email))
		{
			//echo $response->email;
			print_r($response);
		}

		if (empty($response->email))
		{
			echo $response->error;
		}
	}
	public function username()
	{
		// Initialization
		$key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
		$operation = 'check_duplicate_username';

		$username = 'user359';

		// Check Username
		$response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&username='.$username)
        	->asJson()
			->get();
		
		// Validation
		if (!empty($response->status))
		{
			echo $response->error;
		}

		if (empty($response->status))
		{
			echo $response->error;
		}
	}
	public function email()
	{
		// Initialization
		$key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
		$operation = 'check_duplicate_email';

		$email = 'user356@gmail.com';

		// Check Email
		$response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&email='.$email)
        	->asJson()
			->get();
		
		// Validation
		print_r($response);
		if (!empty($response->status))
		{
			echo $response->error;
		}

		if (empty($response->status))
		{
			echo $response->error;
		}
	}
	public function cache()
	{
		if (Cache::has('referral')) {
			echo $value = Cache::get('referral');
		}
	}
}
