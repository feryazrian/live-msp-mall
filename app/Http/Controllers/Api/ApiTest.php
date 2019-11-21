<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ApiTest extends Controller
{
    public function index(Request $request)
    {
		// Initialization
    	$items = array();

    	$items = 'Holaaaa';

		// Response
    	$responses = array(
    		'status_code' => 200,
    		'status_message' => 'OK',
    		'items' => $items,
    	);
		
		// Return Json
        return response()->json($responses, $responses['status_code']);
    }
}
