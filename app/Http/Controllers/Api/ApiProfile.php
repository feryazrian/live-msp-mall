<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Marketplace\User;
use Marketplace\UserAuth;
use Marketplace\Blacklist;
use Marketplace\Product;
use Marketplace\TransactionProduct;

use Validator;

class ApiProfile extends Controller
{	
    public function detail(Request $request)
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
		$userId = $request->user_id;

        $user = User::where('id', $userId)
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

			// Location
			$location = null;
			if (!empty($item->kabupaten))
			{
				$location = $item->kabupaten->name;
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
				'location' => $location,
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
	
    public function product(Request $request)
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
        $userId = $request->user_id;

    	$item = User::where('id', $userId)
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
            $lists = Product::where('user_id', $userId)
                ->where('status', 1)
				->get();
				
			foreach ($lists as $item)
			{
				// Location
				$location = null;
				if (!empty($item->user->kabupaten))
				{
					$location = $item->user->kabupaten->name;
				}

				// Product
				$data = array(
					'id' => $item->id,
					'name' => $item->name,
					'photo' => asset('uploads/products/medium-'.$item->productphoto[0]->photo),
					'price' => $item->price,
					'discount' => $item->discount,
					'rating' => $item->rating,
					'review' => $item->review,
					'location' => $location,
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

    public function review(Request $request)
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
        $userId = $request->user_id;

    	$item = User::where('id', $userId)
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
            $reviews = TransactionProduct::where('user_id', $userId)
				->where('status', 5)
				->groupBy('transaction_id')
                ->get();
            
            foreach ($reviews as $list)
            {
                foreach ($list->review_buyer as $item)
                {
                    // Location
                    $location = null;
                    if (!empty($item->transaction->user->kabupaten))
                    {
                        $location = $item->transaction->user->kabupaten->name;
                    }
    
                    // User Detail
                    $dataUser = array(
                        'id' => $item->transaction->user->id,
                        'name' => $item->transaction->user->name,
                        'username' => $item->transaction->user->username,
                        'photo' => asset('uploads/photos/medium-'.$item->transaction->user->photo),
                        'location' => $location,
                    );
                    
                    // Product Review
                    $data = array(
                        'id' => $item->id,
                        'user' => $dataUser,
                        'review' => $item->review,
                        'rating' => $item->rating,
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
            }

        	$responses = array(
        		'status_code' => 200,
        		'status_message' => 'OK',
        		'items' => $items,
        	);
        }

        return response()->json($responses, $responses['status_code']);
    }
}
