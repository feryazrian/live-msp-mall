<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;

use Marketplace\Category;

class CategoryController extends Controller
{
	public function index()
	{
		// Initliazation
		$pageTitle = 'Kategori Produk';
		
		// Return View
		return view('category.index')->with([
			'pageTitle' => $pageTitle,
		]);
	}
}
