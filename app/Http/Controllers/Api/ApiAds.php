<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Validator;

use Marketplace\AdsPosition;
use Marketplace\AdsRequest;

class ApiAds extends Controller
{
    public function create(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            //'user_id' => 'required|integer',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|numeric',
            'position_id' => 'required|integer',
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
        
        // Initialization
    	$items = array();
        $user_id = $request->user_id;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $position_id = $request->position_id;
        $content = $request->content;

        // Check
        $item = AdsPosition::where('id', $position_id)
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
            $insert = new AdsRequest;
            $insert->user_id = $user_id;
            $insert->name = $name;
            $insert->email = $email;
            $insert->phone = $phone;
            $insert->position_id = $position_id;
            $insert->content = $content;
            $insert->save();

            // Data
            $item = $insert;

    		$data = array(
    			'id' => $item->id,
    			'user_id' => $item->user_id,
    			'name' => $item->name,
    			'email' => $item->email,
    			'phone' => $item->phone,
    			'position_id' => $item->position_id,
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
        		'status_code' => 201,
        		'status_message' => 'Created',
        		'items' => $items,
        	);
        }

        return response()->json($responses, $responses['status_code']);
    }

    public function position()
    {
        $items = array();

        $lists = AdsPosition::orderBy('name', 'asc')
            ->get();

        foreach ($lists as $item)
        {
            $data = array(
                'id' => $item->id,
                'name' => $item->name,
                'resolution' => $item->resolution,
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
