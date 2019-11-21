<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Auth;
use Image;
use Validator;
use RajaOngkir;

use Marketplace\Wishlist;
use Marketplace\Product;
use Marketplace\ProductComment;
use Marketplace\ProductPhoto;
use Marketplace\TransactionProduct;
use Marketplace\User;
use Marketplace\Option;
use Marketplace\Kabupaten;
use Marketplace\Condition;
use Marketplace\Category;

class ApiProduct extends Controller
{
    public function detail(Request $request)
    {
		// Initialization
        $items = array();
        $caption = null;
        $wishlist = 0;

		// Validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
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
        $itemId = $request->product_id;
        $userId = $request->user_id;

    	$item = Product::where('id', $itemId)
            ->where('status', 1)
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
                'photo' => asset('uploads/photos/'.$item->user->photo),
                'location' => $location,
            );

            // Product Photos
            $dataPhotos = array();

            foreach ($item->productphoto as $itemPhoto)
            {
                $photo = array(
                    'id' => $itemPhoto->id,
                    'photo' => asset('uploads/products/'.$itemPhoto->photo),
                );
                
                $dataPhotos[] = $photo;
            }
        
            // Product Recomendations
            $dataRecomendations = array();
            $productRecomendation = Product::where('status', 1)
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->limit(6)
                ->get();

            foreach ($productRecomendation as $itemRecomendation)
            {
                // Price
				$priceRecomendation = $itemRecomendation->price;
				$discountRecomendation = $itemRecomendation->discount;

                // Location
                $locationRecomendation = null;
                if (!empty($itemRecomendation->user->kabupaten))
                {
                    $locationRecomendation = $itemRecomendation->user->kabupaten->name;
                }
                
                $recomendation = array(
					'id' => $itemRecomendation->id,
					'name' => $itemRecomendation->name,
					'photo' => asset('uploads/products/medium-'.$itemRecomendation->productphoto[0]->photo),
					'price' => $priceRecomendation,
					'discount' => $discountRecomendation,
					'rating' => $itemRecomendation->rating,
					'review' => $itemRecomendation->review,
					'location' => $locationRecomendation,
                );
                
                $dataRecomendations[] = $recomendation;
            }
        
            // Product Price
            $price = $item->price;
            $discount = $item->discount;
            
            // Auth
            if (!empty($userId))
            {
                // Wishlist
                $wishlistCheck = Wishlist::where('user_id', $userId)
                    ->where('product_id', $itemId)
                    ->first();
                
                if (!empty($wishlistCheck))
                {
                    $wishlist = 1;
                }
            }

            // Point Price
            $point_price = Option::where('type', 'point-price')
                ->first();
            $point_price = $point_price->content;
            
            // Product Point
            if (!empty($item->point))
            {
                $point = $item->point / 100;

                $max = $point * $price;

                $msp = $max / $point_price;

                // Floor Point & Min 1
                $msp_before = $msp;
                $msp = floor($msp);
                if ($msp == 0)
                {
                    if ($msp_before > 0 AND $msp_before < 1)
                    {
                        $msp = 1;
                    }
                }
                $msp_price = $msp * $point_price;

                $total = $price - $msp_price;

                $caption = 'Rp '.number_format($total,0,',','.').' + '.$msp.' MSP';
            }

            // Product Detail
            $data = array(
                'id' => $item->id,
                'name' => $item->name,
                'caption' => $caption,
                'wishlist' => $wishlist,
                'photo' => asset('uploads/products/medium-'.$item->productphoto[0]->photo),
                'price' => $price,
                'discount' => $discount,
                'rating' => $item->rating,
                'review' => $item->review,
                'location' => $location,
                'description' => $item->description,
                'user' => $dataUser,
                'photos' => $dataPhotos,
                'recomendations' => $dataRecomendations,
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
            'product_id' => 'required|integer',
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
        $itemId = $request->product_id;

    	$item = Product::where('id', $itemId)
            ->where('status', 1)
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
            $reviews = TransactionProduct::where('product_id', $itemId)
                ->where('status', 5)
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

    public function shipping(Request $request)
    {
		// Initialization
        $items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'kabupaten_id' => 'required|integer',
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

		// Check Product
        $itemId = $request->product_id;

    	$product = Product::where('id', $itemId)
            ->where('status', 1)
            ->first();

        if (empty($responses) AND empty($product))
        {
            $responses = array(
                'status_code' => 203,
                'status_message' => 'Not Found',
                'items' => $items,
            );
        }

		// Check Location
        $kabupaten_id = $request->kabupaten_id;

		$location = Kabupaten::where('id', $kabupaten_id)
            ->first();
            
        if (empty($responses) AND empty($location))
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
            // Shipping Input
            $kotaAsal = $product->user->kabupaten->name;
            $kotaTujuan = $location->name;
            $shippingweightCount = $product->weight;

            // Shipping Method Check
            // Check Kota Asal
            $dataKota = str_replace('Kab.','Kab',$kotaAsal);
            $dataKota = str_replace('Kabupaten','Kab',$dataKota);
            $dataNamaKota = trim(str_replace('Kota','', str_replace('Kab','',$dataKota)));
            $dataNamaType = str_replace(' ','', str_replace($dataNamaKota,'',$dataKota));

            $kotaAsal = RajaOngkir::Kota()
                ->search('city_name', $dataNamaKota)
                ->search('type', $dataNamaType)
                ->get();

            // Check Kota Tujuan
            $dataKota = str_replace('Kab.','Kab',$kotaTujuan);
            $dataKota = str_replace('Kabupaten','Kab',$dataKota);
            $dataNamaKota = trim(str_replace('Kota','', str_replace('Kab','',$dataKota)));
            $dataNamaType = str_replace(' ','', str_replace($dataNamaKota,'',$dataKota));

            $kotaTujuan = RajaOngkir::Kota()
                ->search('city_name', $dataNamaKota)
                ->search('type', $dataNamaType)
                ->get();

            // Check Cost - JNE
            $dataOngkir = RajaOngkir::Cost([
                'origin'        => $kotaAsal[0]['city_id'], // id kota asal
                'destination'   => $kotaTujuan[0]['city_id'], // id kota tujuan
                'weight'        => $shippingweightCount, // berat satuan gram
                'courier'       => 'jne', // kode kurir pengantar ( jne / tiki / pos )

				'originType'       => 'city', // cakupan lokasi ( city / subdistrict )
				'destinationType'       => 'city', // cakupan lokasi ( city / subdistrict )
            ])->get();

            $ongkirJne = array();
			if (!empty($dataOngkir))
			{
                foreach ($dataOngkir as $ongkirCheck) {
                    foreach ($ongkirCheck['costs'] as $ongkir) {
                        $ongkirName = $ongkirCheck['name'];
                        $ongkirService = $ongkir['service'];
                        $ongkirCostPrice = $ongkir['cost'][0]['value'];
                        $ongkirCostTime = $ongkir['cost'][0]['etd'];

                        $ongkirDescription = 'JNE '.$ongkirService.' ';

                        $ongkirDuration = null;
                        if (!empty($ongkirCostTime)) {
                            $ongkirDescription .= '('.$ongkirCostTime.' Hari Kerja)';
                            $ongkirDuration = $ongkirCostTime.' Hari Kerja';
                        }

                        $ongkirDescription .= ' - Rp '.number_format($ongkirCostPrice,0,",",".");

                        $ongkirJne[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription);

                        // Save to Array
                        $data = array(
                            'name' => 'JNE '.$ongkirService,
                            'duration' => $ongkirDuration,
                            'price' => 'Rp '.number_format($ongkirCostPrice,0,',','.'),
                        );
                        
                        $items[] = $data;
                    }
                }
            }

            // Check Cost - POS
            $dataOngkir = RajaOngkir::Cost([
                'origin'        => $kotaAsal[0]['city_id'], // id kota asal
                'destination'   => $kotaTujuan[0]['city_id'], // id kota tujuan
                'weight'        => $shippingweightCount, // berat satuan gram
                'courier'       => 'pos', // kode kurir pengantar ( jne / tiki / pos )

				'originType'       => 'city', // cakupan lokasi ( city / subdistrict )
				'destinationType'       => 'city', // cakupan lokasi ( city / subdistrict )
            ])->get();

            $ongkirPos = array();
			if (!empty($dataOngkir))
			{
                foreach ($dataOngkir as $ongkirCheck) {
                    foreach ($ongkirCheck['costs'] as $ongkir) {
                        $ongkirName = $ongkirCheck['name'];
                        $ongkirService = $ongkir['service'];
                        $ongkirCostPrice = $ongkir['cost'][0]['value'];
                        $ongkirCostTime = $ongkir['cost'][0]['etd'];

                        $ongkirDescription = 'POS '.$ongkirService.' ';

                        $ongkirDuration = null;
                        if (!empty($ongkirCostTime)) {
                            $ongkirDescription .= '('.$ongkirCostTime.' Hari Kerja)';
                            $ongkirDuration = $ongkirCostTime.' Hari Kerja';
                        }

                        $ongkirDescription .= ' - Rp '.number_format($ongkirCostPrice,0,",",".");

                        $ongkirPos[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription);

                        // Save to Array
                        $data = array(
                            'name' => 'POS '.$ongkirService,
                            'duration' => $ongkirDuration,
                            'price' => 'Rp '.number_format($ongkirCostPrice,0,',','.'),
                        );
                        
                        $items[] = $data;
                    }
                }
            }

            // Check Cost - TIKI
            $dataOngkir = RajaOngkir::Cost([
                'origin'        => $kotaAsal[0]['city_id'], // id kota asal
                'destination'   => $kotaTujuan[0]['city_id'], // id kota tujuan
                'weight'        => $shippingweightCount, // berat satuan gram
                'courier'       => 'tiki', // kode kurir pengantar ( jne / tiki / pos )

				'originType'       => 'city', // cakupan lokasi ( city / subdistrict )
				'destinationType'       => 'city', // cakupan lokasi ( city / subdistrict )
            ])->get();

            $ongkirTiki = array();
			if (!empty($dataOngkir))
			{
                foreach ($dataOngkir as $ongkirCheck) {
                    foreach ($ongkirCheck['costs'] as $ongkir) {
                        $ongkirName = $ongkirCheck['name'];
                        $ongkirService = $ongkir['service'];
                        $ongkirCostPrice = $ongkir['cost'][0]['value'];
                        $ongkirCostTime = $ongkir['cost'][0]['etd'];

                        $ongkirDescription = 'TIKI '.$ongkirService.' ';

                        $ongkirDuration = null;
                        if (!empty($ongkirCostTime)) {
                            $ongkirDescription .= '('.$ongkirCostTime.' Hari Kerja)';
                            $ongkirDuration = $ongkirCostTime.' Hari Kerja';
                        }

                        $ongkirDescription .= ' - Rp '.number_format($ongkirCostPrice,0,",",".");

                        $ongkirTiki[] = array('price' => $ongkirCostPrice, 'description' => $ongkirDescription);

                        // Save to Array
                        $data = array(
                            'name' => 'POS '.$ongkirService,
                            'duration' => $ongkirDuration,
                            'price' => 'Rp '.number_format($ongkirCostPrice,0,',','.'),
                        );
                        
                        $items[] = $data;
                    }
                }
            }
            ////////////////////////

        	$responses = array(
        		'status_code' => 200,
        		'status_message' => 'OK',
        		'items' => $items,
        	);
        }

        return response()->json($responses, $responses['status_code']);
    }

    public function comment(Request $request)
    {
		// Initialization
        $items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
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
        $itemId = $request->product_id;
        $userId = $request->user_id;

    	$item = Product::where('id', $itemId)
            ->where('status', 1)
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
            $lists = ProductComment::where('product_id', $itemId)
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

                // Delete
                $delete = 0;

                // Auth
                if (!empty($userId))
                {
                    if ($userId == $item->user->id)
                    {
                        $delete = 1;
                    }
                }
                
                // Product Comment
                $data = array(
                    'id' => $item->id,
                    'user' => $dataUser,
                    'content' => $item->content,
                    'delete' => $delete,
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
	
    public function createComment(Request $request)
    {
		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'user_id' => 'required|integer',
            'content' => 'required',
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
        $content = $request->content;

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
		$duplicate = ProductComment::where('user_id', $user_id)
			->where('product_id', $product_id)
			->where('content', $content)
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
            $insert = new ProductComment;
            $insert->user_id = $user_id;
            $insert->product_id = $product_id;
            $insert->content = $content;
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

            // Delete
            $delete = 0;

            // Auth
            if (!empty($userId))
            {
                if ($userId == $item->user->id)
                {
                    $delete = 1;
                }
            }
            
            // Product Comment
            $data = array(
                'id' => $item->id,
                'user' => $dataUser,
                'content' => $item->content,
                'delete' => $delete,
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
    
    public function deleteComment(Request $request)
    {
		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|integer',
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
        $comment_id = $request->comment_id;

		$check = ProductComment::where('user_id', $user_id)
			->where('id', $comment_id)
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
			$delete = ProductComment::where('user_id', $user_id)
				->where('id', $comment_id)
				->delete();

        	$responses = array(
        		'status_code' => 202,
        		'status_message' => 'Deleted',
        		'items' => $items,
        	);
		}

		return response()->json($responses, $responses['status_code']);
    }

    
	public function stocked(Request $request)
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
            // Lists
            $products = Product::where('user_id', $userId)
                ->where('stock', '>', 0)
                ->orderBy('updated_at', 'desc')
                ->get();
            
			foreach ($products as $item)
			{
                switch ($item->status) {
                    case 1:
                        $status = array(
                            'id' => $item->status,
                            'name' => $item->action->name.' Disetujui',
                            'color' => '#40BE65',
                        );
                        break;

                    case 2:
                        $status = array(
                            'id' => $item->status,
                            'name' => $item->action->name.' Ditolak',
                            'color' => '#E93F2E',
                        );
                        break;
                    
                    default:
                        $status = array(
                            'id' => $item->status,
                            'name' => $item->action->name.' Menunggu',
                            'color' => '#FFCB3C',
                        );
                        break;
                }

				$data = array(
					'id' => $item->id,
					'name' => $item->name,
					'photo' => asset('uploads/products/medium-'.$item->productphoto[0]->photo),
					'price' => $item->price,
					'discount' => $item->discount,
					'stock' => $item->stock,
					'sold' => $item->sold,
					'rating' => $item->rating,
					'review' => $item->review,
					'location' => $item->user->kabupaten->name,
					'status' => $status,
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
    
	public function stockless(Request $request)
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
            // Lists
            $products = Product::where('user_id', $userId)
                ->where('stock', '<=', 0)
                ->orderBy('updated_at', 'desc')
                ->get();
            
			foreach ($products as $item)
			{
                switch ($item->status) {
                    case 1:
                        $status = array(
                            'id' => $item->status,
                            'name' => $item->action->name.' Disetujui',
                            'color' => '#40BE65',
                        );
                        break;

                    case 2:
                        $status = array(
                            'id' => $item->status,
                            'name' => $item->action->name.' Ditolak',
                            'color' => '#E93F2E',
                        );
                        break;
                    
                    default:
                        $status = array(
                            'id' => $item->status,
                            'name' => $item->action->name.' Menunggu',
                            'color' => '#FFCB3C',
                        );
                        break;
                }

				$data = array(
					'id' => $item->id,
					'name' => $item->name,
					'photo' => asset('uploads/products/medium-'.$item->productphoto[0]->photo),
					'price' => $item->price,
					'discount' => $item->discount,
					'stock' => $item->stock,
					'sold' => $item->sold,
					'rating' => $item->rating,
					'review' => $item->review,
					'location' => $item->user->kabupaten->name,
					'status' => $status,
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
    
	public function createPhoto(Request $request)
	{
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
        
		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        // Photo Check
        $photos = ProductPhoto::whereNull('product_id')
            ->where('user_id', $userId)
            ->get()
            ->count();
    
        if (empty($responses) AND $photos > 4)
        {
            $responses = array(
                'status_code' => 207,
                'status_message' => 'Validation Error',
                'errors' => array('photo' => ['Maaf, Anda hanya dapat mengunggah Maksimal 5 Foto.']),
            );
		}

		// Success
        if (empty($responses))
        {
            //  Initialization
            $username = $user->username;

            // Upload
            $imageName = md5(str_random(10).$userId.$username.$request->photo->getClientOriginalName()).'.'.$request->photo->getClientOriginalExtension();
            $imagePath = $public.'uploads/products/'.$imageName;
            $request->photo->move($public.'uploads/products', $imageName);

            $imageLarge = Image::make($imagePath)->fit(400, 400);
            $imageLarge->save($public.'uploads/products/large-'.$imageName);

            $imageMedium = Image::make($imagePath)->fit(225, 225);
            $imageMedium->save($public.'uploads/products/medium-'.$imageName);

            $imageSmall = Image::make($imagePath)->fit(135, 135);
            $imageSmall->save($public.'uploads/products/small-'.$imageName);
            
            // Insert
            $insert = new ProductPhoto;
            $insert->user_id = $userId;
            $insert->photo = $imageName;
            $insert->save();

            // Check
            $item = ProductPhoto::where('id', $insert->id)
                ->first();
        
            $data = array(
                'id' => $item->id,
                'user_id' => $item->user_id,
                'product_id' => $item->product_id,
                'photo' => asset('uploads/products/medium-'.$item->photo),
                'name' => $item->photo,
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
	public function createEditPhoto(Request $request)
	{
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
        
		// Initialization
        $items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'user_id' => 'required|integer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        
		// Product Check
        $productId = $request->product_id;

        $item = Product::where('id', $productId)
            ->where('user_id', $userId)
            ->first();

        if (empty($responses) AND empty($item))
        {
            $responses = array(
                'status_code' => 203,
                'status_message' => 'Not Found',
                'items' => $items,
            );
        }

        // Photo Check
        $photos = ProductPhoto::where('product_id', $productId)
            ->where('user_id', $userId)
            ->get()
            ->count();
    
        if (empty($responses) AND $photos > 4)
        {
            $responses = array(
                'status_code' => 207,
                'status_message' => 'Validation Error',
                'errors' => array('photo' => ['Maaf, Anda hanya dapat mengunggah Maksimal 5 Foto.']),
            );
		}

		// Success
        if (empty($responses))
        {
            //  Initialization
            $username = $user->username;

            // Upload Image
            $imageName = md5(str_random(10).$userId.$username.$request->photo->getClientOriginalName()).'.'.$request->photo->getClientOriginalExtension();
            $imagePath = $public.'uploads/products/'.$imageName;
            $request->photo->move($public.'uploads/products', $imageName);

            $imageLarge = Image::make($imagePath)->fit(400, 400);
            $imageLarge->save($public.'uploads/products/large-'.$imageName);

            $imageMedium = Image::make($imagePath)->fit(225, 225);
            $imageMedium->save($public.'uploads/products/medium-'.$imageName);

            $imageSmall = Image::make($imagePath)->fit(135, 135);
            $imageSmall->save($public.'uploads/products/small-'.$imageName);

            // Insert
            $insert = new ProductPhoto;
            $insert->product_id = $productId;
            $insert->user_id = $userId;
            $insert->photo = $imageName;
            $insert->save();
		
            // Update
            $update = Product::where('id', $productId)->update([
                'status' => 0,
                'action_id' => 2,
            ]);

            // Check
            $item = ProductPhoto::where('id', $insert->id)
                ->first();
        
            $data = array(
                'id' => $item->id,
                'user_id' => $item->user_id,
                'product_id' => $item->product_id,
                'photo' => asset('uploads/products/medium-'.$item->photo),
                'name' => $item->photo,
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
    
	public function deletePhoto(Request $request)
	{
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
        
		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'photo' => 'required',
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
        
		// Product Check
		$fileName = $request->photo;

        $item = ProductPhoto::where('photo', $fileName)
            ->where('user_id', $userId)
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
            // Unlink
            $fileDelete = $public.'uploads/products/'.$fileName;
            $fileDeleteLarge = $public.'uploads/products/large-'.$fileName;
            $fileDeleteMedium = $public.'uploads/products/medium-'.$fileName;
            $fileDeleteSmall = $public.'uploads/products/small-'.$fileName;

            if (file_exists($fileDelete)) { unlink($fileDelete); }
            if (file_exists($fileDeleteLarge)) { unlink($fileDeleteLarge); }
            if (file_exists($fileDeleteMedium)) { unlink($fileDeleteMedium); }
            if (file_exists($fileDeleteSmall)) { unlink($fileDeleteSmall); }
            
            // Delete
            $productPhotoDelete = ProductPhoto::where('photo', $fileName)
                ->where('user_id', $userId)
                ->delete();

            $responses = array(
                'status_code' => 202,
                'status_message' => 'Deleted',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
	}
	public function deleteEditPhoto(Request $request)
	{
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
        
		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'user_id' => 'required|integer',
            'photo' => 'required',
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
        
		// Product Check
		$productId = $request->product_id;
		$fileName = $request->photo;

        $item = ProductPhoto::where('photo', $fileName)
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (empty($responses) AND empty($item))
        {
            $responses = array(
                'status_code' => 203,
                'status_message' => 'Not Found',
                'items' => $items,
            );
        }

        // Photo Check
		$productPhotoCount = ProductPhoto::where('product_id', $productId)
			->get()
			->count();

        if (empty($responses) AND $productPhotoCount <= 1)
        {
            $responses = array(
                'status_code' => 207,
                'status_message' => 'Validation Error',
                'errors' => array('photo' => ['Foto Tidak Dapat Dihapus!! Minimal harus terdapat 1 Foto Produk.']),
            );
		}

		// Success
        if (empty($responses))
        {
            // Unlink
            $fileDelete = $public.'uploads/products/'.$fileName;
            $fileDeleteLarge = $public.'uploads/products/large-'.$fileName;
            $fileDeleteMedium = $public.'uploads/products/medium-'.$fileName;
            $fileDeleteSmall = $public.'uploads/products/small-'.$fileName;

            if (file_exists($fileDelete)) { unlink($fileDelete); }
            if (file_exists($fileDeleteLarge)) { unlink($fileDeleteLarge); }
            if (file_exists($fileDeleteMedium)) { unlink($fileDeleteMedium); }
            if (file_exists($fileDeleteSmall)) { unlink($fileDeleteSmall); }

            // Delete
            $productPhotoDelete = ProductPhoto::where('photo', $fileName)
                ->where('user_id', $userId)
                ->where('product_id', $productId)
                ->delete();
    
            // Update
            $update = Product::where('id', $productId)->update([
                'status' => 0,
                'action_id' => 2,
            ]);

            $responses = array(
                'status_code' => 202,
                'status_message' => 'Deleted',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
    }
    
	public function add(Request $request)
	{
        // Path
		$public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));
        
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

		// User Check
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
            // Clean Photo History
            //DB::beginTransaction();
            
            $productPhoto = ProductPhoto::whereNull('product_id')
                ->where('user_id', $userId)
                ->get();

            foreach ($productPhoto as $photo) {
                $fileName = $photo->photo;

                // Unlink
                $fileDelete = $public.'uploads/products/'.$fileName;
                $fileDeleteLarge = $public.'uploads/products/large-'.$fileName;
                $fileDeleteMedium = $public.'uploads/products/medium-'.$fileName;
                $fileDeleteSmall = $public.'uploads/products/small-'.$fileName;

                if (file_exists($fileDelete)) { unlink($fileDelete); }
                if (file_exists($fileDeleteLarge)) { unlink($fileDeleteLarge); }
                if (file_exists($fileDeleteMedium)) { unlink($fileDeleteMedium); }
                if (file_exists($fileDeleteSmall)) { unlink($fileDeleteSmall); }
                
                // Delete
                $productPhotoDelete = ProductPhoto::where('photo', $fileName)
                    ->delete();
            }

            //DB::commit();

            // Lists
            $conditions = Condition::orderBy('id', 'ASC')
                ->get();

            $categories = Category::orderBy('id', 'ASC')
                ->get();
            
            // Max Point
            $max_point = Option::where('type', 'max-point')->first();
            $max_point = $max_point->content;

            // Data
            $data = array(
                'conditions' => $conditions,
                'categories' => $categories,
                'max_point' => $max_point,
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
	public function create(Request $request)
	{
		// Initialization
    	$items = array();
    	$photos = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'name' => 'required|max:255',
            'condition' => 'required|integer',
            'category' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'stock' => 'required|integer|min:1',
            'price' => 'required|integer|min:15000',
            'description' => 'required',
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

		// Photo Validation
        $productPhoto = ProductPhoto::whereNull('product_id')
			->where('user_id', $userId)
			->first();
			
        if (empty($responses) AND empty($productPhoto))
        {
            $responses = array(
                'status_code' => 207,
                'status_message' => 'Validation Error',
                'errors' => array('photo' => ['Foto Produk harus terisi minimal 1 Foto.']),
            );
        }

		// Point Validation
		$max_point = Option::where('type', 'max-point')->first();
		$max_point = $max_point->content;

        if (empty($responses) AND !empty($request->point))
        {
            if ($request->point > $max_point)
            {
                $responses = array(
                    'status_code' => 207,
                    'status_message' => 'Validation Error',
                    'errors' => array('point' => ['Persentase Maksimal Point adalah '.$max_point.'%']),
                );
            }
        }
        
        // Discount Validation
		if (empty($responses) AND !empty($request->discount))
		{
			if ($request->discount >= $request->price)
			{
                $responses = array(
                    'status_code' => 207,
                    'status_message' => 'Validation Error',
                    'errors' => array('price' => ['Harga Diskon harus Lebih Kecil dari Harga Satuan']),
                );
			}
		}
        
		// Success
        if (empty($responses))
        {
            // Transaction
            //DB::beginTransaction();
            
            // Price & Discount
            $price = $request->price;
            $discount = $request->discount;

            if (!empty($request->discount))
            {
                $price = $request->discount;
                $discount = $request->price;
            }

            // Slug Validation
            $slug = str_slug($request->name);
            
            $productCheck = Product::where('slug', $slug)
                ->first();

            if (!empty($productCheck)) {
                $slug = $slug.'-'.time();
            }

            // Initialization
            $categoryId = $request->category;
            $conditionId = $request->condition;
            $name = $request->name;
            $weight = $request->weight;
            $stock = $request->stock;
            $description = $request->description;
            $point = $request->point;

            // Insert
            $insert = new Product;
            $insert->user_id = $userId;
            $insert->category_id = $categoryId;
            $insert->condition_id = $conditionId;
            $insert->name = $name;
            $insert->slug = $slug;
            $insert->weight = $weight;
            $insert->price = $price;
            $insert->stock = $stock;
            $insert->description = $description;
            $insert->discount = $discount;
            $insert->point = $point;
            $insert->status = 0;
            $insert->action_id = 1;
            $insert->save();

            $productId = $insert->id;
            
            $productPhotoUpdate = ProductPhoto::whereNull('product_id')
                ->where('user_id', $userId)->update([
                    'product_id' => $productId,
            ]);

            //DB::commit();

            // Check
            $item = Product::where('id', $productId)
                ->first();
            
            // Photo
            foreach ($item->productphoto as $productphoto)
            {
                $photo = array(
                    'id' => $productphoto->id,
                    'user_id' => $productphoto->user_id,
                    'product_id' => $productphoto->product_id,
                    'photo' => asset('uploads/products/medium-'.$productphoto->photo),
                );

                $photos[] = $photo;
            }
                
            // Data
            $data = array(
                'id' => $item->id,
                'name' => $item->name,
                'photo' => $photos,
                'category_id' => $item->category_id,
                'condition_id' => $item->condition_id,
                'weight' => $item->weight,
                'description' => $item->description,
                'point' => $item->point,
                'price' => $item->price,
                'discount' => $item->discount,
                'stock' => $item->stock,
                'sold' => $item->sold,
                'rating' => $item->rating,
                'review' => $item->review,
                'location' => $item->user->kabupaten->name,
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

	public function edit(Request $request)
	{
		// Initialization
    	$items = array();
    	$photos = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
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

		// Product Check
        $itemId = $request->product_id;

    	$item = Product::where('id', $itemId)
            ->where('user_id', $userId)
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
            // Photo
            foreach ($item->productphoto as $productphoto)
            {
                $photo = array(
                    'id' => $productphoto->id,
                    'user_id' => $productphoto->user_id,
                    'product_id' => $productphoto->product_id,
                    'photo' => asset('uploads/products/medium-'.$productphoto->photo),
                    'name' => $productphoto->photo,
                );

                $photos[] = $photo;
            }

            // Price & Discount
            $price = $item->price;
            $discount = $item->discount;
    
            if (!empty($item->discount))
            {
                $item->discount = $price;
                $item->price = $discount;
            }
                
            // Product
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

            $product = array(
                'id' => $item->id,
                'name' => $item->name,
                'photo' => $photos,
                'category_id' => $item->category_id,
                'condition_id' => $item->condition_id,
                'weight' => $item->weight,
                'description' => $item->description,
                'point' => $item->point,
                'price' => $item->price,
                'discount' => $item->discount,
                'stock' => $item->stock,
                'sold' => $item->sold,
                'rating' => $item->rating,
                'review' => $item->review,
                'location' => $item->user->kabupaten->name,
                'created' => $created,
                'updated' => $updated,
            );

            // Lists
            $conditions = Condition::orderBy('id', 'ASC')
                ->get();

            $categories = Category::orderBy('id', 'ASC')
                ->get();
        
            // Max Point
            $max_point = Option::where('type', 'max-point')->first();
            $max_point = $max_point->content;

            // Data
            $data = array(
                'product' => $product,
                'conditions' => $conditions,
                'categories' => $categories,
                'max_point' => $max_point,
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
	public function update(Request $request)
	{
		// Initialization
    	$items = array();
    	$photos = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'name' => 'required|max:255',
            'condition' => 'required|integer',
            'category' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            // 'price' => 'required|integer|min:15000',
            'price' => 'required|integer|min:0',
            'description' => 'required',
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

		// Product Check
        $productId = $request->product_id;

        $product = Product::where('id', $productId)
            ->where('user_id', $userId)
            ->first();

        if (empty($responses) AND empty($product))
        {
            $responses = array(
                'status_code' => 203,
                'status_message' => 'Not Found',
                'items' => $items,
            );
        }

		// Photo Validation
        $productPhoto = ProductPhoto::where('product_id', $productId)
			->where('user_id', $userId)
			->first();
			
        if (empty($responses) AND empty($productPhoto))
        {
            $responses = array(
                'status_code' => 207,
                'status_message' => 'Validation Error',
                'errors' => array('photo' => ['Foto Produk harus terisi minimal 1 Foto.']),
            );
        }

		// Point Validation
		$max_point = Option::where('type', 'max-point')->first();
		$max_point = $max_point->content;

        if (empty($responses) AND !empty($request->point))
        {
            if ($request->point > $max_point)
            {
                $responses = array(
                    'status_code' => 207,
                    'status_message' => 'Validation Error',
                    'errors' => array('point' => ['Persentase Maksimal Point adalah '.$max_point.'%']),
                );
            }
		}
        
        // Discount Validation
		if (empty($responses) AND !empty($request->discount))
		{
			if ($request->discount >= $request->price)
			{
                $responses = array(
                    'status_code' => 207,
                    'status_message' => 'Validation Error',
                    'errors' => array('price' => ['Harga Diskon harus Lebih Kecil dari Harga Satuan']),
                );
			}
		}
        
		// Success
        if (empty($responses))
        {
            // Transaction
            //DB::beginTransaction();
            
            // Price & Discount
            $price = $request->price;
            $discount = $request->discount;

            if (!empty($request->discount))
            {
                $price = $request->discount;
                $discount = $request->price;
            }

            // Initialization
            $categoryId = $request->category;
            $conditionId = $request->condition;
            $name = $request->name;
            $weight = $request->weight;
            $stock = $request->stock;
            $description = $request->description;
            $point = $request->point;

            // Insert
            $update = Product::find($productId);
            $update->category_id = $categoryId;
            $update->condition_id = $conditionId;
            $update->name = $name;
            $update->weight = $weight;
            $update->price = $price;
            $update->stock = $stock;
            $update->description = $description;
            $update->discount = $discount;
            $update->point = $point;
            $update->save();

            //DB::commit();

            // Check
            $item = Product::where('id', $productId)
                ->first();
            
            // Photo
            foreach ($item->productphoto as $productphoto)
            {
                $photo = array(
                    'id' => $productphoto->id,
                    'user_id' => $productphoto->user_id,
                    'product_id' => $productphoto->product_id,
                    'photo' => asset('uploads/products/medium-'.$productphoto->photo),
                );

                $photos[] = $photo;
            }
                
            // Data
            $data = array(
                'id' => $item->id,
                'name' => $item->name,
                'photo' => $photos,
                'category_id' => $item->category_id,
                'condition_id' => $item->condition_id,
                'weight' => $item->weight,
                'description' => $item->description,
                'point' => $item->point,
                'price' => $item->price,
                'discount' => $item->discount,
                'stock' => $item->stock,
                'sold' => $item->sold,
                'rating' => $item->rating,
                'review' => $item->review,
                'location' => $item->user->kabupaten->name,
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
    
	public function delete(Request $request)
	{
		// Initialization
    	$items = array();

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
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

		// Product Check
        $productId = $request->product_id;

    	$product = Product::where('id', $productId)
            ->where('user_id', $userId)
            ->first();

        if (empty($responses) AND empty($product))
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
            // Transaction
            //DB::beginTransaction();
    
            // Delete
            $update = Product::where('id', $productId)->update([
                'status' => 0,
                'action_id' => 3,
            ]);
    
            //DB::commit();

            // Check
            $item = Product::where('id', $productId)
                ->first();
            
            // Photo
            foreach ($item->productphoto as $productphoto)
            {
                $photo = array(
                    'id' => $productphoto->id,
                    'user_id' => $productphoto->user_id,
                    'product_id' => $productphoto->product_id,
                    'photo' => asset('uploads/products/medium-'.$productphoto->photo),
                );

                $photos[] = $photo;
            }
                
            // Data
            $data = array(
                'id' => $item->id,
                'name' => $item->name,
                'photo' => $photos,
                'category_id' => $item->category_id,
                'condition_id' => $item->condition_id,
                'weight' => $item->weight,
                'description' => $item->description,
                'point' => $item->point,
                'price' => $item->price,
                'discount' => $item->discount,
                'stock' => $item->stock,
                'sold' => $item->sold,
                'rating' => $item->rating,
                'review' => $item->review,
                'location' => $item->user->kabupaten->name,
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
        		'status_message' => 'Deleted',
				'items' => $items,
			);
		}

		return response()->json($responses, $responses['status_code']);
    }
}
