<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;


use Marketplace\Transaction;
use Carbon\Carbon;
use Marketplace\User;

use Marketplace\TransactionProduct;

class SalesReportController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Sales Report';
        $page = 'salesreport';
      

        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }

    public function data(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'Sales Report';
        $page = 'salesreport';
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

    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'salesreport';
        $pageTitle = 'Sales Report';
        $page = 'salesreport';

        $item = Transaction::where('id', $id)
            ->first();

        $data =$this->detail_sales_report($id);

        if (empty($item))
        {
            return redirect('/');
        }

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'directory' => $directory,     
            'item' => $item,
            'data'=>$data
        ]);
    }

    public function sales_report($page,$pageTitle,$from_date,$to_date,$status){
        $items = array();

        if ($status == "all") {
            $data = Transaction::join('users', 'users.id', '=', 'transactions.user_id')
                ->leftjoin('transaction_promo','transaction_promo.id','=', 'transactions.promo_id') 
                ->leftjoin('transaction_gateways','transaction_gateways.id','=', 'transactions.gateway_id')
                ->leftjoin('transaction_payment','transaction_payment.id','=', 'transactions.payment_id')
                ->select(
                    'transactions.id','transactions.user_id','transactions.address_id','transactions.payment_id','transactions.gateway_id','transactions.promo_id','transactions.total','transactions.created_at',                 
                    // 'transaction_products.user_id AS user_merchant_id','transaction_products.status',
                    'users.id AS cust_id','users.name AS cust_name','users.username AS cust_username','users.email AS cust_email','users.identity_photo AS cust_identity_photo',
                    'transaction_promo.code as promo_code','transaction_gateways.name as transaction_gateways_name',
                    'transaction_payment.transaction_status','transaction_payment.transaction_time as transaction_time'
                )
                ->where('transactions.total','>',0) //Anonim Shop
                ->whereDate('transaction_payment.transaction_time', '>=', $from_date)
                ->whereDate('transaction_payment.transaction_time', '<=', $to_date)
                ->where('transaction_payment.transaction_status','!=',null)
                ->where('transactions.address_id','!=',null)
                ->orderBy('transactions.id', 'DESC')
                ->get();
        } 
        else {
            $data = Transaction::join('users', 'users.id', '=', 'transactions.user_id')
                ->leftjoin('transaction_promo','transaction_promo.id','=', 'transactions.promo_id') 
                ->leftjoin('transaction_gateways','transaction_gateways.id','=', 'transactions.gateway_id')
                ->leftjoin('transaction_payment','transaction_payment.id','=', 'transactions.payment_id')
                ->select(
                    'transactions.id','transactions.user_id','transactions.address_id','transactions.payment_id','transactions.gateway_id','transactions.promo_id','transactions.total','transactions.created_at',                 
                    // 'transaction_products.user_id AS user_merchant_id','transaction_products.status',
                    'users.id AS cust_id','users.name AS cust_name','users.username AS cust_username','users.email AS cust_email','users.identity_photo AS cust_identity_photo',
                    'transaction_promo.code as promo_code','transaction_gateways.name as transaction_gateways_name',
                    'transaction_payment.transaction_status','transaction_payment.transaction_time as transaction_time'
                )
                ->where('transactions.total','>',0) //Anonim Shop
                ->whereDate('transaction_payment.transaction_time', '>=', $from_date)
                ->whereDate('transaction_payment.transaction_time', '<=', $to_date)
                ->where('transaction_payment.transaction_status','=',$status)
                ->where('transaction_payment.transaction_status','!=',null)
                ->where('transactions.address_id','!=',null)
                ->orderBy('transactions.id', 'DESC')
                ->get();        
        }
    
        foreach ($data as $key => $item) 
        {

            // $user_merchant = User::where('id','=',$item->user_merchant_id)->first();
            $items[] = array(
                'id'=> $item->id,                
                'customer' => ($item)?
                    "<div>Name <b>: ".$item->cust_name."</b></div>".
                    "<div>Username <b>: ".$item->cust_username."</b></div>".
                    "<div>Email  <b>: ".$item->cust_email."</b></div>"
                :
                    "No Users",
                'total'=>   ($item->total >0)?'Rp.'. number_format($item->total, 2) : $item->total,   
                'promo_code' => ($item->promo_code != '') ? $item->promo_code : '-',
                'transaction_gateways_name'=>$item->transaction_gateways_name,
                'transaction_status'=> ($item->transaction_status == "settlement" || $item->transaction_status == "capture") ? 
                '<span class="badge badge-success">'.$item->transaction_status .'</span>' : (($item->transaction_status == "cancel" || $item->transaction_status == "expire") ?
                '<span class="badge badge-danger">'.$item->transaction_status .'</span>' :'<span class="badge badge-warning">'.$item->transaction_status .'</span>') ,
                'transaction_time' => ($item)?
                            $item->transaction_time
                        :
                            "",
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split">
                <span class="la la-edit ks-icon"></span><span class="ks-text">Detail '.$pageTitle.'</span></button></a>',
            );
        }
        return $items;
    }

    public function detail_sales_report($id){  
        $items=[];     
        $data = Transaction::join('users', 'users.id', '=', 'transactions.user_id')
                ->leftjoin('transaction_shipping','transaction_shipping.transaction_id','=', 'transactions.id') 
                ->leftjoin('transaction_address','transaction_address.id','=', 'transactions.address_id') 
                ->leftjoin('transaction_payment','transaction_payment.id','=', 'transactions.payment_id')
                ->leftjoin('transaction_promo','transaction_promo.id','=', 'transactions.promo_id') 
                ->leftjoin('area_provinces','area_provinces.id','=', 'transaction_address.provinsi_id') 
                ->leftjoin('area_regencies','area_regencies.id','=', 'transaction_address.kabupaten_id') 
                ->leftjoin('area_districts','area_districts.id','=', 'transaction_address.kecamatan_id') 

                ->select(
                    'transactions.id','transactions.user_id','transactions.address_id','transactions.payment_id','transactions.gateway_id','transactions.promo_id','transactions.total','transactions.created_at',                 
                    'users.id AS cust_id','users.name AS cust_name','users.username AS cust_username','users.email AS cust_email','users.identity_photo AS cust_identity_photo',
                    'transaction_shipping.description AS shipping_description','transaction_shipping.price AS shipping_price','transaction_shipping.service As shipping_service','transaction_shipping.code AS shipping_code',
                    'transaction_address.dropshipper_name','transaction_address.dropshipper_phone','transaction_address.address_name','transaction_address.first_name','transaction_address.last_name',
                    'transaction_address.phone','transaction_address.address','transaction_address.postal_code','area_provinces.name AS nama_provinsi','area_districts.name AS nama_kecamatan','area_regencies.name AS nama_kabupaten',
                    'transaction_promo.type AS promo_type','transaction_promo.name as promo_name','transaction_promo.code as promo_code','transaction_promo.expired as promo_expired','transaction_promo.price as promo_price',
                    'transaction_payment.transaction_status','transaction_payment.transaction_time'
                )
                ->where('transactions.id','=',$id)
                ->orderBy('transactions.id', 'DESC')
                ->first();
        $transaction_product = TransactionProduct::where ('transaction_id','=',$id)
                ->join('users','users.id','=','transaction_products.user_id')
                ->select('users.name as merchant_name','transaction_products.user_id AS user_merchant_id','transaction_products.name','transaction_products.unit','transaction_products.price','transaction_products.preorder','transaction_products.point',
                'transaction_products.point_price','transaction_products.notes','transaction_products.status','transaction_products.cancel')
                ->orderBy('merchant_name')
                ->orderBy('transaction_id', 'DESC')
                ->get();
        $items= [
            "data_transaksi"=>$data,
            "data_transaction_product"=>$transaction_product
        ];
        return $items;
    }
}
