<?php

namespace Marketplace\Http\Controllers\Shipping;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Marketplace\Slide;
use Marketplace\Kabupaten;

class HomeController extends Controller
{
	public function index()
	{
		// Initialization
		$pageTitle = 'Mons Express';

        // Lists
		$slides = Slide::where('status', 1)
			->where('position_id', 2)
			->inRandomOrder()
            ->get();

		$places = Kabupaten::orderBy('province_id', 'asc')
			->get();

        // Return View
	    return view('shipping.index')->with([
			'pageTitle' => $pageTitle,
			'slides' => $slides,
			'places' => $places,
        ]);
	}
}
