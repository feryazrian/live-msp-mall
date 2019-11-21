<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\User;
use Marketplace\PointWelcome;
use Marketplace\PointShare;
use Marketplace\PointReferral;
use Marketplace\PointGame;
use Marketplace\PointBonus;

use Auth;
use Validator;
use Carbon\Carbon;
use Curl;
use QrCode;

class ApiPoint extends Controller
{
	public function point(Request $request)
	{
		// Initialization
    	$items = array();

		// Validation
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

		// Check
		$user_id = $request->user_id;

        $user = User::where('id', $user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

		// Success
        if (empty($responses))
        {
            // Initialization
            $key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
            $operation = 'check_mspoint';
            $point = 0;

            $username = $user->username;
            $url = route('referral', ['username' => $username]);

            // Check Username
            $response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&username='.$username)
                ->asJson()
                ->get();
                
            // Point
            if ($response->status == 1)
            {
                $point = $response->point;
            }

            // QR Code Generate
            $qrcode = QrCode::format('png')
                ->size(300)
                ->generate($url);
            
            $qrcode ='data:image/png;base64, '.base64_encode($qrcode);
            
            // User Data
            $item = $user;

            // Location
            $location = null;
            if (!empty($item->place_birth))
            {
                $location = $item->kabupaten->name;
            }

			$dataUser = array(
				'id' => $item->id,
				'name' => $item->name,
				'username' => $item->username,
				'photo' => asset('uploads/photos/medium-'.$item->photo),
				'location' => $location,
            );
            
            // Data
    		$data = array(
    			'user' => $dataUser,
    			'point' => $point,
    			'qrcode' => $qrcode,
    		);

    		$items[] = $data;
            
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
	}
	public function welcome(Request $request)
	{
		// Initialization
    	$items = array();
        $point = 30;
        $status = 0;

		// Validation
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

		// Check
		$user_id = $request->user_id;

        $user = User::where('id', $user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

		// Success
        if (empty($responses))
        {
            // Check
            $check = PointWelcome::where('user_id', $user_id)
                ->first();
            
            if (!empty($check))
            {
                $status = 1;
            }
               
            // User Data
            $item = $user;

            // Location
            $location = null;
            if (!empty($item->place_birth))
            {
                $location = $item->kabupaten->name;
            }

			$dataUser = array(
				'id' => $item->id,
				'name' => $item->name,
				'username' => $item->username,
				'photo' => asset('uploads/photos/medium-'.$item->photo),
				'location' => $location,
            );
            
            // Data
    		$data = array(
    			'user' => $dataUser,
    			'point' => $point,
    			'status' => $status,
    		);

    		$items[] = $data;
            
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
    }
    
	public function createWelcome(Request $request)
	{
		// Initialization
    	$items = array();
        $point = 30;
        $status = 1;

		// Validation
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

		// User Check
		$user_id = $request->user_id;

        $user = User::where('id', $user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

        // Duplicate Check 
        $duplicate = PointWelcome::where('user_id', $user_id)
            ->first();

        if (empty($responses) AND !empty($duplicate))
        {
            $responses = array(
                'status_code' => 205,
                'status_message' => 'Duplicate Content',
                'items' => $items,
            );
        }

		// Success
        if (empty($responses))
        {
            // Plus Point
            $key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
            $operation = 'update_mspoint';
            $username = $user->username;

            // Plus
            $response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&username='.$username.'&point='.$point)
                ->asJson()
                ->get();
            
            // Insert
            $insert = new PointWelcome;
            $insert->user_id = $user_id;
            $insert->point = $point;
            $insert->save();
            
            // User Data
            $item = $user;

            // Location
            $location = null;

            if (!empty($item->place_birth))
            {
                $location = $item->kabupaten->name;
            }

			$dataUser = array(
				'id' => $item->id,
				'name' => $item->name,
				'username' => $item->username,
				'photo' => asset('uploads/photos/medium-'.$item->photo),
				'location' => $location,
            );
            
            // Data
    		$data = array(
    			'user' => $dataUser,
    			'point' => $point,
    			'status' => $status,
    		);

    		$items[] = $data;
                
            $responses = array(
				'status_code' => 201,
				'status_message' => 'Created',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
    }
	public function share(Request $request)
	{
		// Initialization
    	$items = array();
        $point = 1;

        $title = config('app.name');
        $url = url('/');

		// Validation
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

		// Check
		$user_id = $request->user_id;

        $user = User::where('id', $user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

		// Success
        if (empty($responses))
        {
            // User Data
            $item = $user;

            // Location
            $location = null;
            if (!empty($item->place_birth))
            {
                $location = $item->kabupaten->name;
            }

			$dataUser = array(
				'id' => $item->id,
				'name' => $item->name,
				'username' => $item->username,
				'photo' => asset('uploads/photos/medium-'.$item->photo),
				'location' => $location,
            );
            
            // Data
    		$data = array(
    			'user' => $dataUser,
    			'point' => $point,
    			'facebook' => array(
                    'title' => $title,
                    'url' => $url,
                    'share' => 'https://www.facebook.com/sharer/sharer.php?u='.$url.'&t='.str_limit($title,60),
                ),
    			'whatsapp' => array(
                    'title' => $title,
                    'url' => $url,
                    'share' => 'whatsapp://send?text='.str_replace(' ', '%20', str_limit($title,60)).'%20'.$url,
                ),
    		);

    		$items[] = $data;
            
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
	}
	public function createShare(Request $request)
	{
		// Initialization
    	$items = array();
        $point = 1;

        $title = config('app.name');
        $url = url('/');

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'provider' => 'required',
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

		// Check
		$user_id = $request->user_id;

        $user = User::where('id', $user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

        $provider = $request->provider;

		// Success
        if (empty($responses))
        {
            // Check
            $check = PointShare::where('user_id', $user_id)
                ->where('provider', $provider)
                ->first();
            
            if (empty($check))
            {
                // Plus Point
                $key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
                $operation = 'update_mspoint';
                $username = $user->username;

                // Plus
                $response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&username='.$username.'&point='.$point)
                    ->asJson()
                    ->get();
                
                // Insert
                $insert = new PointShare;
                $insert->user_id = $user_id;
                $insert->point = $point;
                $insert->provider = $provider;
                $insert->save();
            }

            // Return
            if ($provider == 'facebook')
            {
                $share = 'https://www.facebook.com/sharer/sharer.php?u='.$url.'&t='.str_limit($title,60);
            }

            if ($provider == 'whatsapp')
            {
                $share = 'whatsapp://send?text='.str_replace(' ', '%20', str_limit($title,60)).'%20'.$url;
            }

            // User Data
            $item = $user;

            // Location
            $location = null;
            if (!empty($item->place_birth))
            {
                $location = $item->kabupaten->name;
            }

			$dataUser = array(
				'id' => $item->id,
				'name' => $item->name,
				'username' => $item->username,
				'photo' => asset('uploads/photos/medium-'.$item->photo),
				'location' => $location,
            );
            
            // Data
    		$data = array(
    			'user' => $dataUser,
    			'point' => $point,
    			'share' => array(
                    'provider' => $provider,
                    'title' => $title,
                    'url' => $url,
                    'share' => $share,
                )
    		);

    		$items[] = $data;
            
            $responses = array(
				'status_code' => 201,
				'status_message' => 'Created',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
	}
	public function referral(Request $request)
	{
		// Initialization
    	$items = array();
        $point = 5;

		// Validation
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

		// Check
		$user_id = $request->user_id;

        $user = User::where('id', $user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

		// Success
        if (empty($responses))
        {
            // Initialization
            $username = $user->username;
            $url = route('referral.install', ['username' => $username]);

            // QR Code Generate
            $qrcode = QrCode::format('png')
                ->size(300)
                ->generate($url);
            
            $qrcode ='data:image/png;base64, '.base64_encode($qrcode);

            // User Data
            $item = $user;

            // Location
            $location = null;
            if (!empty($item->place_birth))
            {
                $location = $item->kabupaten->name;
            }

			$dataUser = array(
				'id' => $item->id,
				'name' => $item->name,
				'username' => $item->username,
				'photo' => asset('uploads/photos/medium-'.$item->photo),
				'location' => $location,
            );
            
            // Data
    		$data = array(
    			'user' => $dataUser,
    			'point' => $point,
    			'url' => $url,
    			'qrcode' => $qrcode,
    		);

    		$items[] = $data;
            
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
	}
	public function game(Request $request)
	{
		// Initialization
    	$items = array();
        $today = Carbon::now()->format('Y-m-d');
        $point = 5;
        $status = 0;

		// Validation
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

		// User Check
		$user_id = $request->user_id;

        $user = User::where('id', $user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

		// Success
        if (empty($responses))
        {
            // Today
            $check = PointGame::where('user_id', $user_id)
                ->where('date', $today)
                ->orderBy('id', 'ASC')
                ->first();
        
            if (!empty($check))
            {
                $status = 1;
                $point = $check->point;
            }

            // Repeat
            $repeat = PointGame::where('user_id', $user_id)
                ->where('status', 1)
                ->get()
                ->count();
            
            // User Data
            $item = $user;

            // Location
            $location = null;
            if (!empty($item->place_birth))
            {
                $location = $item->kabupaten->name;
            }

			$dataUser = array(
				'id' => $item->id,
				'name' => $item->name,
				'username' => $item->username,
				'photo' => asset('uploads/photos/medium-'.$item->photo),
				'location' => $location,
            );
            
            // Data
    		$data = array(
    			'user' => $dataUser,
    			'point' => $point,
    			'status' => $status,
    			'repeat' => $repeat,
    			'roulette' => asset('images/roulette_straight.png'),
    		);

    		$items[] = $data;
            
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
	}
	public function createGame(Request $request)
	{
		// Initialization
    	$items = array();
        $date = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'point' => 'required|integer',
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

		// User Check
		$user_id = $request->user_id;

        $user = User::where('id', $user_id)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

        // Duplicate Check 
        $today = PointGame::where('user_id', $user_id)
            ->where('date', $date)
            ->first();

        if (empty($responses) AND !empty($today))
        {
            $responses = array(
                'status_code' => 205,
                'status_message' => 'Duplicate Content',
                'items' => $items,
            );
        }

		// Success
        if (empty($responses))
        {
            $point = $request->point;

            // Check Yesterday
            $yesterday = PointGame::where('user_id', $user_id)
                ->where('date', $yesterday)
                ->first();
                
            if (empty($yesterday))
            {
                $update = PointGame::where('user_id', $user_id)->update([
                    'status' => 0,
                ]);
            }

            // Today Point
            if (empty($today))
            {
                // Transaction
                //DB::beginTransaction();

                // Plus Point
                $key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';
                $operation = 'update_mspoint';
                $username = $user->username;

                // Plus
                $response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&username='.$username.'&point='.$point)
                    ->asJson()
                    ->get();
                
                // Insert
                $insert = new PointGame;
                $insert->user_id = $user_id;
                $insert->point = $point;
                $insert->date = $date;
                $insert->status = 1;
                $insert->bonus = 0;
                $insert->save();

                // Repeat
                $repeat = PointGame::where('user_id', $user_id)
                    ->where('status', 1)
                    ->where('bonus', 0)
                    ->get()
                    ->count();

                // Bonus
                $bonus = PointBonus::where('day', $repeat)
                    ->first();

                if (!empty($bonus))
                {
                    // Plus
                    $response = Curl::to('https://mymsplife.com/secure/api/mspmallv2.php?key='.$key.'&operation='.$operation.'&username='.$username.'&point='.$bonus->point)
                        ->asJson()
                        ->get();

                    // Insert
                    $insert = new PointGame;
                    $insert->user_id = $user_id;
                    $insert->point = $bonus->point;
                    $insert->date = $date;
                    $insert->status = 0;
                    $insert->bonus = 1;
                    $insert->save();

                    $point = $point.' + '.$bonus->point;
                }

                // Reset 30 Days
                if ($repeat == 30)
                {
                    $update = PointGame::where('user_id', $user_id)->update([
                        'status' => 0,
                    ]);
                }

                //DB::commit();
            }
            
            // User Data
            $item = $user;

            // Location
            $location = null;

            if (!empty($item->place_birth))
            {
                $location = $item->kabupaten->name;
            }

			$dataUser = array(
				'id' => $item->id,
				'name' => $item->name,
				'username' => $item->username,
				'photo' => asset('uploads/photos/medium-'.$item->photo),
				'location' => $location,
            );
            
            // Data
    		$data = array(
    			'user' => $dataUser,
    			'point' => $point,
    		);

    		$items[] = $data;
                
            $responses = array(
				'status_code' => 201,
				'status_message' => 'Created',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
	}
}
