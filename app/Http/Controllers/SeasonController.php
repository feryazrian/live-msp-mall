<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\Season;
use Marketplace\SeasonProduct;

use Auth;

class SeasonController extends Controller
{
	public function index(Request $request)
	{
        // Initialization
		$pageTitle = 'Promo Musiman';
		$slug = $request->slug;
		
        // Check
        $season = Season::where('slug', $slug)
            ->first();

        if (empty($season))
        {
            return redirect('/');
		}

		$pageTitle = $season->name;
		
		// Lists
		$lists = SeasonProduct::where('season_id', $season->id)
			->groupBy('product_id')
			->paginate(16);

		// Return View
	    return view('season')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
			'lists' => $lists,
		]);;
	}
}
