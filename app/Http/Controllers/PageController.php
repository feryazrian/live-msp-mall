<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;

use Marketplace\Page;

class PageController extends Controller
{
	public function index()
	{
		// Initialization
		$pageTitle = 'Bantuan';
		$position = 1;

		// Check
		$lists = Page::whereHas('footer', function ($q) use ($position) {
			$q->where('footers.position_id', $position);
		})
			->orderBy('created_at', 'ASC')
			->get();

		if (empty($lists)) {
			// Return Redirect
			return redirect('/');
		}

		// Return View
		return view('page.index')->with([
			'headTitle' => true,
			'pageTitle' => $pageTitle,
			'lists' => $lists,
		]);
	}

	public function detail(Request $request)
	{
		// Initialization
		$slug = str_slug($request->slug);
		$pageTitle = 'Bantuan';
		$position = 1;

		// Check
		$page = Page::where('slug', $slug)
			->whereHas('footer', function ($q) use ($position) {
				$q->where('footers.position_id', $position);
			})
			->first();

		if (empty($page)) {
			// Return Redirect
			return redirect('/');
		}

		$pageTitle = $page->name;

		// Lists
		$lists = Page::orderBy('created_at', 'ASC')
			->get();

		// Return View
		return view('page.detail')->with([
			'headTitle' => true,
			'pageTitle' => $pageTitle,
			'page' => $page,
			'lists' => $lists,
		]);
	}
}