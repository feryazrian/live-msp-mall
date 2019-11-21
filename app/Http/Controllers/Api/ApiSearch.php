<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Marketplace\Product;
use Marketplace\Kabupaten;
use Marketplace\Category;
use Marketplace\User;

use Auth;

class ApiSearch extends Controller
{
	public function index(Request $request)
	{
		// Initialization
		$keyword = null;

		$type = $request->type;

		$condition = null;
		$category = null;

		$min = null;
		$max = null;

		$location = null;

		$sort = null;

		// Array
        $items = array();
		$users = array();
		$products = array();
		$categoryDetail = null;

		// Take & Skip
        $take = config('app.take');
        $skip = config('app.skip');

        if (!empty($request->take))
        {
            $take = $request->take;
        }

        if (!empty($request->skip))
        {
            $skip = $request->skip;
        }

		// Check Type
		if(empty($type) || $type > 2) {
			$type = 1;
		}

		// Product Search
		if ($type == 1) {

			// Product Status
			$search = Product::where('status', 1)
				->take($take)
				->skip($skip);

			// Keyword
			if (!empty($request->keyword)) {
				$keyword = $request->keyword;

				$categoryDetail = Category::where('name', $keyword)->first();

				if (!empty($categoryDetail))
				{
					$category = $categoryDetail->id;
					$search = $search->where('category_id', $category);
				}
				
				if (empty($categoryDetail))
				{
					$search->where('name', 'like', '%'.$keyword.'%')
						->orWhere('description', 'like', '%'.$keyword.'%');
				}
			}

			// Condition
			if (!empty($request->condition)) {
				$condition = $request->condition;
				$search->where('condition_id', $condition);
			}

			// Category
			if (!empty($request->category)) {
				$category = $request->category;
				$categoryDetail = Category::where('id', $category)->first();

				if (empty($categoryDetail))
				{
					$categoryDetail = Category::where('slug', $category)->first();
					if (!empty($categoryDetail))
					{
						$category = $categoryDetail->id;
						$keyword = $categoryDetail->name;
					}
				}

				$search = $search->where('category_id', $category);
			}

			// Price Min
			if (!empty($request->min)) {
				$min = $request->min;
				$search->where('price', '>=', $min);
			}

			// Price Max
			if (!empty($request->max)) {
				$max = $request->max;
				$search->where('price', '<=', $max);
			}

			// Location
			if (!empty($request->location)) {
				$location = $request->location;
				$search->whereHas('user', function($q) use ($location) {
					$q->where('users.place_birth', $location);
				});
			}

			// Sort
			if (!empty($request->sort)) {
				$sort = $request->sort;

				switch ($sort) {
					case 'new':
						$search->orderBy('created_at', 'desc');
						break;
					case 'bestseller':
						$search->orderBy('sold', 'desc');
						break;
					case 'expensive':
						$search->orderBy('price', 'desc')
							->orderBy('discount', 'desc');
						break;
					case 'cheap':
						$search->orderBy('price', 'asc')
							->orderBy('discount', 'asc');
						break;
					case 'sale':
						$search->where('sale', 1)
							->orderBy('created_at', 'DESC');
						break;
					default:
						$search->orderBy('created_at', 'desc');
						break;
				}
			}

			// Lists
			$products = $search->where('status', 1)
				->where('stock', '>', 0)
				->get();

			foreach ($products as $item)
			{
				$data = array(
					'id' => $item->id,
					'name' => $item->name,
					'photo' => asset('uploads/products/medium-'.$item->productphoto[0]->photo),
					'price' => $item->price,
					'discount' => $item->discount,
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
			}
		}

		// Merchant User
		if ($type == 2) {
			$keyword = $request->keyword;
			
			$search = User::whereNotNull('merchant_id')
				->where('name', 'like', '%'.$keyword.'%')
				->orWhere('username', 'like', '%'.$keyword.'%')
				->take($take)
				->skip($skip);

			$users = $search->get();

			foreach ($users as $item)
			{
				// Merchant Status
				$merchant = 0;
				if (!empty($item->merchant_id))
				{
					$merchant = 1;
				}

				$location = null;
				$place_birth = array();
				if (!empty($item->place_birth))
				{
					$location = $item->kabupaten->name;
					$place_birth = array(
						'id' => $item->kabupaten->id,
						'name' => $item->kabupaten->name,
					);
				}

				// Data
				$data = array(
					'id' => $item->id,
					'name' => $item->name,
					'username' => $item->username,
					'email' => $item->email,
					'photo' => asset('uploads/photos/medium-'.$item->photo),
					'phone' => $item->phone,
					'location' => $location,
					'place_birth' => $place_birth,
					'date_birth' => $item->date_birth,
					'bio' => $item->bio,
					'merchant' => $merchant,
					'api_msp' => $item->api_msp,
					'api_app' => $item->api_app,
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

        return response()->json($responses, $responses['status_code']);
	}
}
