<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\Wishlist;

use Auth;

class WishlistController extends Controller
{
	public function index(Request $request)
	{
        // Initialization
        $pageTitle = 'Wishlist Saya';
		$user_id = Auth::user()->id;
		
		// Lists
		$wishlists = Wishlist::where('user_id', $user_id)
			->simplePaginate(20);

		// Return View
	    return view('wishlist.index')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
			'wishlists' => $wishlists,
			'cardType' => 'wishlist',
		]);;
	}
	public function store(Request $request)
	{
        // Initialization
		$product_id = $request->product_id;
		$user_id = Auth::user()->id;
		$redirect = $request->redirect;

		// Check
		$check = Wishlist::where('user_id', $user_id)
			->where('product_id', $product_id)
			->first();

		if(!empty($check)) {
			// Return Redirect
			return redirect('/');
		}

		// Insert
		$insert = new Wishlist;
		$insert->user_id = $user_id;
		$insert->product_id = $product_id;
		$insert->save();

		$slug = $insert->product->slug;
		
		// Return Redirect Product
		if ($redirect == 'product')
		{
			return redirect()
				->route('product.detail', ['slug' => $slug])
				->with('status', 'Selamat!! Produk telah berhasil di simpan pada Wishlist anda.');
		}

		// Return Redirect
		return redirect()
			->route('wishlist')
			->with('status', 'Produk telah berhasil di Hapus dari Wishlist anda.');
	}
	public function delete(Request $request)
	{
        // Initialization
		$product_id = $request->product_id;
		$user_id = Auth::user()->id;
		$redirect = $request->redirect;

		// Check
		$check = Wishlist::where('user_id', $user_id)
			->where('product_id', $product_id)
			->first();

		if(empty($check)) {
			// Return Redirect
			return redirect('/');
		}

		$slug = $check->product->slug;

		// Delete
		$delete = Wishlist::where('product_id', $product_id)
			->where('user_id', $user_id)
			->delete();
		
		// Return Redirect Product
		if ($redirect == 'product')
		{
			return redirect()
				->route('product.detail', ['slug' => $slug])
				->with('status', 'Produk telah berhasil di Hapus dari Wishlist anda.');
		}

		// Return Redirect
		return redirect()
			->route('wishlist')
			->with('status', 'Produk telah berhasil di Hapus dari Wishlist anda.');
	}
}
