<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;

use Marketplace\Option;
use Marketplace\Provinsi;
use Marketplace\Kabupaten;
use Marketplace\Kecamatan;
use Marketplace\Desa;

class ApiOption extends Controller
{
    public function index(Request $request)
    {
    	$items = array();

    	$lists = Option::orderBy('id', 'ASC')
			->get();

    	foreach ($lists as $item)
    	{
			if ($item->format == 'photo')
			{
				$item->content = asset('uploads/options/'.$item->content);
			}

			$data = array(
				'id' => $item->id,
				'name' => $item->name,
				'type' => $item->type,
				'format' => $item->format,
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
    
	public function provinsi()
	{
        // Initialization
    	$items = array();

        // Lists
        $lists = Provinsi::orderBy('name', 'asc')
            ->get();
        
        foreach ($lists as $item)
        {
			$data = array(
				'id' => $item->id,
				'name' => $item->name,
			);

			$items[] = $data;
        }

		// Success
    	$responses = array(
    		'status_code' => 200,
    		'status_message' => 'OK',
    		'items' => $items,
    	);

        return response()->json($responses, $responses['status_code']);
    }
	public function provinsiKabupaten()
	{
        // Initialization
    	$items = array();

        // Lists
        $lists = Provinsi::orderBy('name', 'asc')
            ->get();
        
        foreach ($lists as $item)
        {
            $itemsTwo = array();
                
            foreach ($item->kabupaten as $kabupaten)
            {
                $dataTwo = array(
                    'id' => $kabupaten->id,
                    'name' => $kabupaten->name,
                );
    
                $itemsTwo[] = $dataTwo;
            }

			$data = array(
				'id' => $item->id,
				'name' => $item->name,
				'kabupaten' => $itemsTwo,
			);

			$items[] = $data;
        }

		// Success
    	$responses = array(
    		'status_code' => 200,
    		'status_message' => 'OK',
    		'items' => $items,
    	);

        return response()->json($responses, $responses['status_code']);
    }
	public function kabupaten(Request $request)
	{
        // Initialization
    	$items = array();

        // Validation
        $validator = Validator::make($request->all(), [
            'provinsi_id' => 'required|integer',
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
        $provinsi_id = $request->provinsi_id;

    	$check = Provinsi::where('id', $provinsi_id)
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
            // Lists
            $lists = Kabupaten::where('province_id', $provinsi_id)
                ->orderBy('name', 'asc')
                ->get();
            
            foreach ($lists as $item)
            {
                $data = array(
                    'id' => $item->id,
                    'name' => $item->name,
                );

                $items[] = $data;
            }

            // Success
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
    }
	public function kecamatan(Request $request)
	{
        // Initialization
    	$items = array();

        // Validation
        $validator = Validator::make($request->all(), [
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

        // Check
        $kabupaten_id = $request->kabupaten_id;

    	$check = Kabupaten::where('id', $kabupaten_id)
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
            // Lists
            $lists = Kecamatan::where('regency_id', $kabupaten_id)
                ->orderBy('name', 'asc')
                ->get();
            
            foreach ($lists as $item)
            {
                $data = array(
                    'id' => $item->id,
                    'name' => $item->name,
                );

                $items[] = $data;
            }

            // Success
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
    }
	public function desa(Request $request)
	{
        // Initialization
    	$items = array();

        // Validation
        $validator = Validator::make($request->all(), [
            'kecamatan_id' => 'required|integer',
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
        $kecamatan_id = $request->kecamatan_id;

    	$check = Kecamatan::where('id', $kecamatan_id)
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
            // Lists
            $lists = Desa::where('district_id', $kecamatan_id)
                ->orderBy('name', 'asc')
                ->get();
            
            foreach ($lists as $item)
            {
                $data = array(
                    'id' => $item->id,
                    'name' => $item->name,
                );

                $items[] = $data;
            }

            // Success
            $responses = array(
                'status_code' => 200,
                'status_message' => 'OK',
                'items' => $items,
            );
        }

        return response()->json($responses, $responses['status_code']);
    }
}
