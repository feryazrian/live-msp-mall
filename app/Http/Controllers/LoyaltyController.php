<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\User;

use Auth;
use Hash;
use Validator;

class LoyaltyController extends Controller
{
	public function request()
	{
        // Initialization
        $pageTitle = 'Gabung Member Loyalty';
		
		// Return View
		return view('loyalty.request')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
        ]);
    }
    public function submit(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'username' => 'required|max:255',
			'password' => 'required|min:8',
        ]);

        // Initialization
        $pageTitle = 'Gabung Member Loyalty';
        $name = $request->name;
        $email = $request->email;
        $username = $request->username;
        $password = $request->password;

        // Check
        $check = User::where('id', Auth::user()->id)
            ->where('email', $email)
            ->where('username', $username)
            ->first();
        
        if (empty($check))
        {
            return redirect()
                ->route('loyalty.request');
        }

		if (!Hash::check($request->password, $check->password)) {
			// Return Redirect
			return redirect()
				->route('loyalty.request')
				->with('warning', 'Terjadi kesalahan pada Formulir Anda! Harap Masukkan Password Anda dengan benar.');
		}

        // Transaction
        //DB::beginTransaction();

        // Plus Point
        $operation = 'create_user';

        // Plus
        $response = new MsplifeController;
        $response = $response->create_user($operation, $username, $email, $password, $name);
        
        // Validation Email
        if (!empty($response->status))
        {
            if ($response->status == 3 OR $response->status == 4)
            {
                return redirect()
                    ->route('loyalty.request')
                    ->with(['warning' => $response->notif])
                    ->withInput();
            }

            if ($response->status == 1)
            {
                // Update
                $update = User::where('id', Auth::user()->id)->update([
                    'api_msp' => 1,
                    'api_msp_request' => 1,
                ]);

                // Return Redirect
                return redirect()
                    ->route('loyalty.complete');
            }

            // Return Redirect
            return redirect()
                ->route('loyalty.request')
                ->with(['warning' => $response->notif])
                ->withInput();
        }

        //DB::commit();

        // Return Redirect
        return redirect()
            ->route('loyalty.request')
            ->with('warning', 'Terjadi kesalahan pada Formulir Anda!');
    }

	public function complete()
	{
        // Initialization
        $pageTitle = 'Gabung Member Loyalty';

		// Return View
		return view('loyalty.complete')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
        ]);
    }
}
