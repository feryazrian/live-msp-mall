<?php

namespace Marketplace\Http\Controllers\Shipping;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Marketplace\Slide;
use Marketplace\ShippingWaybill;

use Validator;

class TrackingController extends Controller
{
	public function index()
	{
        // Initialization
        $pageTitle = 'Lacak Kiriman';

        // Lists
		$slides = Slide::where('status', 1)
			->where('position_id', 2)
			->inRandomOrder()
            ->get();

        // Return View
	    return view('shipping.tracking.index')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'slides' => $slides,
        ]);
	}
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'code' => 'required|integer',
        ]);

        // Initialization
        $pageTitle = 'Lacak Kiriman';
        $code = $request->code;
		
		// Check
		$item = ShippingWaybill::where('id', $code)
			->first();

		// Lists
		$slides = Slide::where('status', 1)
			->where('position_id', 2)
			->inRandomOrder()
			->get();

        // Return View
	    return view('shipping.tracking.detail')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'slides' => $slides,
			'item' => $item,
        ]);
	}

	public function json($code)
	{
		// Initialization
		$items = array();
		$manifest = array();
		$delivered = false;

		// Check
		$item = ShippingWaybill::where('id', $code)
			->first();

		if (empty($item))
		{
			return $responses = array(
				'status_code' => 203,
				'status_message' => 'Not Found',
				'items' => $items,
			);
		}
		
		if ($item->status_id == 1)
		{
			$delivered = true;
		}

		$destination = $item->kecamatan->name.', '.$item->kabupaten->name.', '.$item->kabupaten->provinsi->name.', Indonesia '.$item->postal_code;
		
		// Summary
		$summary = array(
			'courier_code' => strtolower($item->courier->code),
			'courier_name' => $item->courier->name,
			'waybill_number' => $item->id,
			'service_code' => $item->courier->slug,
			'waybill_date' => $item->created_at->format('Y-m-d'),
			'shipper_name' => $item->shipper_name,
			'receiver_name' => $item->name,
			'origin' => $item->shipper_address,
			'destination' => $destination,
		);

		// Details
		$details = array(
			'waybill_number' => $item->id,
			'waybill_date' => $item->created_at->format('Y-m-d'),
			'waybill_time' => $item->created_at->format('H:i'),
			'transaction' => $item->transaction,
			'origin' => $item->shipper_address,
			'destination' => $destination,
			'shipper_name' => $item->shipper_name,
			'receiver_name' => $item->name,
		);

		// Delivery Status
		$delivery_status = array(
			'status' => $item->status->name,
			'pod_date' => $item->updated_at->format('Y-m-d'),
			'pod_time' => $item->updated_at->format('H:i'),
		);

		// Manifest
        foreach ($item->manifest as $itemmanifest)
        {
            $data = array(
                'manifest_code' => $itemmanifest->id,
                'manifest_description' => $itemmanifest->description,
                'manifest_date' => $itemmanifest->created_at->format('Y-m-d'),
                'manifest_time' => $itemmanifest->created_at->format('H:i'),
                'city_name' => $itemmanifest->kabupaten->name,
            );

            $manifest[] = $data;
        }

		// Result
		$result = array(
			'delivered' => $delivered,
			'summary' => $summary,
			'details' => $details,
			'delivery_status' => $delivery_status,
			'manifest' => $manifest
		);
		
		// Data
		$data = array(
			'query' => array(
				'waybill' => $item->id,
				'courier' => strtolower($item->courier->code),
			),
			'result' => $result
		);

		$items = $data;

		return $responses = array(
			'status_code' => 200,
			'status_message' => 'OK',
			'items' => $items,
		);
	}
    public function api(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'code' => 'required|integer',
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
        $code = $request->code;

		// Check
		$item = ShippingWaybill::where('id', $code)
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
			$responses = $this->json($code);
        }

        return response()->json($responses, $responses['status_code']);
	}
}
