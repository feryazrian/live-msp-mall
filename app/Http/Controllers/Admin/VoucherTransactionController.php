<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Marketplace\VoucherTransaction;
use Marketplace\VoucherClaim;


use Carbon\Carbon;
use Marketplace\User;

use Marketplace\TransactionProduct;

class VoucherTransactionController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Voucher Transaction';
        $page = 'vouchertransaction';
      

        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }

    public function data(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'Voucher Transaction';
        $page = 'vouchertransaction';
        $from_date=$request->from_date;
        $to_date=$request->to_date;
        $status = $request->status;

        if($from_date != '' || $to_date != ''){
            $items = $this->voucher_report($page,$pageTitle,$request->from_date,$request->to_date,$status);
        }
        else{
            if($status=="") $status = "all";
            $items = $this->voucher_report($page,$pageTitle, Carbon::now()->subDays(100), Carbon::now(),$status);
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'vouchertransaction';
        $pageTitle = 'Voucher Transaction';
        $page = 'vouchertransaction';

        $item = VoucherTransaction::where('id', $id)
            ->first();

        $data =$this->detail_voucher_report($id);
        // dd($data);

        if (empty($item))
        {
            return redirect('/');
        }

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'directory' => $directory,     
            'data'=> $data
        ]);
    }

    public function voucher_report($page,$pageTitle,$from_date,$to_date,$status){
        $items = array();
        if ($status == "all") {
            $data = VoucherTransaction::join('users', 'users.id', '=', 'voucher_transactions.user_id')
                ->select('voucher_transactions.id','voucher_transactions.user_id','voucher_transactions.payment_id','voucher_transactions.transaction_id','voucher_transactions.product_id','voucher_transactions.name AS voucher_name',
                'voucher_transactions.unit AS voucher_unit','voucher_transactions.price AS voucher_price','voucher_transactions.voucher_expired','voucher_transactions.status AS voucher_status',
                'users.name as user_name','users.username as user_username', 'users.email as user_email', 'voucher_transactions.updated_at')
                ->orderBy('voucher_transactions.id', 'DESC')
                ->get();
        } 
        else {
            $data = VoucherTransaction::join('users', 'users.id', '=', 'voucher_transactions.user_id')
                ->select('voucher_transactions.id','voucher_transactions.user_id','voucher_transactions.payment_id','voucher_transactions.transaction_id','voucher_transactions.product_id','voucher_transactions.name AS voucher_name',
                'voucher_transactions.unit AS voucher_unit','voucher_transactions.price AS voucher_price','voucher_transactions.voucher_expired','voucher_transactions.status AS voucher_status',
                'users.name as user_name','users.username as user_username', 'users.email as user_email', 'voucher_transactions.updated_at')
                ->orderBy('voucher_transactions.id', 'DESC')
                ->whereDate('voucher_transactions.updated_at', '>=', $from_date)
                ->whereDate('voucher_transactions.updated_at', '<=', $to_date)
                ->where('voucher_transactions.status','=',$status)
                ->get();   
        }

        foreach ($data as $key => $item) 
        {
            $items[] = array(
                'id'=> $item->id,   
                'payment_id'=> $item->payment_id,                
                'transaction_id'=> $item->transaction_id,                
                'product_id'=> $item->product_id,                
             
                'customer' => ($item)?
                    "<div>Name <b>: ".$item->user_name."</b></div>".
                    "<div>Username <b>: ".$item->user_username."</b></div>".
                    "<div>Email  <b>: ".$item->user_email."</b></div>"
                :
                    "No Users",                  
                'voucher_name'=> $item->voucher_name,                
                'voucher_unit'=> $item->voucher_unit,                
                'voucher_price'=>   ($item->voucher_price >0)?'Rp.'. number_format($item->voucher_price, 2) : $item->voucher_price,  
                'voucher_expired'=> $item->voucher_expired,                
                'voucher_status'=> 
                ($item->voucher_status == 0) ?
                 '<span class="badge badge-warning"> Menunggu Pembayaran </span>' : 
                (
                    ($item->voucher_status == 1) ? 
                    '<span class="badge badge-success"> Transaksi Sukses</span>' :
                    (
                        ($item->voucher_status == 7) ? 
                        '<span class="badge badge-danger"> Transaksi dibatalkan sistem </span>' :
                        '<span class="badge badge-danger"> </span>'

                    )
                ),                
                'updated_at' => ($item)?
                            $item->updated_at ->format('Y-m-d H:i:s')
                        :
                            "",
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split">
                <span class="la la-edit ks-icon"></span><span class="ks-text">Detail '.$pageTitle.'</span></button></a>',
            );
        }
        return $items;
    }


 
    public function detail_voucher_report($id){  
        $items=[];   

        $voucherTransaction = VoucherTransaction::join('users', 'users.id', '=', 'voucher_transactions.user_id')
                ->select('voucher_transactions.id','voucher_transactions.user_id','voucher_transactions.payment_id','voucher_transactions.transaction_id','voucher_transactions.product_id','voucher_transactions.name AS voucher_name',
                'voucher_transactions.unit as voucher_unit','voucher_transactions.price AS voucher_price','voucher_transactions.voucher_expired','voucher_transactions.status AS voucher_status',
                'users.name as user_name','users.username as user_username', 'users.email as user_email', 'voucher_transactions.updated_at')
                ->orderBy('voucher_transactions.id', 'ASC')
                ->where('voucher_transactions.id','=',$id)
                ->first(); 

        $buyer = VoucherTransaction::join('products','products.id','=','voucher_transactions.product_id')
                ->join('users','users.id', '=','products.user_id')
                ->select('users.name as buyer_name','users.username as buyer_username','users.email as buyer_email')
                ->where('voucher_transactions.id','=',$id)
                ->first();
        $vouchers = array();

        if (empty($voucherTransaction)) {
            return redirect('/');
        }  

        if ($voucherTransaction->voucher_status == 1) {    
            // Voucher
            $vouchers = array();
            for ($v = 1; $v <= $voucherTransaction->voucher_unit; $v++) {
                // Initialization
                $timestamp = '-';
                $status = '<span class="badge badge-success">Belum di Klaim</span>';

                // Code
                $code = $voucherTransaction->transaction_id.''.$v;
                $code = abs(crc32(hexdec($code)));

                // Check
                $claim = VoucherClaim::where('code', $code)
                    ->where('transaction_id', $voucherTransaction->id)
                    ->first();
                
                if (!empty($claim)) {
                    $status = '<span class="badge badge-danger">Sudah di Klaim</span>';
                    $timestamp = $claim->created_at->format('Y-m-d H:i:s');
                }

                if (empty($claim)) {
                    // if ($transactionAccess == 1) {
                        $code = '********'.substr($code, -2);
                    // }
                }

                // Response
                $vouchers[] = array(
                    'code' => $code,
                    'status' => $status,
                    'timestamp' => $timestamp
                );
            }
            // $vouchers =  json_encode($vouchers, JSON_FORCE_OBJECT);
            // $vouchers =  json_decode($vouchers);
        }
       
    
        $items= [
            'id'=> $voucherTransaction->id,   
            'payment_id'=> $voucherTransaction->payment_id,                
            'transaction_id'=> $voucherTransaction->transaction_id,                
            'product_id'=> $voucherTransaction->product_id,                
            'customer' => ($voucherTransaction)?
                "<div>Name <b>: ".$voucherTransaction->user_name."</b></div>".
                "<div>Username <b>: ".$voucherTransaction->user_username."</b></div>".
                "<div>Email  <b>: ".$voucherTransaction->user_email."</b></div>"
            :
                "No Users",                  
            'voucher_name'=> $voucherTransaction->voucher_name,                
            'voucher_unit'=> $voucherTransaction->voucher_unit,  
            'voucher_price'=>   $voucherTransaction->voucher_price,
              
            // 'voucher_price'=>   ($voucherTransaction->voucher_price >0)?'Rp.'. number_format($voucherTransaction->voucher_price, 2) : $voucherTransaction->voucher_price,  
            'voucher_expired'=> $voucherTransaction->voucher_expired,                
            'voucher_status'=> $voucherTransaction->voucher_status,             
            'updated_at' => ($voucherTransaction)?
                        $voucherTransaction->updated_at ->format('Y-m-d H:i:s')
                    :
                        "",
            'vouchers'=>$vouchers,
            'voucherTransaction'=>$voucherTransaction,
            'buyer'=>$buyer

        ];
        return $items;
    }
}
