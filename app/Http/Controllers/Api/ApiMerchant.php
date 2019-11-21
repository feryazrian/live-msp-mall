<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Auth;
use Image;
use Validator;

use Marketplace\Provinsi;
use Marketplace\Kabupaten;
use Marketplace\Kecamatan;
use Marketplace\Desa;
use Marketplace\User;
use Marketplace\UserType;
use Marketplace\Merchant;
use Marketplace\MerchantAddress;
use Marketplace\MerchantFinance;

class ApiMerchant extends Controller
{
    public function status(Request $request)
    {
		// Initialization
        $items = array();
        $status = 0;
        $alert = "Proses Pengisian Formulir";

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
            // Check
            $merchant = Merchant::where('user_id', $user_id)
                ->first();
            
            if (!empty($merchant))
            {
                $status = $merchant->status;
                
                if ($merchant->status == 1)
                {
                    $alert = "Permohonan Menjadi Merchant anda telah di Setujui!";
                }

                if ($merchant->status == 2)
                {
                    $alert = "Maaf, Permohonan Menjadi Merchant anda di Tolak. Harap lakukan Permohonan Ulang!";
                }

                if ($merchant->status == 3)
                {
                    $alert = "Formulir sedang dalam tahap menunggu persetujuan oleh Administrator.";
                }
            }

			// Data
        	$items[] = array(
				'status' => $status,
				'alert' => $alert,
        	);

	    	$responses = array(
	    		'status_code' => 200,
	    		'status_message' => 'OK',
	    		'items' => $items,
	    	);
        }

        return response()->json($responses, $responses['status_code']);
    }
    
    public function join(Request $request)
    {
		// Initialization
        $items = array();

        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'name' => 'required|max:255',
            'phone' => 'required|numeric',
            'place_birth' => 'required|integer',
            'date_birth' => 'required|date',
            'identity_number' => 'required|max:255',
            'identity_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            // Initialization
            $name = $request->name;
            $phone = $request->phone;
            $place_birth = $request->place_birth;
            $date_birth = $request->date_birth;
            $identity_number = $request->identity_number;
            $identity_photo = $request->identity_photo;
            
            // Transaction Update
            //DB::beginTransaction();

            if (!empty($request->identity_photo))
            {
                $directory = 'identities';

                // Upload New Photo
                $identity_photo = md5($user_id).'.'.$request->identity_photo->getClientOriginalExtension();
                $path = $public.'uploads/'.$directory.'/'.$identity_photo;

                $request->identity_photo->move($public.'uploads/'.$directory.'/', $identity_photo);

                // Resize Photo
                $resize = Image::make($path)->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $resize->save($public.'uploads/'.$directory.'/'.$identity_photo);
            }

            // Update
            $update = User::where('id', $user_id)->update([
                'name' => $name,
                'phone' => $phone,
                'place_birth' => $place_birth,
                'date_birth' => $date_birth,
                'identity_name' => $name,
                'identity_number' => $identity_number,
                'identity_photo' => $identity_photo,
            ]);

            //DB::commit();

            // User
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
                
                'identity_name' => $item->identity_name,
                'identity_number' => $item->identity_number,
                'identity_photo' => asset('uploads/'.$directory.'/'.$item->identity_photo),

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

    public function type(Request $request)
    {
		// Initialization
    	$items = array();

        // Lists
    	$lists = UserType::orderBy('id', 'ASC')
			->get();

    	foreach ($lists as $item)
    	{
			$data = array(
				'id' => $item->id,
				'name' => $item->name,
				'slug' => $item->slug,
				'percent' => $item->percent,
	            'content' => $item->content,
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

        return response()->json($responses, $responses['status_code']);
    }
    
    public function store(Request $request)
    {
		// Initialization
        $items = array();
        
        // Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'type_id' => 'required|integer',
            'name' => 'required|max:255',
            'category_id' => 'required|integer',
            'additional_id' => 'required|integer',
            'address' => 'required|max:255',
            'provinsi_id' => 'required|integer',
            'kabupaten_id' => 'required|integer',
            'kecamatan_id' => 'required|integer',
            'desa_id' => 'required|integer',
            'postal_code' => 'required|numeric',
            'checkbox' => 'required',
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
            // Initialization
            $type_id = $request->type_id;
            $name = $request->name;
            $category_id = $request->category_id;
            $additional_id = $request->additional_id;
            $address = $request->address;
            $provinsi_id = $request->provinsi_id;
            $kabupaten_id = $request->kabupaten_id;
            $kecamatan_id = $request->kecamatan_id;
            $desa_id = $request->desa_id;
            $postal_code = $request->postal_code;
            
            // Transaction
            //DB::beginTransaction();

            // Check
            $merchant = Merchant::where('user_id', $user_id)
                ->first();

            if (empty($merchant))
            {
                // Merchant Insert
                $insert = new Merchant;
                $insert->user_id = $user_id;
                $insert->type_id = $type_id;
                $insert->name = $name;
                $insert->category_id = $category_id;
                $insert->additional_id = $additional_id;
                $insert->save();

                // Merchant ID
                $merchant_id = $insert->id;

                // Merchant Address Insert
                $insert = new MerchantAddress;
                $insert->merchant_id = $merchant_id;
                $insert->address = $address;
                $insert->provinsi_id = $provinsi_id;
                $insert->kabupaten_id = $kabupaten_id;
                $insert->kecamatan_id = $kecamatan_id;
                $insert->desa_id = $desa_id;
                $insert->postal_code = $postal_code;
                $insert->save();

                // Merchant Update
                $update = Merchant::where('user_id', $user_id)->update([
                    'address_id' => $insert->id,
                ]);
            }

            if (!empty($merchant))
            {
                // Merchant Update
                $update = Merchant::where('user_id', $user_id)->update([
                    'type_id' => $type_id,
                    'name' => $name,
                    'category_id' => $category_id,
                    'additional_id' => $additional_id,
                ]);

                // Merchant Address Insert
                if(empty($merchant->address_id))
                {  
                    // Insert
                    $insert = new MerchantAddress;
                    $insert->merchant_id = $merchant->merchant_id;
                    $insert->address = $address;
                    $insert->provinsi_id = $provinsi_id;
                    $insert->kabupaten_id = $kabupaten_id;
                    $insert->kecamatan_id = $kecamatan_id;
                    $insert->desa_id = $desa_id;
                    $insert->postal_code = $postal_code;
                    $insert->save();

                    // Merchant Update
                    $update = Merchant::where('user_id', $user_id)->update([
                        'address_id' => $insert->id,
                    ]);
                }

                // Merchant Address Update
                if(!empty($merchant->address_id))
                {
                    $update = MerchantAddress::where('id', $merchant->address_id)->update([
                        'address' => $address,
                        'provinsi_id' => $provinsi_id,
                        'kabupaten_id' => $kabupaten_id,
                        'kecamatan_id' => $kecamatan_id,
                        'desa_id' => $desa_id,
                        'postal_code' => $postal_code,
                    ]);
                }
            }

            // Merchant Update
            $update = User::where('id', $user_id)->update([
                'name' => $name,
                'place_birth' => $kabupaten_id,
            ]);

            //DB::commit();

            // Merchant
            $item = Merchant::where('user_id', $user_id)
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

			// Data
        	$items[] = array(
        		'id' => $item->id,
        		'user_id' => $item->user_id,
                'type' => array(
                    'id' => $item->type->id,
                    'name' => $item->type->name,
                    'slug' => $item->type->slug,
                    'percent' => $item->type->percent,
                    'content' => $item->type->content,
                ),
                'name' => $item->name,
                'category' => array(
                    'id' => $item->category->id,
                    'name' => $item->category->name,
                ),
                'additional_id' => array(
                    'id' => $item->additional->id,
                    'name' => $item->additional->name,
                ),
                
                'address' => array(
                    'address' => $item->address->address,
                    'provinsi' => array(
                        'id' => $item->address->provinsi->id,
                        'name' => $item->address->provinsi->name,
                    ),
                    'kabupaten' => array(
                        'id' => $item->address->kabupaten->id,
                        'name' => $item->address->kabupaten->name,
                    ),
                    'kecamatan' => array(
                        'id' => $item->address->kecamatan->id,
                        'name' => $item->address->kecamatan->name,
                    ),
                    'desa' => array(
                        'id' => $item->address->desa->id,
                        'name' => $item->address->desa->name,
                    ),
                    'postal_code' => $item->address->postal_code,
                ),

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

    public function finance(Request $request)
    {
		// Initialization
        $items = array();

        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
        
        // Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'bank_name' => 'required|max:255',
            'bank_branch' => 'required|max:255',
            'account_number' => 'required|numeric',
            'account_name' => 'required|max:255',
            'npwp_number' => 'required|numeric',
            'npwp_name' => 'required|max:255',
            'npwp_address' => 'required|max:255',
            'npwp_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // Merchant
        if (empty($responses))
        {
            // Initialization
            $bank_name = $request->bank_name;
            $bank_branch = $request->bank_branch;
            $account_number = $request->account_number;
            $account_name = $request->account_name;
            $npwp_number = $request->npwp_number;
            $npwp_name = $request->npwp_name;
            $npwp_address = $request->npwp_address;
            $npwp_photo = $request->npwp_photo;
            
            // Transaction
            //DB::beginTransaction();

            // Check
            $merchant = Merchant::where('user_id', $user_id)
                ->first();
            
            if (empty($merchant))
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
                // Merchant Finance Insert
                if (empty($merchant->finance_id))
                {
                    if (!empty($request->npwp_photo))
                    {
                        $directory = 'npwp';
            
                        // Upload New Photo
                        $npwp_photo = md5($user_id).'.'.$request->npwp_photo->getClientOriginalExtension();
                        $path = $public.'uploads/'.$directory.'/'.$npwp_photo;
            
                        $request->npwp_photo->move($public.'uploads/'.$directory.'/', $npwp_photo);
            
                        // Resize Photo
                        $resize = Image::make($path)->resize(600, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $resize->save($public.'uploads/'.$directory.'/'.$npwp_photo);
                    }

                    // Insert
                    $insert = new MerchantFinance;
                    $insert->merchant_id = $merchant->id;
                    $insert->bank_name = $bank_name;
                    $insert->bank_branch = $bank_branch;
                    $insert->account_number = $account_number;
                    $insert->account_name = $account_name;
                    $insert->npwp_number = $npwp_number;
                    $insert->npwp_name = $npwp_name;
                    $insert->npwp_address = $npwp_address;
                    $insert->npwp_photo = $npwp_photo;
                    $insert->save();

                    // Merchant Update
                    $update = Merchant::where('user_id', $user_id)->update([
                        'finance_id' => $insert->id,
                        'status' => 3,
                    ]);
                }

                // Merchant Finance Update
                if (!empty($merchant->finance_id))
                {
                    if (!empty($request->npwp_photo))
                    {
                        $directory = 'npwp';
            
                        // Upload New Photo
                        $npwp_photo = md5($user_id).'.'.$request->npwp_photo->getClientOriginalExtension();
                        $path = $public.'uploads/'.$directory.'/'.$npwp_photo;
            
                        $request->npwp_photo->move($public.'uploads/'.$directory.'/', $npwp_photo);
            
                        // Resize Photo
                        $resize = Image::make($path)->resize(600, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $resize->save($public.'uploads/'.$directory.'/'.$npwp_photo);
                    }

                    $update = MerchantFinance::where('id', $merchant->finance_id)->update([
                        'bank_name' => $bank_name,
                        'bank_branch' => $bank_branch,
                        'account_number' => $account_number,
                        'account_name' => $account_name,
                        'npwp_number' => $npwp_number,
                        'npwp_name' => $npwp_name,
                        'npwp_address' => $npwp_address,
                        'npwp_photo' => $npwp_photo,
                    ]);

                    // Merchant Update
                    $update = Merchant::where('user_id', $user_id)->update([
                        'status' => 3,
                    ]);
                }

                //DB::commit();

                // Merchant
                $item = Merchant::where('user_id', $user_id)
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
    
                // Data
                $items[] = array(
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'type' => array(
                        'id' => $item->type->id,
                        'name' => $item->type->name,
                        'slug' => $item->type->slug,
                        'percent' => $item->type->percent,
                        'content' => $item->type->content,
                    ),
                    'name' => $item->name,
                    'category' => array(
                        'id' => $item->category->id,
                        'name' => $item->category->name,
                    ),
                    'additional_id' => array(
                        'id' => $item->additional->id,
                        'name' => $item->additional->name,
                    ),

                    'finance' => array(
                        'bank_name' => $item->finance->bank_name,
                        'bank_branch' => $item->finance->bank_branch,
                        'account_number' => $item->finance->account_number,
                        'account_name' => $item->finance->account_name,
                        'npwp_number' => $item->finance->npwp_number,
                        'npwp_name' => $item->finance->npwp_name,
                        'npwp_address' => $item->finance->npwp_address,
                        'npwp_photo' => asset('uploads/'.$directory.'/'.$item->finance->npwp_photo),
                    ),

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
}
