<?php

namespace Marketplace\Http\Controllers\Shipping;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Marketplace\Page;
use Marketplace\Slide;

class PageController extends Controller
{
	public function index()
	{
		// Initialization
        $pageTitle = 'Bantuan';
        $position = 2;

		// Check
		$lists = Page::whereHas('footer', function($q) use ($position) {
                $q->where('footers.position_id', $position);
            })
			->orderBy('created_at', 'ASC')
			->get();

        if (empty($lists)) {
			// Return Redirect
        	return redirect('/');
		}

        // Lists
		$slides = Slide::where('status', 1)
			->where('position_id', 2)
			->inRandomOrder()
            ->get();

		// Return View
		return view('shipping.page.index')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'lists' => $lists,
			'slides' => $slides,
		]);
	}
	
	public function detail(Request $request)
	{
		// Initialization
		$slug = str_slug($request->slug);
        $pageTitle = 'Bantuan';
        $position = 2;

		// Check
        $page = Page::where('slug', $slug)
            ->whereHas('footer', function($q) use ($position) {
                $q->where('footers.position_id', $position);
            })
			->first();

        if (empty($page)) {
			// Return Redirect
        	return redirect('/');
		}

        $pageTitle = $page->name;

        // Lists
		$slides = Slide::where('status', 1)
			->where('position_id', 2)
			->inRandomOrder()
            ->get();

		// Return View
		return view('shipping.page.detail')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'page' => $page,
			'slides' => $slides,
		]);
	}
}
