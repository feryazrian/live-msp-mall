<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Marketplace\Category;

class ApiCategory extends Controller
{
    public function index(Request $request)
    {
    	$items = array();

    	$lists = Category::orderBy('id', 'ASC')
			->get();

    	foreach ($lists as $item)
    	{
			if (!empty($item->cover))
			{
				$item->cover = asset('uploads/categories/'.$item->cover);
			}

			$data = array(
				'id' => $item->id,
				'name' => $item->name,
				'slug' => $item->slug,
	            'icon' => asset('uploads/categories/'.$item->icon),
				'cover' => $item->cover,
				'highlight' => $item->highlight,
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
	
    public function highlight(Request $request)
    {
    	$items = array();

    	$lists = Category::where('highlight', 1)
			->orderBy('id', 'ASC')
			->get();

    	foreach ($lists as $item)
    	{
			$products = array();
			foreach ($item->product_highlight as $highlight)
			{
				// Location
				$location = null;
				if (!empty($highlight->user->kabupaten))
				{
					$location = $highlight->user->kabupaten->name;
				}

				// Product
				$dataHighlight = array(
					'id' => $highlight->id,
					'name' => $highlight->name,
					'photo' => asset('uploads/products/medium-'.$highlight->productphoto[0]->photo),
					'price' => $highlight->price,
					'discount' => $highlight->discount,
					'rating' => $highlight->rating,
					'review' => $highlight->review,
					'location' => $location,
				);
	
				$products[] = $dataHighlight;
			}

			if (!empty($item->cover))
			{
				$item->cover = asset('uploads/categories/'.$item->cover);
			}

			$data = array(
				'id' => $item->id,
				'name' => $item->name,
				'slug' => $item->slug,
	            'icon' => asset('uploads/categories/'.$item->icon),
				'cover' => $item->cover,
				'product' => $products,
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
}
