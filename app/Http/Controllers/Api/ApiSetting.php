<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;
use Hash;
use Image;

use Marketplace\User;
use Marketplace\UserAddress;

class ApiSetting extends Controller
{	
    public function profile(Request $request)
    {
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
			'user_id' => 'required|integer',
			'name' => 'required|max:255',
			'phone' => 'required|numeric',
			'date_birth' => 'required|date',
			'place_birth' => 'required|integer',
			'bio' => 'required',
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
			// Update Photo
			$photo = $user->photo;
			
			if (!empty($request->photo))
			{
				// Validation
				$validator = Validator::make($request->all(), [
					'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=300,min_height=300'
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
	
				if (empty($responses))
				{
					// Unlink Photo
					if ($user->photo != "default.png") {
						$fileDelete = $public.'uploads/photos/'.$user->photo;
		
						$fileDeleteLarge = $public.'uploads/photos/large-'.$user->photo;
						$fileDeleteMedium = $public.'uploads/photos/medium-'.$user->photo;
						$fileDeleteSmall = $public.'uploads/photos/small-'.$user->photo;
		
						if (file_exists($fileDelete)) { unlink($fileDelete); }
						if (file_exists($fileDeleteLarge)) { unlink($fileDeleteLarge); }
						if (file_exists($fileDeleteMedium)) { unlink($fileDeleteMedium); }
						if (file_exists($fileDeleteSmall)) { unlink($fileDeleteSmall); }
					}
		
					// Upload Photo
					$imageName = md5($user->id.$user->username.$request->photo->getClientOriginalName()).'.'.$request->photo->getClientOriginalExtension();
					$imagePath = $public.'uploads/photos/'.$imageName;
		
					$update = User::find($user->id);
					$update->photo = $imageName;
					$update->save();
		
					$request->photo->move($public.'uploads/photos/', $imageName);
		
					$imageLarge = Image::make($imagePath)->fit(300, 300);
					$imageLarge->save($public.'uploads/photos/large-'.$imageName);
		
					$imageMedium = Image::make($imagePath)->fit(125, 125);
					$imageMedium->save($public.'uploads/photos/medium-'.$imageName);
		
					$imageSmall = Image::make($imagePath)->fit(45, 45);
					$imageSmall->save($public.'uploads/photos/small-'.$imageName);

					$photo = $imageName;
				}
			}

			// Update
			if (empty($responses))
			{
				// Update Data
				$update = User::where('id', $user_id)
					->update([
						'photo' => $photo,
						'name' => $request->name,
						'place_birth' => $request->place_birth,
						'date_birth' => $request->date_birth,
						'phone' => $request->phone,
						'bio' => $request->bio,
						'photo' => $photo,
				]);
				
				// Data
				$item = User::where('id', $user_id)
					->first();

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

				// Merchant Status
				$merchant = 0;
				if (!empty($item->merchant_id))
				{
					$merchant = 1;
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
					'api_msp' => $item->api_msp,
					'api_app' => $item->api_app,

					'created_at' => $created,
					'updated_at' => $updated,
				);

				$responses = array(
					'status_code' => 202,
					'status_message' => 'Updated',
					'items' => $items,
				);
			}
		}

		return response()->json($responses, $responses['status_code']);
	}
	
    public function password(Request $request)
    {
		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|min:8',
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

		// Validation
        if (empty($responses) AND !empty($user))
        {
	        if (empty($responses) AND !Hash::check($request->old_password, $user->password))
	        {
				$responses = array(
			    	'status_code' => 207,
			    	'status_message' => 'Validation Error',
			    	'errors' => array('old_password' => ['Terjadi kesalahan pada Formulir Anda! Harap Masukkan Password Lama dengan benar.']),
			    );
	        }

	        if (empty($responses) AND $request->new_password != $request->confirm_password)
	        {
				$responses = array(
			    	'status_code' => 207,
			    	'status_message' => 'Validation Error',
			    	'errors' => array('new_password' => ['Terjadi kesalahan pada Formulir Anda! Harap masukkan Password Baru dan Konfirmasi Password dengan benar.']),
			    );
	        }
        }

		// Success
        if (empty($responses))
        {
			// Update Password
			$update = User::where('id', $user_id)
				->update([
					'password' => Hash::make($request->new_password),
			]);

			// Data
            $item = User::where('id', $user_id)
                ->first();

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

			// Merchant Status
			$merchant = 0;
			if (!empty($item->merchant_id))
			{
				$merchant = 1;
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
				'api_msp' => $item->api_msp,
				'api_app' => $item->api_app,

				'created_at' => $created,
				'updated_at' => $updated,
			);

			$responses = array(
				'status_code' => 202,
				'status_message' => 'Updated',
				'items' => $items,
			);
        }

        return response()->json($responses, $responses['status_code']);
	}


    public function address(Request $request)
    {
		// Initialization
    	$items = array();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

		// Validation
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
			$lists = UserAddress::where('user_id', $user_id)
				->orderBy('created_at', 'DESC')
				->get();

			foreach ($lists as $item)
			{
				$data = array(
					'id' => $item->id,
					'address_name' => $item->address_name,
					'first_name' => $item->first_name,
					'last_name' => $item->last_name,
					'phone' => $item->phone,
					'provinsi' => array(
						'id' => $item->provinsi->id,
						'name' => $item->provinsi->name,
					),
					'kabupaten' => array(
						'id' => $item->kabupaten->id,
						'name' => $item->kabupaten->name,
					),
					'kecamatan' => array(
						'id' => $item->kecamatan->id,
						'name' => $item->kecamatan->name,
					),
					'desa' => array(
						'id' => $item->desa->id,
						'name' => $item->desa->name,
					),
					'address' => $item->address,
					'postal_code' => $item->postal_code,
				);
	
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
				$data = array_add($data, 'created', $created);
				$data = array_add($data, 'updated', $updated);
	
				$items[] = $data;
			}

			$responses = array(
				'status_code' => 200,
				'status_message' => 'OK',
				'items' => $items,
			);
        }

        return response()->json($responses, $responses['status_code']);
	}
	
    public function createAddress(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'address_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|numeric',
            'provinsi_id' => 'required|integer',
            'kabupaten_id' => 'required|integer',
            'kecamatan_id' => 'required|integer',
            'desa_id' => 'required|integer',
            'address' => 'required',
            'postal_code' => 'required|integer',
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
        
        // Initialization
    	$items = array();
        $user_id = $request->user_id;
        $address_name = $request->address_name;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $phone = $request->phone;
        $provinsi_id = $request->provinsi_id;
        $kabupaten_id = $request->kabupaten_id;
        $kecamatan_id = $request->kecamatan_id;
        $desa_id = $request->desa_id;
        $address = filter_var($request->address, FILTER_SANITIZE_STRING);
        $postal_code = $request->postal_code;

        // Check
        $item = User::where('id', $user_id)
            ->first();

        if (empty($responses) AND empty($item))
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
            // Insert
            $insert = new UserAddress;
            $insert->user_id = $user_id;
            $insert->address_name = $address_name;
            $insert->first_name = $first_name;
            $insert->last_name = $last_name;
            $insert->phone = $phone;
            $insert->provinsi_id = $provinsi_id;
            $insert->kabupaten_id = $kabupaten_id;
            $insert->kecamatan_id = $kecamatan_id;
            $insert->desa_id = $desa_id;
            $insert->address = $address;
            $insert->postal_code = $postal_code;
            $insert->save();

            // Data
            $item = $insert;

    		$data = array(
				'id' => $item->id,
				'address_name' => $item->address_name,
				'first_name' => $item->first_name,
				'last_name' => $item->last_name,
				'phone' => $item->phone,
				'provinsi' => array(
					'id' => $item->provinsi->id,
					'name' => $item->provinsi->name,
				),
				'kabupaten' => array(
					'id' => $item->kabupaten->id,
					'name' => $item->kabupaten->name,
				),
				'kecamatan' => array(
					'id' => $item->kecamatan->id,
					'name' => $item->kecamatan->name,
				),
				'desa' => array(
					'id' => $item->desa->id,
					'name' => $item->desa->name,
				),
				'address' => $item->address,
				'postal_code' => $item->postal_code,
    		);

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
    		$data = array_add($data, 'created', $created);
    		$data = array_add($data, 'updated', $updated);

    		$items[] = $data;

        	$responses = array(
        		'status_code' => 201,
        		'status_message' => 'Created',
        		'items' => $items,
        	);
        }

        return response()->json($responses, $responses['status_code']);
	}
	
    public function updateAddress(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'address_id' => 'required|integer',
            'address_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|numeric',
            'provinsi_id' => 'required|integer',
            'kabupaten_id' => 'required|integer',
            'kecamatan_id' => 'required|integer',
            'desa_id' => 'required|integer',
            'address' => 'required',
            'postal_code' => 'required|integer',
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
        
        // Initialization
    	$items = array();
        $user_id = $request->user_id;
        $address_id = $request->address_id;
        $address_name = $request->address_name;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $phone = $request->phone;
        $provinsi_id = $request->provinsi_id;
        $kabupaten_id = $request->kabupaten_id;
        $kecamatan_id = $request->kecamatan_id;
        $desa_id = $request->desa_id;
        $address = filter_var($request->address, FILTER_SANITIZE_STRING);
        $postal_code = $request->postal_code;

        // Check
		$item = UserAddress::where('id', $address_id)
			->where('user_id', $user_id)
            ->first();

        if (empty($responses) AND empty($item))
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
			// Update
			$update = UserAddress::where('id', $address_id)
				->where('user_id', $user_id)
				->update([
		            'address_name' => $address_name,
		            'first_name' => $first_name,
		            'last_name' => $last_name,
		            'phone' => $phone,
		            'provinsi_id' => $provinsi_id,
		            'kabupaten_id' => $kabupaten_id,
		            'kecamatan_id' => $kecamatan_id,
		            'desa_id' => $desa_id,
		            'address' => $address,
		            'postal_code' => $postal_code,
			]);

            // Data
			$item = UserAddress::where('id', $address_id)
				->where('user_id', $user_id)
				->first();

    		$data = array(
				'id' => $item->id,
				'address_name' => $item->address_name,
				'first_name' => $item->first_name,
				'last_name' => $item->last_name,
				'phone' => $item->phone,
				'provinsi' => array(
					'id' => $item->provinsi->id,
					'name' => $item->provinsi->name,
				),
				'kabupaten' => array(
					'id' => $item->kabupaten->id,
					'name' => $item->kabupaten->name,
				),
				'kecamatan' => array(
					'id' => $item->kecamatan->id,
					'name' => $item->kecamatan->name,
				),
				'desa' => array(
					'id' => $item->desa->id,
					'name' => $item->desa->name,
				),
				'address' => $item->address,
				'postal_code' => $item->postal_code,
    		);

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
    		$data = array_add($data, 'created', $created);
    		$data = array_add($data, 'updated', $updated);

    		$items[] = $data;

        	$responses = array(
        		'status_code' => 202,
        		'status_message' => 'Updated',
        		'items' => $items,
        	);
        }

        return response()->json($responses, $responses['status_code']);
	}
	
    public function deleteAddress(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'address_id' => 'required|integer',
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
        
        // Initialization
    	$items = array();
        $user_id = $request->user_id;
        $address_id = $request->address_id;

        // Check
		$item = UserAddress::where('id', $address_id)
			->where('user_id', $user_id)
            ->first();

        if (empty($responses) AND empty($item))
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
			// Delete
			$delete = UserAddress::where('id', $address_id)
				->where('user_id', $user_id)
				->delete();

        	$responses = array(
        		'status_code' => 202,
        		'status_message' => 'Deleted',
        		'items' => $items,
        	);
        }

        return response()->json($responses, $responses['status_code']);
    }
}
