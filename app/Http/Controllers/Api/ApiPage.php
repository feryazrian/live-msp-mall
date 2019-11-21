<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;

use Marketplace\Page;

class ApiPage extends Controller
{
    public function index(Request $request)
    {
        $items = array();

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

        $lists = Page::orderBy('created_at', 'ASC')
            ->take($take)
            ->skip($skip)
            ->get();

        foreach ($lists as $item)
        {
            $data = array(
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
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

    public function detail(Request $request)
    {
        // Initialization
    	$items = array();

        // Validation
        $validator = Validator::make($request->all(), [
            'page_id' => 'required|integer',
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
        $itemId = $request->page_id;

    	$item = Page::where('id', $itemId)
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
    		$data = array(
    			'id' => $item->id,
    			'name' => $item->name,
    			'slug' => $item->slug,
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

        	$responses = array(
        		'status_code' => 200,
        		'status_message' => 'OK',
        		'items' => $items,
        	);
        }

        return response()->json($responses, $responses['status_code']);
    }
}
