<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
use Marketplace\Transaction;
use Marketplace\User;
use Marketplace\TransactionProduct;
use Carbon\Carbon;

class MonsWalletHistoryController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'MonsWallet History';
        $page = 'monswallethistory';
      
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }

    public function data(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'MonsWallet History';
        $page = 'monswallethistory';
        $from_date=$request->from_date;
        $to_date=$request->to_date;
        $status = $request->status;

        if($from_date != '' || $to_date != ''){
            $items = $this->sales_report($page,$pageTitle,$request->from_date,$request->to_date,$status);
        }
        else{
            if(empty($status)) $status = "all";
            $items = $this->sales_report($page,$pageTitle, Carbon::now()->subDays(100), Carbon::now(),$status);
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function sales_report($page,$pageTitle,$from_date,$to_date,$status){
        $items = array();
        if ($status == "all") {
            $data = Transaction::join('users', 'users.id', '=', 'transactions.user_id')
                ->leftjoin('transaction_gateways','transaction_gateways.id','=', 'transactions.gateway_id')
                ->leftjoin('transaction_payment','transaction_payment.id','=', 'transactions.payment_id')
                ->leftjoin('balance_deposit','balance_deposit.payment_id','=', 'transaction_payment.id')
                ->leftjoin('balances','balances.deposit_id','=', 'balance_deposit.id')
                ->select(
                    'transactions.id','transactions.user_id','transactions.payment_id','transactions.gateway_id','transactions.total','transactions.created_at as transactions_created',                 
                    'transaction_payment.payment_type','transaction_payment.transaction_status','transaction_payment.transaction_time as transaction_time',
                    'balance_deposit.transaction_id','balance_deposit.status as balance_deposit_status',
                    'balances.notes AS balance_notes',
                    'users.id AS cust_id','users.name AS cust_name','users.username AS cust_username','users.email AS cust_email','users.identity_photo AS cust_identity_photo',
                    'transaction_gateways.name as transaction_gateways_name'
                )
                ->whereDate('transaction_payment.transaction_time', '>=', $from_date)
                ->whereDate('transaction_payment.transaction_time', '<=', $to_date)
                ->where('balance_deposit.transaction_id','!=',null)
                ->where('balance_deposit.transaction_id', 'LIKE', "MSBC%") 
                ->orderBy('transactions.id', 'DESC')
                ->get();
        } 
        else {
            $data = Transaction::join('users', 'users.id', '=', 'transactions.user_id')
                ->leftjoin('transaction_gateways','transaction_gateways.id','=', 'transactions.gateway_id')
                ->leftjoin('transaction_payment','transaction_payment.id','=', 'transactions.payment_id')
                ->leftjoin('balance_deposit','balance_deposit.payment_id','=', 'transaction_payment.id')
                ->leftjoin('balances','balances.deposit_id','=', 'balance_deposit.id')
                ->select(
                    'transactions.id','transactions.user_id','transactions.payment_id','transactions.gateway_id','transactions.total','transactions.created_at as transactions_created',                 
                    'transaction_payment.payment_type','transaction_payment.transaction_status','transaction_payment.transaction_time as transaction_time',
                    'balance_deposit.transaction_id','balance_deposit.status as balance_deposit_status',
                    'balances.notes AS balance_notes',
                    'users.id AS cust_id','users.name AS cust_name','users.username AS cust_username','users.email AS cust_email','users.identity_photo AS cust_identity_photo',
                    'transaction_gateways.name as transaction_gateways_name'
                )
                ->whereDate('transaction_payment.transaction_time', '>=', $from_date)
                ->whereDate('transaction_payment.transaction_time', '<=', $to_date)
                ->where('transaction_payment.payment_type','=',$status)
                ->where('balance_deposit.transaction_id','!=',null)
                ->where('balance_deposit.transaction_id', 'LIKE', "MSBC%") 
                ->orderBy('transactions.id', 'DESC')
                ->get();   
        }
    
        foreach ($data as $key => $item) 
        {
            $items[] = array(
                'id'=> $item->id,                
                'customer' => ($item)?
                    "<div>Name <b>: ".$item->cust_name."</b></div>".
                    "<div>Username <b>: ".$item->cust_username."</b></div>".
                    "<div>Email  <b>: ".$item->cust_email."</b></div>"
                :
                    "No Users",
                'total'=>   ($item->total >0)?'Rp.'. number_format($item->total, 2) : $item->total,   
                'transaction_id'=>   $item->transaction_id,
                'transaction_gateways_name'=>$item->transaction_gateways_name,
                'payment_type'=>$item->payment_type,
                'balance_notes' => $item->balance_notes,
                'transaction_status'=> ($item->transaction_status == "settlement" || $item->transaction_status == "capture") ? 
                '<span class="badge badge-success">'.$item->transaction_status .'</span>' : (($item->transaction_status == "cancel" || $item->transaction_status == "expire") ?
                '<span class="badge badge-danger">'.$item->transaction_status .'</span>' :'<span class="badge badge-warning">'.$item->transaction_status .'</span>') ,
                'transaction_time' => ($item)?
                            $item->transaction_time
                        :
                            "",
                // 'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split">
                // <span class="la la-edit ks-icon"></span><span class="ks-text">Detail '.$pageTitle.'</span></button></a>',
            );
        }
        return $items;
    }
}
