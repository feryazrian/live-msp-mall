<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use DB;
use Carbon\Carbon;
use Auth;
use Marketplace\User;
use Marketplace\Option;
use Marketplace\BalanceDepositHistory;
use Marketplace\Balance;
use Marketplace\BalanceDeposit;
use Marketplace\Transaction;
use Marketplace\TransactionPayment;
use Marketplace\TransactionProduct;
use Marketplace\TransactionShipping;
use Marketplace\TransactionPaymentHistory;
class BalanceDepositHistoryController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Balance Deposit History';
        $page = 'balancedeposithistory';

        // Return View
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }

    public function data()
    {
        // Initialization
        $items = array();
        $pageTitle = 'Balance Deposit History';
        $page = 'balancedeposithistory';
    
        // $admin_id = Auth::user()->id;
        // $admin = User::where('id','=',$admin_id)->first();
       
        $lists = BalanceDepositHistory::join('users', 'users.id', '=', 'balance_deposit_history.user_id')
            ->select(
                'balance_deposit_history.id','users.id AS user_id','users.name','users.username','users.email','users.photo',
                'balance_deposit_history.admin_id', 

                'balance_deposit_history.jumlah', 
                'balance_deposit_history.tgl_auto_debet', 
                'balance_deposit_history.created_at', 
                'balance_deposit_history.updated_at'
            )

            ->where('users.activated','=','1')
            ->orderBy('balance_deposit_history.id', 'DESC')
            ->get();

      
        foreach ($lists as $item) 
        {
            $admin = User::where('id','=',$item->admin_id)->first();

            $items[] = array(
                'id'=> $item->id,
                'users' => ($item)?
                    "<div>Name <b>: ".$item->name."</b></div>".
                    "<div>Username <b>: ".$item->username."</b></div>".
                    "<div>Email  <b>: ".$item->email."</b></div>"
                :
                    "No Users",
                'admin' => 
                    "<div>Name <b>: ".$admin->name."</b></div>".
                    "<div>Username <b>: ".$admin->username."</b></div>".
                    "<div>Email  <b>: ".$admin->email."</b></div>",
              
                'jumlah'=> "Rp. ".$item->jumlah,
                'user_id'=> $item->user_id,
                'tgl_auto_debet'=> $item->tgl_auto_debet,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at'=> $item->updated_at->format('Y-m-d H:i:s'),
                'myBalance'=>$this->myBalance($item->user_id),
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function create(Request $request)
    {
        // Initialization
        $pageTitle = 'Balance Deposit History';
        $page = 'balancedeposithistory';
        $id = $request->id;
        $user = User::orderBy('id', 'ASC')
            ->where('activated','=','1')
            ->get();

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'users'=> $user,
        ]);

    }

 

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'user_id' => 'required|max:255',
            'jumlah' => 'required|max:255',
        ]);

        // Initialization
        $user_id = $request->user_id;
        $admin_id = Auth::user()->id;
        $admin = User::where('id','=',$admin_id)->first();

        $jumlah = $request->jumlah;
        $pageTitle = 'Balance Deposit History';
        $page = 'balancedeposithistory';
       
         // Transaction
        DB::beginTransaction();
        try {
            $now = Carbon::now();
            $now = $now->toDateTimeString();
            $transaction_id = str_random(33);
            $order_id= "MSBC". time();

            // Insert AutoDebet
            $insert = new BalanceDepositHistory;
            $insert->user_id = $user_id;
            $insert->admin_id = $admin_id;
            $insert->jumlah = $jumlah;
            $insert->tgl_auto_debet =$now;
            $insert->save();
            
            $id_payment=DB::table('transaction_payment')->max('id');
            $id_balance_deposit=DB::table('balance_deposit')->max('id');
            $id_transactions=DB::table('transactions')->max('id');
            $id_balance=DB::table('balances')->max('id');
            $id_transaction_payment_histori=DB::table('transaction_payment_histories')->max('id');

            $data_transaction_payment=[];
            $data_balance_deposit=[];
            $data_transactions=[];
            $data_balance=[];
            $data_transaction_payment_histori=[];

            $now = Carbon::now();
            $now = $now->toDateTimeString();
            $transaction_id = str_random(33);
            $order_id= rand();
            // $order_id= "MSBC". rand();

            $insert_transaction_payment= [
                "id"=>$id_payment +1,
                "user_id"=>$user_id,
                "gateway_id" => 5, 
                "status_code" => 200, 
                "status_message" => "success",
                "transaction_id" => $transaction_id, 
                "order_id"=>$order_id, 
                "gross_amount"=>$jumlah, 
                "payment_type"=> "layanan Admin",
                "transaction_time"=>$now, 
                "transaction_status"=>"settlement", 
                "fraud_status"=>"accept",
                "created_at"=>now(), 
                "updated_at"=>now()
            ];
            array_push($data_transaction_payment, $insert_transaction_payment);

            $insert_balance_deposit= [
                "id"=>$id_balance_deposit +1,
                "user_id"=>$user_id,
                "payment_id" => $id_payment + 1, 
                "transaction_id" => $order_id, 
                "status" => 1,
                "created_at"=>now(), 
                "updated_at"=>now()
            ];
            array_push($data_balance_deposit, $insert_balance_deposit);

            $insert_transactions= [
                "id"=>$id_transactions + 1,
                "user_id"=>$user_id,
                "payment_id" => $id_payment + 1, 
                "gateway_id" => 5, 
                "total" => $jumlah,
                "created_at"=>now(), 
                "updated_at"=>now()
            ];
            array_push($data_transactions, $insert_transactions);

            $insert_balance= [
                "id"=>$id_balance + 1,
                "user_id"=>$user_id,
                "deposit_id" => $id_balance_deposit +1, 
                "notes" => "Penambahan Saldo", 
                "created_at"=>now(), 
                "updated_at"=>now()
            ];
            array_push($data_balance, $insert_balance);

            $insert_transaction_payment_histori= [
                "id"=>$id_transaction_payment_histori + 1,
                "user_id"=>$user_id,
                "gateway_id"=>5,
                "status_code"=>200,
                "status_message"=>"success",
                "transaction_id"=>$transaction_id,
                "order_id"=>$order_id,
                "gross_amount"=>$jumlah,
                "payment_type"=> "layanan Admin",
                "transaction_time"=>$now,
                "transaction_status"=>"settlement",
                "fraud_status" => "accept", 
                "created_at"=>now(), 
                "updated_at"=>now()
            ];
            array_push($data_transaction_payment_histori, $insert_transaction_payment_histori);

            $transactionPayment = TransactionPayment::insert($data_transaction_payment);
            $balanceDeposit = BalanceDeposit::insert($data_balance_deposit);
            $transaction = Transaction::insert($data_transactions);
            $balance = Balance::insert($data_balance);
            $transactionPaymentHistori = TransactionPaymentHistory::insert($data_transaction_payment_histori);

            DB::commit();
            // Return Redirect Insert Success
            return redirect()->route('admin.'.$page)
            ->with('status', $pageTitle.' Baru telah berhasil diterbitkan');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
    }

    public function myBalance($id)
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
					if (!empty($transactionPayment)) {
						$myBalance += $transactionPayment->gross_amount;
					}
				}
			}

			if (!empty($balance->withdraw)) {
				$myBalance -= $balance->withdraw->balance;
			}

			if (!empty($balance->ads)) {
				$myBalance -= $balance->ads->balance;
				
			}

			if (!empty($balance->voucher)) {
				$myBalance += $balance->voucher->price;
			}

			if (!empty($balance->ppob)) {
				$myBalance -= $balance->ppob->price;
			}

			if (!empty($balance->transaction)) {
				if ($balance->transaction->status == 1) {
					// Transaction Product
					$transactionProduct = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id)
						->where('user_id', $balance->transaction->seller_id)
						->get();

					$statusTransaction = 0;
					$totalTransaction = 0;
					foreach ($transactionProduct as $transaction) {
						$totalProduct = ($transaction->unit * $transaction->price);
						$totalTransaction += $totalProduct;

						$statusTransaction = $transaction->status;
					}

					// Transaction Shipping
					$transactionShipping = TransactionShipping::where('transaction_id', $balance->transaction->transaction_id)
						->where('user_id', $balance->transaction->seller_id)
						->first();

					if (!empty($transactionShipping))
					{
						$totalTransaction += $transactionShipping->price;
					}

					// Transaction Point
					$transactionProductPoint = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id)
						->where('user_id', $balance->transaction->seller_id)
						->get();
					
					$totalTransactionPoint = 0;
					foreach ($transactionProductPoint as $transactionPoint) {
						$totalTransactionPoint += ($transactionPoint->point * $transactionPoint->point_price);
					}

					$totalTransaction -= $totalTransactionPoint;

					// Transaction Promo
					if ($statusTransaction == 6) {
						if (!empty($balance->transaction->transaction->promo)) {
							$totalTransaction -= $balance->transaction->transaction->promo->price;
						}
					}

					// Status
					$myBalance += $totalTransaction;
				}
				if ($balance->transaction->status == 0) {
					// Transaction Product
					$transactionProduct = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id)
						->get();

					$totalTransaction = 0;
					foreach ($transactionProduct as $transaction) {
						$totalProduct = ($transaction->unit * $transaction->price);
						$totalTransaction += $totalProduct;
					}

					// Transaction Shipping
					$transactionShipping = TransactionShipping::where('transaction_id', $balance->transaction->transaction_id)
						->get();

					foreach ($transactionShipping as $shipping) {
						$totalTransaction += $shipping->price;
					}

					// Transaction Point
					$transactionProductPoint = TransactionProduct::where('transaction_id', $balance->transaction->transaction_id)
						->get();
					
					$totalTransactionPoint = 0;
					foreach ($transactionProductPoint as $transactionPoint) {
						$totalTransactionPoint += ($transactionPoint->point * $transactionPoint->point_price);
					}

					$totalTransaction -= $totalTransactionPoint;

					// Transaction Promo
					// if (!empty($balance->transaction->transaction->promo)) {
					// 	$totalTransaction -= $balance->transaction->transaction->promo->price;
					// }

					// Status
					$myBalance -= $totalTransaction;
				}
			}
		}

		// Return Integer
		return $myBalance;
	}
}
