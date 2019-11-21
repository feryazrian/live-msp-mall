<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Marketplace\Http\Controllers\Controller;

use Auth;
use Image;
use Validator;
use File;
use DB;
use Carbon\Carbon;

use Marketplace\User;
use Marketplace\Option;
use Marketplace\AutoDebet;
use Marketplace\Balance;
use Marketplace\BalanceDeposit;
use Marketplace\Transaction;
use Marketplace\TransactionPayment;
use Marketplace\TransactionProduct;
use Marketplace\TransactionShipping;
use Marketplace\TransactionPaymentHistory;

use Hash;


class AutoDebetController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Auto Debet';
        $page = 'autodebet';

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
        $pageTitle = 'Auto Debet';
        $page = 'autodebet';
       
        $lists = AutoDebet::join('users', 'users.id', '=', 'auto_debet.user_id')
            ->select(
                'auto_debet.id','users.id AS user_id','users.name','users.username','users.email','users.photo',
                'auto_debet.jumlah', 
                'auto_debet.tgl_auto_debet', 
                'auto_debet.keterangan', 
                'auto_debet.status', 
                'auto_debet.created_at', 
                'auto_debet.updated_at'
            )
            ->where('users.activated','=','1')
            ->orderBy('auto_debet.id', 'DESC')
            ->get();

      
        foreach ($lists as $item) 
        {
            $items[] = array(
                'id'=> $item->id,
                'users' => ($item)?
                    "<div>Name <b>: ".$item->name."</b></div>".
                    "<div>Username <b>: ".$item->username."</b></div>".
                    "<div>Email  <b>: ".$item->email."</b></div>"
                :
                    "No Users",
                'jumlah'=> "Rp. ".$item->jumlah,
                'user_id'=> $item->user_id,
                'tgl_auto_debet'=> $item->tgl_auto_debet,
                'keterangan'=> $item->keterangan,
                // 'status' => ($item->status === 0 ) ? '<span class="badge badge-info">On Process</span>' :(($item->status === 1 ) ? '<span class="badge badge-success">Approved</span>': '<span class="badge badge-danger">Failed</span>'),
                'status' => ($item->status === 1 ) ? '<span class="badge badge-success">Approved</span>': '<span class="badge badge-danger">Unapproved</span>',
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at'=> $item->updated_at->format('Y-m-d H:i:s'),
                'myBalance'=>$this->myBalance($item->user_id),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split mr-2"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function create(Request $request)
    {
        // Initialization
        $pageTitle = 'Auto Debet';
        $page = 'autodebet';
        $id = $request->id;
        $user = User::orderBy('id', 'ASC')
            ->where('activated','=','1')
            ->get();

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'users'=>$user,
        ]);

    }

    public function autodebet_all_old(Request $request)
    {
        // Initialization
        $user_id = $request->user_id;
        $jumlah = $request->jumlah;
        $tgl_auto_debet = $request->tgl_auto_debet;
        $keterangan = $request->keterangan;
        $status = $request->status;

        $pageTitle = 'Auto Debet';
        $page = 'autodebet';

         // Transaction
         DB::beginTransaction();
         try {
            $all_auto_debet = AutoDebet::where('status', 1)->get();
            foreach($all_auto_debet as $data){
                $now = Carbon::now();
                $now = $now->toDateTimeString();
                $transaction_id = str_random(33);
                $order_id=  rand();

                 // Insert TransactionPayment
                $insert_transaction_payment = new TransactionPayment;
                $insert_transaction_payment->user_id = $data->user_id;
                $insert_transaction_payment->gateway_id = 5;
                $insert_transaction_payment->status_code = 200;
                $insert_transaction_payment->status_message ="success";

                $insert_transaction_payment->transaction_id = $transaction_id;
                $insert_transaction_payment->order_id = $order_id;
                $insert_transaction_payment->gross_amount = $data->jumlah;
                $insert_transaction_payment->payment_type = "layanan AutoDebet Admin"; 
 

                $insert_transaction_payment->transaction_time = $now;   
                $insert_transaction_payment->transaction_status = "settlement";            
                $insert_transaction_payment->fraud_status = "accept";            
                $insert_transaction_payment->save();

                // Insert balance_deposit
                $insert_balance_deposit = new BalanceDeposit;
                $insert_balance_deposit->user_id = $data->user_id;
                $insert_balance_deposit->payment_id = DB::table('transaction_payment')->max('id');
                $insert_balance_deposit->transaction_id = $order_id;
                $insert_balance_deposit->status =1;
                $insert_balance_deposit->save();

                // Insert transactions
                $insert_transactions = new Transaction;
                $insert_transactions->user_id = $data->user_id;
                $insert_transactions->payment_id = DB::table('transaction_payment')->max('id');
                $insert_transactions->gateway_id = 5;
                $insert_transactions->total =$data->jumlah;
                $insert_transactions->save();

                // Insert balance
                $insert_balance = new Balance;
                $insert_balance->user_id = $data->user_id;
                $insert_balance->deposit_id = DB::table('balance_deposit')->max('id');
                $insert_balance->notes = "Penambahan Saldo";
                $insert_balance->save();

                // Transaction Payment Histori
                $insert_transaction_payment_histori= new TransactionPaymentHistory;
                $insert_transaction_payment_histori->user_id = $data->user_id;
                $insert_transaction_payment_histori->gateway_id = 5;
                $insert_transaction_payment_histori->status_code = 200;
                $insert_transaction_payment_histori->status_message = "success";
                $insert_transaction_payment_histori->transaction_id = $transaction_id;
                $insert_transaction_payment_histori->order_id = $order_id;
                $insert_transaction_payment_histori->gross_amount =$data->jumlah;
                $insert_transaction_payment_histori->payment_type = "layanan AutoDebet Admin";
                $insert_transaction_payment_histori->transaction_time = $now;   
                $insert_transaction_payment_histori->transaction_status = "settlement";  
                $insert_transaction_payment_histori->fraud_status = "accept";  
                $insert_transaction_payment_histori->save();
            }
            DB::commit();
            return redirect()->route('admin.'.$page)
            ->with('status', $pageTitle.' Baru telah berhasil diterbitkan');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
    }

    public function autodebet_all(Request $request)
    {
        $count = AutoDebet::orderBy('id', 'ASC')->count();
        if($count == 0){
            return redirect()->back()->with('warning', 'Daftar autodebet masing kosong.');
        }

        // Initialization
        $user_id = $request->user_id;
        $jumlah = $request->jumlah;
        $tgl_auto_debet = $request->tgl_auto_debet;
        $keterangan = $request->keterangan;
        $status = $request->status;

        $pageTitle = 'Auto Debet';
        $page = 'autodebet';

         // Transaction
         DB::beginTransaction();
         try {
            $all_auto_debet = AutoDebet::orderBy('id', 'ASC')
                            ->where('status', 1)->get();
            $tgl_debet = Option::orderBy('id', 'ASC')
                            ->where('type', '=','auto-debet')->first();

            // $month = (int) date("m",strtotime($tgl_debet->content));
            $month = (int) $tgl_debet->content;
            $now = now()->month;
            // dd($month,$now);

            if($month == $now){
                return redirect()->back()->with('warning', 'AutoDebet bulan ini telah dilakukan, silakan melakukan Autodebet bulan depan! ');
            }

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

            foreach($all_auto_debet as $key=>$value){
                $now = Carbon::now();
                $now = $now->toDateTimeString();
                $transaction_id = str_random(33);
                // $order_id= "MSBC". rand();
                $order_id= rand();

                $insert_transaction_payment= [
                    "id"=>$id_payment + $key +1,
                    "user_id"=>$value->user_id,
                    "gateway_id" => 5, 
                    "status_code" => 200, 
                    "status_message" => "success",
                    "transaction_id" => $transaction_id, 
                    "order_id"=>$order_id, 
                    "gross_amount"=>$value->jumlah, 
                    "payment_type"=> "layanan AutoDebet Admin",
                    "transaction_time"=>$now, 
                    "transaction_status"=>"settlement", 
                    "fraud_status"=>"accept",
                    "created_at"=>now(), 
                    "updated_at"=>now()
                ];
                array_push($data_transaction_payment, $insert_transaction_payment);

                $insert_balance_deposit= [
                    "id"=>$id_balance_deposit + $key +1,
                    "user_id"=>$value->user_id,
                    "payment_id" => $id_payment + $key +1, 
                    "transaction_id" => $order_id, 
                    "status" => 1,
                    "created_at"=>now(), 
                    "updated_at"=>now()
                ];
                array_push($data_balance_deposit, $insert_balance_deposit);

                $insert_transactions= [
                    "id"=>$id_transactions + $key +1,
                    "user_id"=>$value->user_id,
                    "payment_id" => $id_payment + $key +1, 
                    "gateway_id" => 5, 
                    "total" => $value->jumlah,
                    "created_at"=>now(), 
                    "updated_at"=>now()
                ];
                array_push($data_transactions, $insert_transactions);
            
                $insert_balance= [
                    "id"=>$id_balance + $key +1,
                    "user_id"=>$value->user_id,
                    "deposit_id" => $id_balance_deposit + $key +1, 
                    "notes" => "Penambahan Saldo", 
                    "created_at"=>now(), 
                    "updated_at"=>now()
                ];
                array_push($data_balance, $insert_balance);
            
                $insert_transaction_payment_histori= [
                    "id"=>$id_transaction_payment_histori + $key +1,
                    "user_id"=>$value->user_id,
                    "gateway_id"=>5,
                    "status_code"=>200,
                    "status_message"=>"success",
                    "transaction_id"=>$transaction_id,
                    "order_id"=>$order_id,
                    "gross_amount"=>$value->jumlah,
                    "payment_type"=> "layanan AutoDebet Admin",
                    "transaction_time"=>$now,
                    "transaction_status"=>"settlement",
                    "fraud_status" => "accept", 
                    "created_at"=>now(), 
                    "updated_at"=>now()
                ];
                array_push($data_transaction_payment_histori, $insert_transaction_payment_histori);
            }
            $transactionPayment = TransactionPayment::insert($data_transaction_payment);
            $balanceDeposit = BalanceDeposit::insert($data_balance_deposit);
            $transaction = Transaction::insert($data_transactions);
            $balance = Balance::insert($data_balance);
            $transactionPaymentHistori = TransactionPaymentHistory::insert($data_transaction_payment_histori);

            //Update Tanggal AutoDebet 
            $update = AutoDebet::where('status', 1)->update(['tgl_auto_debet' => $now]);
            $update = Option::where('type', 'auto-debet')->update(['content' => now()->month]);

            DB::commit();
            return redirect()->route('admin.'.$page)
            ->with('status', $pageTitle.' berhasil dikirimkan');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'user_id' => 'required|max:255',
            'jumlah' => 'required|max:255',
            // 'tgl_auto_debet' => 'required|date',
            // 'keterangan' => 'required|max:255',
            'status' => 'required|max:255',
        ]);

        // Initialization
        $user_id = $request->user_id;
        $jumlah = $request->jumlah;
        $tgl_auto_debet = $request->tgl_auto_debet;
        $keterangan = $request->keterangan;
        $status = $request->status;

        $pageTitle = 'Auto Debet';
        $page = 'autodebet';
        $check_user = AutoDebet::orderBy('id', 'ASC')
            ->where('user_id','=',$user_id)
            ->first();
        if(!empty($check_user)){
            return redirect()->back()->with('warning', 'user telah didaftarkan ke dalam table Autodebet');
        }
     
       
         // Transaction
         DB::beginTransaction();
         try {
            $now = Carbon::now();
            $now = $now->toDateTimeString();
            $transaction_id = str_random(33);
            $order_id= "MSBC". time();

            // Insert AutoDebet
            $insert = new AutoDebet;
            $insert->user_id = $user_id;
            $insert->jumlah = $jumlah;
            $insert->tgl_auto_debet = ($tgl_auto_debet)?$tgl_auto_debet:"";
            $insert->keterangan = ($keterangan)?$keterangan:"";
            $insert->status = $status;            
            $insert->save();
            
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

    public function update_auto_debet(Request $request){
        $tgl_auto_debet = $request->tgl_auto_debet;
        DB::beginTransaction();
        try {
            $update = AutoDebet::where('status', 1)->update(['tgl_auto_debet' => $tgl_auto_debet]);
            $pageTitle = 'Auto Debet';
            $page = 'autodebet';

            // dd($update);
            DB::commit();
            return redirect()->route('admin.'.$page)
            ->with('status', $pageTitle.' Baru telah berhasil diperbarui');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }
    }

    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'autodebet';
        $pageTitle = 'Auto Debet';
        $page = 'autodebet';
        // Check
        $item = AutoDebet::where('id', $id)
            ->first();

        $user = User::orderBy('id', 'ASC')
            ->where('activated','=','1')
            ->where('id','=',$item->user_id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'item' => $item,
            'users' => $user,
            'directory' => $directory,
         
        ]);
    }

    public function update(Request $request)
    {
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            // 'title' => 'required|max:255',
            // 'description' => 'required|max:255',
            // 'image_path' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Initialization
        $id = $request->id;
        // $user_id = $request->user_id;
        $jumlah = $request->jumlah;
        // $tgl_auto_debet = $request->tgl_auto_debet;
        // $keterangan = $request->keterangan;
        $status = $request->status;

        $pageTitle = 'Auto Debet';
        $page = 'autodebet';

         // Transaction
         DB::beginTransaction();
         try {
              // Check
            $item = AutoDebet::where('id', $id)
                ->first();

            if (empty($item))
            {
                return redirect('/');
            }


              // Update
            $update = AutoDebet::where('id', $id)->update([
                // 'user_id' =>  $user_id,
                'jumlah' =>  $jumlah,
                // 'tgl_auto_debet' =>  $tgl_auto_debet,
                // 'keterangan' =>  $keterangan,
                'status' =>  $status,
            ]);

           
            DB::commit();
            // Return Redirect Insert Success
            return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }    
    }
    public function delete(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $pageTitle = 'Auto Debet';
        $page = 'autodebet';

        
        // Delete
        DB::beginTransaction();
        try {
             // Check
            $item = AutoDebet::where('id', $id)
            ->first();

            if (empty($item))
            {
                return redirect('/');
            }

            $item = AutoDebet::where('id', $id)->delete();
            DB::commit();
            // Return Redirect Delete Success
            return redirect()->route('admin.'.$page)
                ->with('status', 'Hapus '.$pageTitle.' telah berhasil');

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
