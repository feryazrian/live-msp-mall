<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;

use Marketplace\Slide;

class ApiSlide extends Controller
{
    public function index(Request $request)
    {
    	$items = array();

        $lists = Slide::where('status', 1)
            ->where('position_id', 1)
            ->inRandomOrder()
            ->get();

    	foreach ($lists as $item)
    	{
			$data = array(
				'id' => $item->id,
				'name' => $item->name,
				'position' => array(
                    'id' => $item->position->id, 
                    'name' => $item->position->name, 
                ),
                'photo' => asset('uploads/slides/'.$item->photo),
				'url' => $item->url,
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
