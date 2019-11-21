<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\LifePointController;

use Marketplace\User;
use Marketplace\Balance;
use Marketplace\BalanceDeposit;
use Marketplace\BalanceWithdraw;
use Marketplace\TransactionPayment;
use Marketplace\TransactionProduct;
use Marketplace\TransactionShipping;
use Marketplace\PointTopup;
use Marketplace\PpobTransaction;
use Marketplace\PpobType;
use Marketplace\LifePoint;


use Auth;
use Hash;
use Validator;

class BalanceController extends Controller
{
	public function index()
	{
		// Initialization
        $pageTitle = 'Mons Wallet';
		$point = 0;
		$balanceGrowth = 0;
		$balancePending = array();
		$balanceSummary = array();
		$user = Auth::user();

		//check life point 
		$lifePoint = LifePoint::where('user_id',$user->id)->first();
        if($lifePoint == null){
            $myLifePoint = new LifePointController ;
            $myLifePoint = $myLifePoint->create_new($user);
        }
        

		// Lists
		$balanceDeposit = BalanceDeposit::where('user_id', Auth::user()->id)
			->whereNotNull('payment_id')
			->orderBy('created_at','asc')
			->get();

		$balanceData = Balance::where('user_id', Auth::user()->id)
			->orderBy('created_at','asc')
			->get();

		$pointTopup = PointTopup::where('user_id', Auth::user()->id)
			->whereNotNull('payment_id')
			->orderBy('created_at','asc')
			->get();

		foreach ($balanceData as $balance)
		{
			if (!empty($balance->transaction))
			{
				if (!empty($balance->ppob)) {
					if ($balance->transaction->status == 1){
						$totalTransaction = $balance->transaction->transaction->total;
						$balanceGrowth += $totalTransaction;
						$type = 2;
					}else{
						$totalTransaction = $balance->transaction->transaction->total;
						$balanceGrowth -= $totalTransaction;
						$type = 3;
					}
				} else {
					// Initialize
					$transactionProduct = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id);
					// Transaction Shipping
					$transactionShipping = TransactionShipping::where('transaction_id', $balance->transaction->transaction_id);

					//status 1 bertambah  uang 
					if ($balance->transaction->status == 1) {
						// Transaction Product
						$getTransactionProduct = $transactionProduct->where('user_id', $balance->transaction->seller_id)->get();

						// Get Transaction Shipping
						$getTransactionShipping = $transactionShipping->where('user_id', $balance->transaction->seller_id)->first();
	
						$statusTransaction = 0;
						$totalTransaction = 0;
						$totalTransactionPoint = 0;
						$totalShipping = 0;
						foreach ($getTransactionProduct as $transaction) {
							$totalProduct = ($transaction->unit * $transaction->price);
							$totalTransaction += $totalProduct;
							$statusTransaction = $transaction->status;
							$totalTransactionPoint += ($transaction->point * $transaction->point_price);
						}

						// Kembalikan point
						$totalTransaction -= $totalTransactionPoint;
	
						if (!empty($getTransactionShipping))
						{
							$totalShipping = $getTransactionShipping->price;
							$totalTransaction += $getTransactionShipping->price;
						}
	
						// Transaction Promo
						if ($statusTransaction == 6) {
							if (!empty($balance->transaction->transaction->promo)) {
								if (empty($balance->transaction->transaction->promo->promo->type_id) === 1 || $balance->transaction->transaction->promo->type == "Diskon Ongkir" ) {
									$totalTransaction -= $totalShipping;
								} else{
									if ($totalProduct > $balance->transaction->transaction->promo->price) {
										$totalTransaction -= $balance->transaction->transaction->promo->price;
									} else {
										$totalTransaction -= $totalProduct;
									}
								}
							}
						}
	
						// Status
						$type = 2;
						$balanceGrowth += $totalTransaction;
					}
	
					if ($balance->transaction->status == 0) {
						// Transaction Product
						$getTransactionProduct = $transactionProduct->get();

						// Get Transaction Shipping
						$getTransactionShipping = $transactionShipping->sum('price');
	
						$statusTransaction = 0;
						$totalTransaction = 0;
						$totalTransactionPoint = 0;
						$totalShipping = $getTransactionShipping;
						foreach ($getTransactionProduct as $transaction) {
							$totalProduct = ($transaction->unit * $transaction->price);
							$totalTransaction += $totalProduct;
							$totalTransactionPoint += ($transaction->point * $transaction->point_price);
						}

						// Kembalikan poin
						$totalTransaction -= $totalTransactionPoint;

						// Tambah total shipping
						$totalTransaction += $totalShipping;
	
						// $ppobTransaction = PpobTransaction::where("transaction_id",$balance->transaction->transaction_id)
						// 	->get();
						// // dd($ppobTransaction,"sini",$balance->transaction->transaction_id);
						// foreach($ppobTransaction as $ppob){
						// 	$totalTransaction += $ppob->price;
						// }
	
						// Transaction Promo
						if (!empty($balance->transaction->transaction->promo)) {
							$totalTransaction -= $balance->transaction->transaction->promo->price;
						}
	
						// Status
						$type = 3;
						$balanceGrowth -= $totalTransaction;
					}
				}

				$arrayAddGrowth = array_add($balance, 'growth', $balanceGrowth);

				$arrayAddBalance = array_add($arrayAddGrowth, 'balance', $totalTransaction);

				$arrayAdd = array_add($arrayAddBalance, 'type', $type);

				$balanceSummary[] = $arrayAdd;
			}

			if (!empty($balance->deposit)) {
				if($balance->deposit->status == 1) {
					$transactionPayment = TransactionPayment::where('order_id',$balance->deposit->transaction_id)
						->first();

					if(!empty($transactionPayment)) {
						$balanceGrowth += $transactionPayment->gross_amount;

						$arrayAddGrowth = array_add($balance, 'growth', $balanceGrowth);

						$arrayAddBalance = array_add($arrayAddGrowth, 'balance', $transactionPayment->gross_amount);

						$arrayAdd = array_add($arrayAddBalance, 'type', 1);

						$balanceSummary[] = $arrayAdd;
					}
				}
			}

			if (!empty($balance->withdraw)) {
				$balanceGrowth -= $balance->withdraw->balance;

				$arrayAddGrowth = array_add($balance, 'growth', $balanceGrowth);

				$arrayAddBalance = array_add($arrayAddGrowth, 'balance', $balance->withdraw->balance);

				$arrayAdd = array_add($arrayAddBalance, 'type', 0);

				$balanceSummary[] = $arrayAdd;

				if($balance->withdraw->status == 0) {
					$arrayAddBalance = array_add($balance, 'balance', $balance->withdraw->balance);

					$arrayAdd = array_add($arrayAddBalance, 'type', 0);

					$balancePending[] = $arrayAdd;
				}
			}

			if (!empty($balance->ads)) {
				$balanceGrowth -= $balance->ads->balance;

				$arrayAddGrowth = array_add($balance, 'growth', $balanceGrowth);

				$arrayAddBalance = array_add($arrayAddGrowth, 'balance', $balance->ads->balance);

				$arrayAdd = array_add($arrayAddBalance, 'type', 3);

				$balanceSummary[] = $arrayAdd;
			}

			if (!empty($balance->voucher)) {
				$balanceGrowth += $balance->voucher->price;

				$arrayAddGrowth = array_add($balance, 'growth', $balanceGrowth);

				$arrayAddBalance = array_add($arrayAddGrowth, 'balance', $balance->voucher->price);

				$arrayAdd = array_add($arrayAddBalance, 'type', 2);

				$balanceSummary[] = $arrayAdd;
			}
		}
		// die();
		// dd($balanceSummary);

		$balanceSummary = array_reverse($balanceSummary);
		// dd($balanceSummary);

		
		// Check Balance
		$myBalance = $this->myBalance();

		// MSP Point
		if (!empty(Auth::user()->api_msp))
		{
			$operation = 'check_mspoint';
			$username = Auth::user()->username;
	
			// Check Username
        	$response = new MsplifeController;
        	$response = $response->check_mspoint($operation, $username);
				
			// Point
			if (!empty($response->status))
			{
				if ($response->status == 1)
				{
					$point = $response->point;
				}
			}
		}

		//get Life Point 
		$lifePointController = new LifePointController;
		$lifePoint = $lifePointController->get_life_data(Auth::user());

		// Return View
		return view('balance.index')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
			'point' => $point,
			'lifePoint'=> $lifePoint->total_point,
			'balancePending' => $balancePending,
			'balanceSummary' => $balanceSummary,
			'balanceDeposit' => $balanceDeposit,
            'myBalance' => $myBalance,
            'pointTopup' => $pointTopup,
		]);
	}
	public function deposit()
	{
		// Initialization
		$pageTitle = 'Deposit';
        $pageSubTitle = 'Mons Wallet';

		// Check Balance
		$myBalance = $this->myBalance();
		
		// Return View
		return view('balance.deposit')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'myBalance' => $myBalance,
		]);
	}
	public function withdraw()
	{
		// Initialization
		$pageTitle = 'Withdraw';
		$pageSubTitle = 'Mons Wallet';
		if(Auth::user()->merchant_id == null){
			return redirect()->back()
			->with('Warning', 'Maaf Anda tidak bisa melakukan withdraw');
		}

		// Check Balance
		$myBalance = $this->myBalance();
		
		// Return View
		return view('balance.withdraw')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'pageSubTitle' => $pageSubTitle,
            'myBalance' => $myBalance,
		]);
	}
	public function store(Request $request)
	{
		// Validation
		$this->validatorWithdraw($request->all())->validate();

		// Initialization
		$data = $request->all();

		// Check User
		$user = User::find(Auth::user()->id);

		if (!Hash::check($data['password'], $user->password)) {
			return redirect()
				->route('balance.withdraw')
				->with('warning', 'Terjadi kesalahan pada Formulir Anda! Harap masukkan password anda saat ini dengan benar.');
		}

		// Check Balance
		$myBalance = $this->myBalance();
		if ($myBalance < $data['balance']) {
			return redirect()
				->route('balance.withdraw')
				->with('warning', 'Maaf, saldo anda tidak mencukupi untuk melakukan pencairan dengan nominal yang anda masukkan.');
		}

		// Check Minimum Withdraw
		if ($data['balance'] < 10000) {
			return redirect()
				->route('balance.withdraw')
				->with('warning', 'Maaf, saldo anda tidak mencukupi untuk melakukan pencairan dana!! Pencairan dana minimal sebesar Rp 10.000');
		}

		// Create
		$balanceWithdraw = new BalanceWithdraw;
		$balanceWithdraw->user_id = Auth::user()->id;
		$balanceWithdraw->balance = $data['balance'];
		$balanceWithdraw->bank_name = $data['bank_name'];
		$balanceWithdraw->bank_holder = $data['bank_holder'];
		$balanceWithdraw->bank_number = $data['bank_number'];
		$balanceWithdraw->save();

		$balanceWithdrawId = $balanceWithdraw->id;

		$balanceNew = new Balance;
		$balanceNew->user_id = Auth::user()->id;
		$balanceNew->withdraw_id = $balanceWithdrawId;
		$balanceNew->notes = 'Pencairan Saldo';
		$balanceNew->save();

		// Return Redirect
		return redirect()
			->route('balance')
			->with('status', 'Selamat!! Permintaan pencairan saldo telah berhasil di kirim, dana akan diproses maksimal dalam 1-2 hari kerja.');
	}

	protected function validatorWithdraw(array $data)
	{
		return Validator::make($data, [
			'password' => 'required|min:8',
			'balance' => 'required|numeric',
			// 'bank_number' => 'required|integer',
			'bank_number'=>'required',
			'bank_name' => 'required|max:255',
			'bank_holder' => 'required|max:255',
	    ]);
    }
	public function myBalance()
	{
		// Initialization
		$myBalance = 0;

		// Lists
		$balanceData = Balance::where('user_id', Auth::user()->id)
			->orderBy('created_at','asc')
			->get();
		// dd($balanceData);
		foreach ($balanceData as $balance) {
			if (!empty($balance->deposit)) {
				if ($balance->deposit->status == 1) {
					$transactionPayment = TransactionPayment::where('order_id', $balance->deposit->transaction_id)
						->first();
					// dd($transactionPayment);
					if (!empty($transactionPayment)) {
						$myBalance += $transactionPayment->gross_amount;
					}
				}
			}
			// dd($balance->transaction->status);

			// dd($balance);
			if (!empty($balance->withdraw) && $balance->withdraw->status != 2) {
				$myBalance -= $balance->withdraw->balance;
			}

			if (!empty($balance->ads)) {
				$myBalance -= $balance->ads->balance;
				
			}

			if (!empty($balance->voucher)) {
				$myBalance += $balance->voucher->price;
			}

			if (!empty($balance->transaction)) {
				if (!empty($balance->ppob)) {
					// $myBalance -= $balance->ppob->price;
					if ($balance->transaction->status == 1){
						$myBalance += $balance->transaction->transaction->total;
					}else{
						$myBalance -= $balance->transaction->transaction->total;
					}
				}
				else {
					// Initialize
					$transactionProduct = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id);
					// Transaction Shipping
					$transactionShipping = TransactionShipping::where('transaction_id', $balance->transaction->transaction_id);

					if ($balance->transaction->status == 1) {

						// Transaction Product
						$getTransactionProduct = $transactionProduct->where('user_id', $balance->transaction->seller_id)->get();

						// Get Transaction Shipping
						$getTransactionShipping = $transactionShipping->where('user_id', $balance->transaction->seller_id)->first();
	
						$statusTransaction = 0;
						$totalTransaction = 0;
						$totalTransactionPoint = 0;
						$totalShipping = 0;
						foreach ($getTransactionProduct as $transaction) {
							$totalProduct = ($transaction->unit * $transaction->price);
							$totalTransaction += $totalProduct;
							$statusTransaction = $transaction->status;
							$totalTransactionPoint += ($transaction->point * $transaction->point_price);
						}

						// Kembalikan point
						$totalTransaction -= $totalTransactionPoint;
	
						if (!empty($getTransactionShipping))
						{
							$totalShipping = $getTransactionShipping->price;
							$totalTransaction += $getTransactionShipping->price;
						}
	
						// Transaction Promo
						if ($statusTransaction == 6) {
							if (!empty($balance->transaction->transaction->promo)) {
								if (empty($balance->transaction->transaction->promo->promo->type_id) === 1 || $balance->transaction->transaction->promo->type == "Diskon Ongkir" ) {
									$totalTransaction -= $totalShipping;
								} else{
									if ($totalProduct > $balance->transaction->transaction->promo->price) {
										$totalTransaction -= $balance->transaction->transaction->promo->price;
									} else {
										$totalTransaction -= $totalProduct;
									}
								}
							}
						}
	
						// Balance
						$myBalance += $totalTransaction;
					}
					if ($balance->transaction->status == 0) {
						// Transaction Product
						$getTransactionProduct = $transactionProduct->get();

						// Get Transaction Shipping
						$getTransactionShipping = $transactionShipping->sum('price');
	
						$statusTransaction = 0;
						$totalTransaction = 0;
						$totalTransactionPoint = 0;
						$totalShipping = $getTransactionShipping;
						foreach ($getTransactionProduct as $transaction) {
							$totalProduct = ($transaction->unit * $transaction->price);
							$totalTransaction += $totalProduct;
							$totalTransactionPoint += ($transaction->point * $transaction->point_price);
						}

						// Kembalikan poin
						$totalTransaction -= $totalTransactionPoint;

						// Tambah total shipping
						$totalTransaction += $totalShipping;
	
						// Transaction Promo
						if (!empty($balance->transaction->transaction->promo)) {
							$totalTransaction -= $balance->transaction->transaction->promo->price;
						}
	
						// Status
						$myBalance -= $totalTransaction;
					}
				}
			}
		}

		// Return Integer
		return $myBalance;
	}

	public function myBalanceByUserId($id)
	{
		// Initialization
		$myBalance = 0;

		// Lists
		$balanceData = Balance::where('user_id', $id)
			->orderBy('created_at','asc')
			->get();
		// dd($balanceData);
		foreach ($balanceData as $balance) {
			if (!empty($balance->deposit)) {
				if ($balance->deposit->status == 1) {
					$transactionPayment = TransactionPayment::where('order_id', $balance->deposit->transaction_id)
						->first();
					// dd($transactionPayment);
					if (!empty($transactionPayment)) {
						$myBalance += $transactionPayment->gross_amount;
					}
				}
			}
			// dd($balance->transaction->status);

			// dd($balance);
			if (!empty($balance->withdraw) && $balance->withdraw->status != 2) {
				$myBalance -= $balance->withdraw->balance;
			}

			if (!empty($balance->ads)) {
				$myBalance -= $balance->ads->balance;
				
			}

			if (!empty($balance->voucher)) {
				$myBalance += $balance->voucher->price;
			}

			if (!empty($balance->transaction)) {
				if (!empty($balance->ppob)) {
					// $myBalance -= $balance->ppob->price;
					if ($balance->transaction->status == 1){
						$myBalance += $balance->transaction->transaction->total;
					}else{
						$myBalance -= $balance->transaction->transaction->total;
					}
				}
				else {
					// Initialize
					$transactionProduct = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id);
					// Transaction Shipping
					$transactionShipping = TransactionShipping::where('transaction_id', $balance->transaction->transaction_id);

					if ($balance->transaction->status == 1) {

						// Transaction Product
						$getTransactionProduct = $transactionProduct->where('user_id', $balance->transaction->seller_id)->get();

						// Get Transaction Shipping
						$getTransactionShipping = $transactionShipping->where('user_id', $balance->transaction->seller_id)->first();
	
						$statusTransaction = 0;
						$totalTransaction = 0;
						$totalTransactionPoint = 0;
						$totalShipping = 0;
						foreach ($getTransactionProduct as $transaction) {
							$totalProduct = ($transaction->unit * $transaction->price);
							$totalTransaction += $totalProduct;
							$statusTransaction = $transaction->status;
							$totalTransactionPoint += ($transaction->point * $transaction->point_price);
						}

						// Kembalikan point
						$totalTransaction -= $totalTransactionPoint;
	
						if (!empty($getTransactionShipping))
						{
							$totalShipping = $getTransactionShipping->price;
							$totalTransaction += $getTransactionShipping->price;
						}
	
						// Transaction Promo
						if ($statusTransaction == 6) {
							if (!empty($balance->transaction->transaction->promo)) {
								if (empty($balance->transaction->transaction->promo->promo->type_id) === 1 || $balance->transaction->transaction->promo->type == "Diskon Ongkir" ) {
									$totalTransaction -= $totalShipping;
								} else{
									if ($totalProduct > $balance->transaction->transaction->promo->price) {
										$totalTransaction -= $balance->transaction->transaction->promo->price;
									} else {
										$totalTransaction -= $totalProduct;
									}
								}
							}
						}
	
						// Balance
						$myBalance += $totalTransaction;
					}
					if ($balance->transaction->status == 0) {
						// Transaction Product
						$getTransactionProduct = $transactionProduct->get();

						// Get Transaction Shipping
						$getTransactionShipping = $transactionShipping->sum('price');
	
						$statusTransaction = 0;
						$totalTransaction = 0;
						$totalTransactionPoint = 0;
						$totalShipping = $getTransactionShipping;
						foreach ($getTransactionProduct as $transaction) {
							$totalProduct = ($transaction->unit * $transaction->price);
							$totalTransaction += $totalProduct;
							$totalTransactionPoint += ($transaction->point * $transaction->point_price);
						}

						// Kembalikan poin
						$totalTransaction -= $totalTransactionPoint;

						// Tambah total shipping
						$totalTransaction += $totalShipping;
	
						// Transaction Promo
						if (!empty($balance->transaction->transaction->promo)) {
							$totalTransaction -= $balance->transaction->transaction->promo->price;
						}
	
						// Status
						$myBalance -= $totalTransaction;
					}
				}
			}
		}

		// Return Integer
		return intval($myBalance);
	}
}
