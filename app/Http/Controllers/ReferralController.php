<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;

use Marketplace\Option;
use Marketplace\User;
use Marketplace\PointInstall;

use Cache;

class ReferralController extends Controller
{	
	public function index(Request $request)
	{
		// Initialization
		$username = str_slug($request->username);

        if (empty($username)) {
			// Return Redirect
        	return redirect('/');
		}

		// Check
		$user = User::where('username', $username)
			->first();

        if (empty($user)) {
			// Return Redirect
        	return redirect('/');
		}
		
		// Create Cache
		Cache::forever('referral', $user->id);

		// Return View
		return redirect()
			->route('register');
	}
	public function install(Request $request)
	{
		// Initialization
		$username = str_slug($request->username);

        if (empty($username)) {
			// Return Redirect
        	return redirect('/');
		}

		// Check
		$user = User::where('username', $username)
			->first();

        if (empty($user)) {
			// Return Redirect
        	return redirect('/');
		}

		$user_id = $user->id;

        // Check
        $check = PointInstall::where('user_id', $user_id)
            ->first();
        
        if (empty($check))
        {
            // Plus Point
            $operation = 'update_mspoint';
            $point = 5;

            // Plus
			$response = new MsplifeController;
			$response = $response->update_mspoint($operation, $username, $point);
				
            // Insert
            $insert = new PointInstall;
            $insert->user_id = $user_id;
            $insert->point = $point;
            $insert->save();
		}

		// Link Playstore
		$link_playstore = Option::where('type', 'link-playstore')->first();
		$link_playstore = $link_playstore->content;

		// Return View
		return redirect($link_playstore);
	}
}
