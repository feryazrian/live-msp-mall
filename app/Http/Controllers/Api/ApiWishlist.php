<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;

use Marketplace\User;
use Marketplace\Wishlist;

class ApiWishlist extends Controller
{	
    public function index(Request $request)
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
			$lists = Wishlist::where('user_id', $user_id)
				->orderBy('created_at', 'DESC')
				->get();

			foreach ($lists as $item)
			{
				// Location
				$location = null;
				if (!empty($item->user->kabupaten))
				{
					$location = $item->user->kabupaten->name;
				}

				// User Detail
				$dataUser = array(
					'id' => $item->user->id,
					'name' => $item->user->name,
					'username' => $item->user->username,
					'photo' => asset('uploads/photos/medium-'.$item->user->photo),
					'location' => $location,
				);
				
				// Product Detail
				$products = array();
				$product = $item->product;

				// Product Location
				$locationProduct = null;
				if (!empty($product->user->kabupaten))
				{
					$locationProduct = $product->user->kabupaten->name;
				}
				
				$dataProduct = array(
					'id' => $product->id,
					'name' => $product->name,
					'photo' => asset('uploads/products/medium-'.$product->productphoto[0]->photo),
					'price' => $product->price,
					'discount' => $product->discount,
					'rating' => $product->rating,
					'review' => $product->review,
					'location' => $locationProduct,
				);

				// Data
				$data = array(
					'id' => $item->id,
					'user' => $dataUser,
					'product' => $dataProduct,
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
	
    public function create(Request $request)
    {
		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
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
        $product_id = $request->product_id;

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

		// Duplicate
		$duplicate = Wishlist::where('user_id', $user_id)
			->where('product_id', $product_id)
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
            // Insert
            $insert = new Wishlist;
            $insert->user_id = $user_id;
            $insert->product_id = $product_id;
			$insert->save();
			
			// Data
			$item = $insert;

			// Location
			$location = null;
			if (!empty($item->user->kabupaten))
			{
				$location = $item->user->kabupaten->name;
			}
			
			// User Detail
			$dataUser = array(
				'id' => $item->user->id,
				'name' => $item->user->name,
				'username' => $item->user->username,
				'photo' => asset('uploads/photos/medium-'.$item->user->photo),
				'location' => $location,
			);
			
			// Product Detail
			$products = array();
			$product = $item->product;

			// Product Location
			$locationProduct = null;
			if (!empty($product->user->kabupaten))
			{
				$locationProduct = $product->user->kabupaten->name;
			}
			
			$dataProduct = array(
				'id' => $product->id,
				'name' => $product->name,
				'photo' => asset('uploads/products/medium-'.$product->productphoto[0]->photo),
				'price' => $product->price,
				'discount' => $product->discount,
				'rating' => $product->rating,
				'review' => $product->review,
				'location' => $locationProduct,
			);

			// Data
			$data = array(
				'id' => $item->id,
				'user' => $dataUser,
				'product' => $dataProduct,
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
	
    public function delete(Request $request)
    {
		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
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
        $product_id = $request->product_id;

		$check = Wishlist::where('user_id', $user_id)
			->where('product_id', $product_id)
			->first();

        if (empty($responses) AND empty($check))
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
			$delete = Wishlist::where('user_id', $user_id)
				->where('product_id', $product_id)
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
