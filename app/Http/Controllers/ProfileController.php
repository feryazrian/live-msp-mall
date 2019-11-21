<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;

use Marketplace\User;
use Marketplace\Product;
use Marketplace\TransactionProduct;

use Marketplace\Transaction;
use Marketplace\VoucherTransaction;
use Marketplace\Option;
use Marketplace\LoyaltyMember;

use Validator;
use Auth;

class ProfileController extends Controller
{
	public function index(Request $request)
	{
		// Initialization
		$username = str_slug($request->username);
		$username = str_replace('-','_',$username);

		// Check
		$user = User::where('username', $username)
			->first();

        if (empty($user)) {
        	return redirect('/');
		}

        $pageTitle = $user->name.' @'.$user->username;
		$userId = $user->id;

		// Lists
		$products = Product::where('user_id', $userId)
			->where('status', 1)
            ->where('stock', '>', 0)
			->orderBy('created_at', 'DESC')
			->simplePaginate(20);
			
		$reviews = TransactionProduct::where('user_id', $userId)
			->where('status', 5)
			->groupBy('transaction_id')
			->get();

		// Loyalty
		$loyalty = $this->loyalty($userId);

		// Return View
		return view('profile')->with([
            'headTitle' => false,
			'pageTitle' => $pageTitle,
            'products' => $products,
            'reviews' => $reviews,
            'user' => $user,
            'loyalty' => $loyalty,
        ]);
	}

	public function loyalty($user_id)
	{
        // Loyalty Start
        $loyalty_start = Option::where('type', 'loyalty-start')->first();
		$loyalty_start = $loyalty_start->content;

        // Loyalty End
        $loyalty_end = Option::where('type', 'loyalty-end')->first();
		$loyalty_end = $loyalty_end->content;

		// Transactions Count
		$loyalty = array();
		$transactions = 0;
		$lists = Transaction::where('user_id', $user_id)
			->where('updated_at', '>=', $loyalty_start)
			->where('updated_at', '<=', $loyalty_end)
			->get();
		
		foreach ($lists as $item) {
			if (!empty($item->product[0])) {
				if ($item->product[0]->status == 5) {
					$transactions += $item->total;
				}
			}
		}

		// Vouchers Count
        $vouchers = VoucherTransaction::where('user_id', $user_id)
			->whereNotNull('payment_id')
			->where('status', 1)
			->where('updated_at', '>=', $loyalty_start)
			->where('updated_at', '<=', $loyalty_end)
            ->sum('price');

		// Count
		$transactions = $transactions + $vouchers;
		
        // Price
        $price = Option::where('type', 'loyalty-price')->first();
		$price = $price->content;

		// Point
		$point = floor($transactions / $price);

		$lists = LoyaltyMember::orderBy('point', 'ASC')
			->get();
		
		foreach ($lists as $item) {
			if ($point >= $item->point) {
				$loyalty = $item;
			}
		}

		return $loyalty;
	}

}
