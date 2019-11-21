<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
use Marketplace\Http\Controllers\BalanceController;

use Auth;
use Image;
use Validator;
use File;
use Curl;
use DB;
use Marketplace\PpobTransaction;
use Marketplace\User;
use Marketplace\PpobType;
use Marketplace\PpobOperator;
use Marketplace\PpobPlan;
use Marketplace\TransactionPayment;

class DataController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Data';
        $page = 'data';

        $sum_monswallet = $this->info_sum_monswallet(1,1,2); 
        $sum_monswallet_failed = $this->info_sum_monswallet(1,2,2); 
        $sum_lifepoint = $this->info_sum_monswallet(1,1,3); 
        $sum_lifepoint_failed =$this->info_sum_monswallet(1,2,3); 
    
        $monswallet = $this->info_count_monswallet(1,1,2); 
        $monswallet_failed =$this->info_count_monswallet(1,2,2); 
        $lifepoint = $this->info_count_monswallet(1,1,3); 
        $lifepoint_failed = $this->info_count_monswallet(1,2,3); 

        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'monswallet' => $monswallet,
            'monswallet_failed' => $monswallet_failed,
            'lifepoint' => $lifepoint,
            'lifepoint_failed' => $lifepoint_failed,
            'sum_lifepoint'=>$sum_lifepoint->sum,
            'sum_lifepoint_failed' =>$sum_lifepoint_failed->sum,
            'sum_monswallet'=>$sum_monswallet->sum,
            'sum_monswallet_failed'=>$sum_monswallet_failed->sum,
        ]);
    }

    public function show_data()
    {
        // Initialization
        $pageTitle = 'Data';
        $page = 'data';
        
        // Return View
        return view('admin.'.$page.'.data')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }

    public function data(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'Data';
        $page = 'data';

        


        if($request->from_date != '' || $request->to_date != ''  ){
            $data = PpobTransaction::join('users', 'users.id', '=', 'ppob_transactions.user_id')
            ->join('ppob_types','ppob_types.id','=', 'ppob_transactions.type_id')
            ->join('ppob_operators','ppob_operators.id','=', 'ppob_transactions.operator_id')
            ->join('ppob_plans','ppob_plans.id','=', 'ppob_transactions.plan_id')
            ->join('transaction_payment','transaction_payment.id','=', 'ppob_transactions.payment_id')
            ->join('transaction_gateways','transaction_gateways.id','=', 'transaction_payment.gateway_id')
            ->select(
                'ppob_transactions.id','users.id AS user_id','users.name','users.username','users.email','users.photo',
                'ppob_types.id AS ppob_type_id','ppob_types.name AS ppob_types_name','ppob_transactions.status AS ppob_transactions_status','ppob_types.slug',
                'ppob_plans.name AS plan_name','ppob_transactions.product','ppob_transactions.type_id',
                'ppob_transactions.cust_number', 'ppob_transactions.price',
                'transaction_payment.gateway_id as gateway_id','transaction_payment.status_code',
                'transaction_payment.status_message','transaction_payment.gross_amount',
                'transaction_payment.payment_type','transaction_payment.transaction_status','ppob_transactions.created_at AS waktu',
                'transaction_gateways.title as gateways_name'
            )

            // ->where('ppob_transactions.user_id','=',595) //Kelvin
            ->where('ppob_transactions.type_id','=',1) // 1=data 2=pulsa
            ->whereDate('ppob_transactions.created_at', '>=', $request->from_date)
            ->whereDate('ppob_transactions.created_at', '<=', $request->to_date)
            // ->where('ppob_transactions.status','=',1) // 1=berhasil 2=gagal 0=pending 
            // ->where('transaction_payment.gateway_id','=',3) // 2=mons wallet 3=life point
            ->orderBy('ppob_transactions.id', 'DESC')
            ->get();
            
        }
        else{
            $data = PpobTransaction::join('users', 'users.id', '=', 'ppob_transactions.user_id')
            ->join('ppob_types','ppob_types.id','=', 'ppob_transactions.type_id')
            ->join('ppob_operators','ppob_operators.id','=', 'ppob_transactions.operator_id')
            ->join('ppob_plans','ppob_plans.id','=', 'ppob_transactions.plan_id')
            ->join('transaction_payment','transaction_payment.id','=', 'ppob_transactions.payment_id')
            ->join('transaction_gateways','transaction_gateways.id','=', 'transaction_payment.gateway_id')
            ->select(
                'ppob_transactions.id','users.id AS user_id','users.name','users.username','users.email','users.photo',
                'ppob_types.id AS ppob_type_id','ppob_types.name AS ppob_types_name','ppob_transactions.status AS ppob_transactions_status','ppob_types.slug',
                'ppob_plans.name AS plan_name','ppob_transactions.product','ppob_transactions.type_id',
                'ppob_transactions.cust_number', 'ppob_transactions.price',
                'transaction_payment.gateway_id as gateway_id','transaction_payment.status_code',
                'transaction_payment.status_message','transaction_payment.gross_amount',
                'transaction_payment.payment_type','transaction_payment.transaction_status','ppob_transactions.created_at AS waktu',
                'transaction_gateways.title as gateways_name'
            )

            // ->where('ppob_transactions.user_id','=',595) //Kelvin
            ->where('ppob_transactions.type_id','=',1) // 1=data 2=pulsa
            // ->where('ppob_transactions.status','=',1) // 1=berhasil 2=gagal 0=pending 
            // ->where('transaction_payment.gateway_id','=',3) // 2=mons wallet 3=life point
            ->orderBy('ppob_transactions.id', 'DESC')
            ->get();
        }
      
        // $json_pulsa = $this->pricelist("pulsa");

        $json_data = $this->pricelist()->data;    
        foreach ($data as $key => $item) 
        {
            $pricelistdetail = collect($json_data)->where("pulsa_code","=",$item->product)->first();

            $items[] = array(
                'id'=> $item->id,
                'user_id'=> $item->user_id,
            
                'product'=> ($item->ppob_type_id == 1)?
                        $pricelistdetail->pulsa_op." ".
                        $pricelistdetail->pulsa_nominal. ", " .
                        $pricelistdetail->masaaktif."hari"."</b></div>"
                    :
                        $pricelistdetail->pulsa_op." ".
                        $pricelistdetail->pulsa_nominal,

                'users' => ($item)?
                    "<div>Name <b>: ".$item->name."</b></div>".
                    "<div>Username <b>: ".$item->username."</b></div>".
                    "<div>Email  <b>: ".$item->email."</b></div>"
                :
                    "No Users",

                'type_id'=> $item->ppob_type_id,
                'cust_number'=> $item->cust_number,
                'price'=> "Rp. ".$item->price,
                'status' => ($item->ppob_transactions_status === 0 ) ? '<span class="badge badge-info">On Process</span>' :(($item->ppob_transactions_status === 1 ) ? '<span class="badge badge-success">Approved</span>': '<span class="badge badge-danger">Failed</span>'),
                'response_json'=> $item->product,
                'created_at'=> $item->waktu,
                'payment_type'=> $item->gateways_name,
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }


    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'data';
        $pageTitle = 'Data';
        $page = 'data';

        // Check
        $item = PpobTransaction::where('id', $id)
            ->first();

        $users = User::where('id',$item->user_id)
            ->select('name','username','email','photo')
            ->first();
        $ppob_types = PpobType::where('id' ,$item->type_id)
            ->select('id','name','status','slug')
            ->first();
        $plan = PpobPlan::where('id' ,$item->plan_id)
            ->select('name')
            ->first();
        $transaction_payment = TransactionPayment::where('user_id' ,$item->user_id)    
            ->first();
        $json_pulsa = $this->pricelist()->data;
        $pricelistdetail = collect($json_pulsa)->where("pulsa_code","=",$item->product)->first();

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
            'users' => $users,
            'ppob_types' => $ppob_types,
            'plan' => $plan,
            'transaction_payment' => $transaction_payment,
            'pricelistdetail'=>$pricelistdetail

        ]);
    }
    public function update(Request $request)
    {
        // Path
        $public = str_replace(config('app.public_path'), config('app.storage_path'), public_path('/'));

        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        // $name = $request->name;
        $content = null;
        $pageTitle = 'Data';
        $page = 'data';
        
        
        // Check
        $item = PpobTransaction::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction
        DB::beginTransaction();
        try {
        // Update
            $update = PpobTransaction::where('id', $id)->update([
                'status' => $request->status,
            ]);
            DB::commit();
            return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }       
    }

    public function pricelist(){
        $username = env("MOBILE_PULSA_USERNAME");
        $apiKey = env("MOBILE_PULSA_KEY");
        $signature  = md5($username.$apiKey."pl");

        $header = [
            "commands" => "pricelist",
            "username" => $username,
            "sign"     => $signature,
            "status"   => "all"
        ];
       
        $responseApi = Curl::to(env("MOBILE_PULSA_URI")."/data")
                ->withData( $header )
                ->asJson()
                ->post();
        return  $responseApi;
    }

    public function info_sum_monswallet($type_id, $status, $gateway_id){
        // $type_id      =  1=data 2=pulsa
        // $status       =  1=berhasil 2=gagal 0=pending 
        // $gateway_id   =  2=mons wallet 3=life point
        $result="";
        $result = PpobTransaction::join('transaction_payment','transaction_payment.id','=', 'ppob_transactions.payment_id')
            ->join('transaction_gateways','transaction_gateways.id','=', 'transaction_payment.gateway_id')
            ->select(DB::raw("SUM(ppob_transactions.price) AS sum"))
            ->where('ppob_transactions.type_id','=',$type_id) // 1=data 2=pulsa
            ->where('ppob_transactions.status','=',$status) // 1=berhasil 2=gagal 0=pending 
            ->where('transaction_payment.gateway_id','=',$gateway_id) // 2=mons wallet 3=life point
            // ->where('ppob_transactions.user_id','=',595) //Kelvin
            ->first();
        return $result;
    }

    public function info_count_monswallet($type_id, $status, $gateway_id){
        // $type_id      =  1=data 2=pulsa
        // $status       =  1=berhasil 2=gagal 0=pending 
        // $gateway_id   =  2=mons wallet 3=life point
        $result=0;
        $result =  PpobTransaction::join('transaction_payment','transaction_payment.id','=', 'ppob_transactions.payment_id')
            ->join('transaction_gateways','transaction_gateways.id','=', 'transaction_payment.gateway_id')
            ->where('ppob_transactions.type_id','=',$type_id) // 1=data 2=pulsa
            ->where('ppob_transactions.status','=',$status) // 1=berhasil 2=gagal 0=pending 
            ->where('transaction_payment.gateway_id','=',$gateway_id) // 2=mons wallet 3=life point
            ->count();
        return $result;
    }
}
