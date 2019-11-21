<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;

use Marketplace\Coupon;

use Auth;

class CouponController extends Controller
{
	public function index()
	{
		// Initliazation
		$pageTitle = 'Kupon Transaksi';

		// Lists
		$coupons = Coupon::where('user_id', Auth::user()->id)
			->get();
		
		// Return View
		return view('coupon.index')->with([
			'pageTitle' => $pageTitle,
			'coupons' => $coupons,
		]);
	}
}
