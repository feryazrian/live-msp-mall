<?php

namespace Marketplace\Http\Controllers\Shipping;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Marketplace\Slide;
use Marketplace\Kabupaten;
use Marketplace\Kecamatan;
use Marketplace\Option;
use Marketplace\ShippingCourier;

use Validator;

class PricingController extends Controller
{
	public function index()
	{
        // Initialization
        $pageTitle = 'Ongkos Kirim';

        // Lists
		$slides = Slide::where('status', 1)
			->where('position_id', 2)
			->inRandomOrder()
            ->get();

		$places = Kabupaten::orderBy('province_id', 'asc')
			->get();

        // Return View
	    return view('shipping.pricing.index')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'slides' => $slides,
			'places' => $places,
        ]);
	}
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'kabupaten_id' => 'required|integer',
            'kecamatan_id' => 'required|integer',
            'postal_code' => 'required|integer',
            'transaction' => 'required|integer',
        ]);

        // Initialization
        $pageTitle = 'Ongkos Kirim';
        $kabupaten_id = $request->kabupaten_id;
        $kecamatan_id = $request->kecamatan_id;
        $postal_code = $request->postal_code;
		$transaction = $request->transaction;
		$distance = null;
		$price = null;
		$shipping_maximum = null;
		
		// Check
		$kabupaten = Kabupaten::where('id', $kabupaten_id)
			->first();

		if (empty($kabupaten)) {
			return redirect('/');
		}

		$kecamatan = Kecamatan::where('id', $kecamatan_id)
			->first();

		if (empty($kecamatan)) {
			return redirect('/');
		}

		// Shipping Origin
		$destination = 'Kecamatan '.$kecamatan->name.', '.$kabupaten->name.', Provinsi '.$kabupaten->provinsi->name.', Indonesia '.$postal_code;

		// Shipping Destination
		$origin = Option::where('type', 'shipping-address')
			->first();
		$origin = $origin->content;

		$shipping_maximum = Option::where('type', 'shipping-maximum')
			->first();
		$shipping_maximum = $shipping_maximum->content;

		// Distance
		$distance = $this->distance($destination);

		// Price
		$price = $this->price($transaction, $distance);

        // Lists
		$slides = Slide::where('status', 1)
			->where('position_id', 2)
			->inRandomOrder()
            ->get();

        // Return View
	    return view('shipping.pricing.detail')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'slides' => $slides,
			'kabupaten' => $kabupaten,

			'shipping_maximum' => $shipping_maximum,
			'origin' => $origin,
			'destination' => $destination,
			'transaction' => $transaction,
			'distance' => $distance,
			'price' => $price,
        ]);
	}

	public function json($kabupaten_id, $kecamatan_id, $postal_code, $transaction)
	{
		// Check
		$kabupaten = Kabupaten::where('id', $kabupaten_id)
			->first();

		$kecamatan = Kecamatan::where('id', $kecamatan_id)
			->first();

		// Shipping Origin
		$destination = 'Kecamatan '.$kecamatan->name.', '.$kabupaten->name.', Provinsi '.$kabupaten->provinsi->name.', Indonesia '.$postal_code;

		// Counting
		$shipping_maximum = Option::where('type', 'shipping-maximum')
			->first();
		$shipping_maximum = $shipping_maximum->content;

		// Shipping Origin
		$destination = 'Kecamatan '.$kecamatan->name.', '.$kabupaten->name.', Provinsi '.$kabupaten->provinsi->name.', Indonesia '.$postal_code;
		$distance = $this->distance($destination);

		if ($distance > $shipping_maximum)
		{
			return $responses = array(
				'status_code' => 207,
				'status_message' => 'Validation Error',
				'errors' => array('distance' => ['Jarak Maksimal 12 km.']),
			);
		}

		
		// Price
		$price = $this->price($transaction, $distance);

		// Courier
		$shipping_courier = ShippingCourier::orderBy('id', 'ASC')
			->get();
		
		// Costs
		$cost = array(
			'service' => $shipping_courier[0]->service,
			'description' => $shipping_courier[0]->description,
			'distance' => $distance,
			'value' => $price,
			'etd' => $shipping_courier[0]->etd,
			'note' => $shipping_courier[0]->note,
		);
		$costs[] = $cost;

		// Result
		$result = array(
			'code' => $shipping_courier[0]->code,
			'name' => $shipping_courier[0]->name,
			'costs' => $costs
		);
		$results[] = $result;
		
		// Data
		$data = array(
			'destination_details' => array(
				'province_id' => $kabupaten->provinsi->id,
				'province' => $kabupaten->provinsi->name,
				'city_id' => $kabupaten->id,
				'city' => $kabupaten->name,
				'subdistrict_id' => $kecamatan->id,
				'subdistrict' => $kecamatan->name,
				'postal_code' => $postal_code,
			),
			'results' => $results
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
            'kabupaten_id' => 'required|integer',
            'kecamatan_id' => 'required|integer',
            'postal_code' => 'required|integer',
            'transaction' => 'required|integer',
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
        $kabupaten_id = $request->kabupaten_id;
        $kecamatan_id = $request->kecamatan_id;
        $postal_code = $request->postal_code;
		$transaction = $request->transaction;
		
		$costs = array();
		$results = array();

		// Check
		$kabupaten = Kabupaten::where('id', $kabupaten_id)
			->first();

		if (empty($responses) AND empty($kabupaten))
		{
			$responses = array(
				'status_code' => 203,
				'status_message' => 'Not Found',
				'items' => $items,
			);
		}

		$kecamatan = Kecamatan::where('id', $kecamatan_id)
			->first();

		if (empty($responses) AND empty($kecamatan))
		{
			$responses = array(
				'status_code' => 203,
				'status_message' => 'Not Found',
				'items' => $items,
			);
		}

		// Counting
		$shipping_maximum = Option::where('type', 'shipping-maximum')
			->first();
		$shipping_maximum = $shipping_maximum->content;

        if (empty($responses))
        {
			// Shipping Origin
			$destination = 'Kecamatan '.$kecamatan->name.', '.$kabupaten->name.', Provinsi '.$kabupaten->provinsi->name.', Indonesia '.$postal_code;
			$distance = $this->distance($destination);

			if ($distance > $shipping_maximum)
			{
				$responses = array(
					'status_code' => 207,
					'status_message' => 'Validation Error',
					'errors' => array('distance' => ['Jarak Maksimal 12 km.']),
				);
			}
        }

        // Success
        if (empty($responses))
        {
			$responses = $this->json($kabupaten_id, $kecamatan_id, $postal_code, $transaction);
        }

        return response()->json($responses, $responses['status_code']);
	}

	public function price($transaction, $distance)
	{
        // Initialization
		$shipping_transaction = Option::where('type', 'shipping-transaction')
			->first();
		$shipping_transaction = $shipping_transaction->content;

		$shipping_distance = Option::where('type', 'shipping-distance')
			->first();
		$shipping_distance = $shipping_distance->content;

		// dd($shipping_transaction,$shipping_distance,$transaction, $distance);
		// Lebih
		if ($transaction >= $shipping_transaction)
		{
			if ($distance <= $shipping_distance)
			{
				return 0;
			}

			// if ($distance > $shipping_distance)
			else
			{
				return 15000;
			}
		}

		// Kurang
		// else if ($transaction < $shipping_transaction)
		else
		{
			if ($distance <= $shipping_distance)
			{
				return 15000;
			}

			// if ($distance > $shipping_distance)
			else
			{
				return 30000;
			}
		}
	}
	public function distance($destination)
	{
		$apiKey = 'AIzaSyDELjVje1P1QniFTmzGaf5tY0PqX8WrZn8';
		
		// Change address format
		//$formattedAddrFrom    = str_replace(' ', '+', $origin);
		$formattedAddrTo     = str_replace(' ', '+', $destination);

		$unit = 'k';
		
		// Geocoding API request with start address
		/*$geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
		$outputFrom = json_decode($geocodeFrom);
		if (!empty($outputFrom->error_message)){
			return $outputFrom->error_message;
		}*/
		
		// Geocoding API request with end address
		$geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
		$outputTo = json_decode($geocodeTo);
		if (!empty($outputTo->error_message)){
			return $outputTo->error_message;
		}

		// Options
		$latitude = Option::where('type', 'shipping-latitude')
			->first();
		$latitude = $latitude->content;

		$longitude = Option::where('type', 'shipping-longitude')
			->first();
		$longitude = $longitude->content;
		
		// Get latitude and longitude from the geodata
		$latitudeFrom    = $latitude;
		$longitudeFrom    = $longitude;
		$latitudeTo        = $outputTo->results[0]->geometry->location->lat;
		$longitudeTo    = $outputTo->results[0]->geometry->location->lng;
		
		// Calculate distance between latitude and longitude
		$theta    = $longitudeFrom - $longitudeTo;
		$dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) + 
		cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
		$dist    = acos($dist);
		$dist    = rad2deg($dist);
		$miles    = $dist * 60 * 1.1515;
		
		// Convert unit and return distance
		$unit = strtoupper($unit);
		if ($unit == "K") {
			return floor(round($miles * 1.609344, 2));
		} elseif($unit == "M") {
			return round($miles * 1609.344, 2).' meters';
		} else {
			return round($miles, 2).' miles';
		}
	}
}
