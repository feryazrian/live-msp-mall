<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\Product;
use Marketplace\Kabupaten;
use Marketplace\Category;
use Marketplace\User;

use Auth;

class SearchController extends Controller
{
	public function index(Request $request)
	{
		$request->sort = ($request->sort) ? $request->sort : 'new';
		// Initialization
		$limit = 16;
		$keyword = null;

		$type = $request->type;

		$condition = null;
		$category = null;

		$min = null;
		$max = null;
		$minValue = null;
		$maxValue = null;

		$location = null;

		$sort = null;

		$showMore = false;

		// Array
		$users = array();
		$products = [];
		$categoryDetail = null;

		// Check Type
		if (empty($type) || $type > 2) {
			$type = 1;
		}

		// Product Search
		// if ($type == 1) {

		// Product Status
		$search = Product::where('status', 1)
			->where('stock', '>', 0);

		// Category
		if (!empty($request->category)) {
			$category = $request->category;
			$categoryDetail = Category::where('slug', $category)->orWhere('id', $category)->first();

			// if (empty($categoryDetail)) {
			// 	$categoryDetail = Category::where('slug', $category)->first();
			// 	if (!empty($categoryDetail)) {
			// 		$category = $categoryDetail->id;
			// 		$keyword = $categoryDetail->name;
			// 	}
			// }

			$search->where('category_id', $categoryDetail->id);
		}

		// Keyword
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;

			$categoryDetail = Category::where('name', $keyword)->first();

			// if (!empty($categoryDetail)) {
			// 	$category = $categoryDetail->id;
			// 	$search->where('category_id', $categoryDetail->id);
			// }

			if (empty($categoryDetail)) {
				// $search->where('name', 'like', '%' . $keyword . '%');
				$search->where('name', 'like', '%'.$keyword.'%');
					// ->orWhere('description', 'like', '%'.$keyword.'%');
			}
		}

		// Set Min and Max Value
		$minValue = $search->min('price');
		$maxValue = $search->max('price');

		// Condition
		if (!empty($request->condition)) {
			$condition = $request->condition;
			$search->where('condition_id', $condition);
		}

		// Price Min
		if (!empty($request->min)) {
			$min = $request->min;
			$search->where('price', '>=', $min);
		}

		// Price Max
		if (!empty($request->max)) {
			$max = $request->max;
			$search->where('price', '<=', $max);
		}

		// Location
		if (!empty($request->location)) {
			$location = $request->location;
			$search->whereHas('user', function ($q) use ($location) {
				$q->where('users.place_birth', $location);
			});
		}

		// Sort
		if (!empty($request->sort)) {
			$sort = $request->sort;

			switch ($sort) {
				case 'new':
					$search->orderBy('created_at', 'desc');
					break;
				case 'bestseller':
					$search->orderBy('sold', 'desc');
					break;
				case 'expensive':
					$search->orderBy('price', 'desc')
						->orderBy('discount', 'desc');
					break;
				case 'cheap':
					$search->orderBy('price', 'asc')
						->orderBy('discount', 'asc');
					break;
				case 'sale':
					$search->where('sale', 1)
						->orderBy('created_at', 'DESC');
					break;
				case 'preorder':
					$search->where('preorder', 1)
						->orderBy('created_at', 'DESC');
					break;
				default:
					$search->orderBy('created_at', 'desc');
					break;
			}
		}

		$showMore = ($search->count() > 16) ? true : false;
		// Lists
		$products = $search->where('status', 1)->paginate($limit);
		// }

		$users = User::whereNotNull('merchant_id')
			->where('name', 'like', "%$keyword%");

		if (!empty($request->location)) {
			$location = $request->location;
			$users->where('place_birth', $location);
		}

		$users = $users->whereHas('merchant', function ($q) use ($keyword) {
			$q->where('status', 1)
				->orWhere('name', 'like', "%$keyword%");
			})->paginate($limit);

		// Lists
		// $locations = Kabupaten::orderBy('province_id', 'asc')->get();

		// Title
		$pageTitle = 'Hasil Pencarian ' . $keyword;

		if (empty($search_keyword) and !empty($categoryDetail)) {
			$pageTitle = 'Kategori ' . $categoryDetail->name;
		}

		// Return View
		return view('search')->with([
			'pageTitle' => $pageTitle,

			// 'locations' => $locations,
			'products' => $products,
			'users' => $users,
			'categoryDetail' => $categoryDetail,

			'search_keyword' => $keyword,

			'search_type' => $type,

			'search_condition' => $condition,
			'search_category' => $category,

			'search_min' => $min,
			'search_max' => $max,
			'min_value'	=> $minValue,
			'max_value'	=> $maxValue,

			'search_location' => $location,

			'search_sort' => $sort,
			'show_more' => $showMore,
		]);
	}

	public function loadMoreProduct(Request $request)
	{
		$limit = 20;
		$request->sort = ($request->sort) ? $request->sort : 'new';
		// Initialization
		$keyword = null;

		$type = $request->type;

		$condition = null;
		$category = null;

		$min = null;
		$max = null;

		$location = null;

		$sort = null;

		// Array
		$products = array();
		$categoryDetail = null;

		// Check Type
		if (empty($type) || $type > 2) {
			$type = 1;
		}

		// Product Status
		$search = Product::where('status', 1)
			->where('stock', '>', 0);

		// Category
		if (!empty($request->category)) {
			$category = $request->category;
			$categoryDetail = Category::where('slug', $category)->orWhere('id', $category)->first();

			$search->where('category_id', $categoryDetail->id);
		}

		// Keyword
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;

			$categoryDetail = Category::where('name', $keyword)->first();

			if (!empty($categoryDetail)) {
				$category = $categoryDetail->id;
				$search->where('category_id', $categoryDetail->id);
			}

			if (empty($categoryDetail)) {
				$search->where('name', 'like', '%' . $keyword . '%');

				/*
					$search->where('name', 'like', '%'.$keyword.'%')
						->orWhere('description', 'like', '%'.$keyword.'%');
					*/
			}
		}

		// Condition
		if (!empty($request->condition)) {
			$condition = $request->condition;
			$search->where('condition_id', $condition);
		}

		// Price Min
		if (!empty($request->min)) {
			$min = $request->min;
			$search->where('price', '>=', $min);
		}

		// Price Max
		if (!empty($request->max)) {
			$max = $request->max;
			$search->where('price', '<=', $max);
		}

		// Location
		if (!empty($request->location)) {
			$location = $request->location;
			$search->whereHas('user', function ($q) use ($location) {
				$q->where('users.place_birth', $location);
			});
		}

		// Sort
		if (!empty($request->sort)) {
			$sort = $request->sort;

			switch ($sort) {
				case 'new':
					$search->orderBy('created_at', 'desc');
					break;
				case 'bestseller':
					$search->orderBy('sold', 'desc');
					break;
				case 'expensive':
					$search->orderBy('price', 'desc')
						->orderBy('discount', 'desc');
					break;
				case 'cheap':
					$search->orderBy('price', 'asc')
						->orderBy('discount', 'asc');
					break;
				case 'sale':
					$search->where('sale', 1)
						->orderBy('created_at', 'DESC');
					break;
				case 'preorder':
					$search->where('preorder', 1)
						->orderBy('created_at', 'DESC');
					break;
				default:
					$search->orderBy('created_at', 'desc');
					break;
			}
		}

		// Lists
		$products = $search->inRandomOrder()->paginate($limit);

		$output = '';
		if (!empty($products)) {
			foreach ($products as $key => $item) {
				$imgTag = '';
				$labelTag = '';
				$priceTag = '';
				$productImg = $item->productphoto->first();
				$imgSrc = (!empty($productImg->photo)) ? asset("uploads/products/medium-" . $productImg->photo) : asset("images/placeholder.png");
				$linkDetail = route("product.detail", ["slug" => $item->slug]);
				$imgTag = '<a href="' . $linkDetail . '" class="image"><img src="' . $imgSrc . '"></a>';

				if ($item->sale) {
					$labelTag = '<div class="label"><div class="flashsale">Flash Sale</div></div>';
				}
				if ($item->preorder) {
					$labelTag = '<div class="label"><div class="preorder">Group Buy</div></div>';
				}
				if ($item->type_id == 2) {
					$labelTag = '<div class="label"><div class="preorder">E-Voucher</div></div>';
				}

				$title = str_limit($item->name, 25);

				if (!empty($item->discount)) {
					$priceTag = '<div class="price">Rp ' . number_format($item->price, 0, ",", ".") . '<strike>Rp ' . number_format($item->discount, 0, ",", ".") . '</strike></div>';
				} else {
					$priceTag = '<div class="price">Rp ' . number_format($item->price, 0, ",", ".") . '</div>';
				}

				$star = '';
				$iStar = '';
				$star = str_repeat('<i class="fas fa-star"></i>', $item->rating);
				$iStar = str_repeat('<i class="fas fa-star inactive"></i>', 5 - $item->rating);
				$rating = '<div class="stars">' . $star . $iStar . '<span class="stats ml-1">' . $item->review . '</span></div>';
				if (!empty($item->user->place_birth)) {
					$city = '<div class="location">' . $item->user->kabupaten->name . '</div>';
				}

				$output .= '<div class="product-card">' . $imgTag . $labelTag . '<div class="content"><a href="' . $linkDetail . '" class="title">' . $title . '</a>' . $priceTag . $rating . $city . '</div></div>';
			}
		}

		$results = ['items' => $products, 'html' => $output];
		return $results;
	}

	public function loadMoreShop(Request $request)
	{
		$limit = 20;
		$request->sort = ($request->sort) ? $request->sort : 'new';
		// Initialization
		$keyword = $request->keyword;
		$location = $request->location;

		// Array
		$merchant = [];

		$search = User::whereNotNull('merchant_id')
				->where('name', 'like', "%$keyword%");

		if (!empty($location)) {
			$search->where('place_birth', $location);
		}

		$merchant = $search->whereHas('merchant', function ($q) use ($keyword) {
			$q->where('status', 1)
				->orWhere('name', 'like', "%$keyword%");
			})->paginate($limit);

		$output = '';
		if (!empty($merchant)) {
			foreach ($merchant as $key => $item) {
				$link = route('user.detail', ['username' => $item->username]);
				$imgSrc = (!empty($item->photo)) ? asset("uploads/photos/medium-" . $item->photo) : asset("images/default.png");
				$loc = !empty($item->kabupaten->name) ? $item->kabupaten->name : '';

				$output .= "<div class='product-card'>
				<a href='$link' class='image rounded'>
					<img src='$imgSrc'>
				</a>
				<div class='content'>
					<a href='$link' class='title'>$item->name</a>
					<div class='location'>@$item->username</div>
					<div class='location'>$loc</div>
				</div>
			</div>";
			}
		}

		$results = ['items' => $merchant, 'html' => $output];
		return $results;
	}

	public function kabupaten(){
		$kabupaten = Kabupaten::orderBy('province_id', 'asc')->get();

		return $kabupaten;
	}
}
