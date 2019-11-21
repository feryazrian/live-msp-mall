<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Auth;
use Image;
use Validator;
use File;
use DB;

use Marketplace\Promo;
use Marketplace\PromoType;
use Marketplace\ProductType;
use Marketplace\PpobType;
use Marketplace\PpobPromo;
use Marketplace\PromoProductType;
use Marketplace\Transaction;

class PromoController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Kode Promo';
        $page = 'promo';

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
        $pageTitle = 'Kode Promo';
        $page = 'promo';

        // Lists
        $lists = Promo::orderBy('updated_at', 'DESC')->get();
        $transPromo = Transaction::join('transaction_promo', 'transaction_promo.id', '=', 'transactions.promo_id')
            ->join('transaction_payment', 'transaction_payment.id', '=', 'transactions.payment_id')
            ->whereNotNull('transactions.promo_id')
            ->whereNotNull('transactions.payment_id')
            ->where('transaction_payment.transaction_status', 'settlement')
            ->groupBy('transaction_promo.promo_id')
            ->selectRaw('count(*) AS used, transaction_promo.promo_id')
            ->get();

        foreach ($lists as $item) 
        {
            $quota = $transPromo->where('promo_id', $item->id)->first();
            $quotaUsed = ($quota) ? $quota->used : 0;
            $btnUsedList = ($quotaUsed > 0) ? '<a href="'.route('admin.'.$page.'.used-list', ['id' => $item->id]).'"><button class="btn btn-success ks-split mr-2"><span class="la la-search ks-icon"></span><span class="ks-text">Daftar Pengguna</span></button></a>' : '';
            // Array
            $items[] = array(
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'type' => $item->type->name,
                'expired' => $item->expired,
                'quota' => $item->quota,
                'quota_used' => $quotaUsed,
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split mr-2"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>' . $btnUsedList,
            );
        }

        // Return Array
        return array('aaData' => $items);
    }


    public function create(Request $request)
    {
        // Initialization
        $type = $request->type;
        $pageTitle = 'Kode Promo';
        $page = 'promo';
        
        // Check
        // $type = PromoType::where('id', $type)
        //     ->first();
        $promo_type  = PromoType::where('id', $type)
        ->get();
        $type = $promo_type[0];
        $ppob_type = PpobType::orderBy('id', 'ASC')
            ->where('status','=','1')
            ->get();
        $discount_type = DB::table('discount_type')->get();
        $product_type = ProductType::get();

        if (empty($type))
        {
            return redirect('/');
        }

        $pageTitle = 'Kode Promo - '.$type->name;

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'type' => $type,
            'ppob_type' => $ppob_type,
            'discount_type'=>$discount_type,
            'product_type'=>$product_type,
            'promo_type'=>$promo_type
        ]);
    }

    public function store(Request $request)
    {
       
       
        $validated = $request->validate([
            // 'type_id' => 'required|integer',
            'name' => 'required|max:255',
            'code' => 'required|max:255',
            'expired' => 'required|date',
            'transaction_min' => 'required|numeric',
            'quota' => 'required|numeric',
            'term_condition' => 'required',
        ]);

        $input = $request->all();

        $shipping_code = null;
        $discount_price=null;
        $discount_max = null;
        $discount_percent=null;
        $term_condition=null;

        if($request->has('shipping_code')){
            $shipping_code = $request->shipping_code;
        }
        if($request->type_id == 1){
            $discount_price = $request->discount_price;
            $discount_max = null;
            $discount_percent = null;

        }
        if($request->type_id == 2){
            $discount_max = $request->discount_max;
            $discount_percent = $request->discount_percent;
            $discount_price = null;

        }
        if($request->type_id == 3){
            if($request->check_type == 0){
                $discount_price = $request->discount_price;
                $discount_max = null;
                $discount_percent = null;
            }
            else{
                $discount_max = $request->discount_max;
                $discount_percent = $request->discount_percent;
                $discount_price = null;
            }
        }
        
        if($request->has('term_condition')){
            $term_condition = $request->term_condition;
        }
        else{
            $term_condition = "";
        }
       
        $pageTitle = 'Kode Promo';
        $page = 'promo';

        // Transaction
        DB::beginTransaction();
        try {
            // Insert
            $inputPromo = [
                'user_id'           =>Auth::user()->id,
                'type_id'           =>$input['type_id'],
                'name'              =>$input['name'],
                'code'              =>$input['code'],
                'expired'           =>$input['expired'],
                'discount_type_id'  =>$input['discount_type_id'],
                'transaction_min'   =>$input['transaction_min'],
                'discount_price'    =>$discount_price,
                'shipping_code'     =>$shipping_code,
                'discount_max'      =>$discount_max,
                'discount_percent'  =>$discount_percent,
                'quota'             =>$input['quota'],
                'total_quota'       =>$input['total_quota'],
                'quota_user_total'  =>$input['quota_user_total'],
                'quota_user_day'    =>$input['quota_user_day'],
                'term_condition'    =>$term_condition,
            ];
            $insert = Promo::create($inputPromo);
            if($request->has('ppob_type')){
                $ppob_type = $request->ppob_type;
                foreach($ppob_type as $p){
                    $arr = new PpobPromo;
                    $arr->promo_id = $insert->id;
                    $arr->ppob_type_id= $p;
                    $arr->save();
                 }
                //  $inputPpobPromo = PpobPromo::create();
            }
            if(isset($request->product_type)){
                foreach($request->product_type as $product_type){
                    $arr = new PromoProductType;
                    $arr->promo_id = $insert->id;
                    $arr->product_type_id= $product_type;
                    $arr->save();
                }
            }
    
               
            DB::commit();
            // Return Redirect Insert Success
            return redirect()->route('admin.'.$page)
                ->with('status', $pageTitle.' Baru telah berhasil diterbitkan');
        }
        catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $pageTitle = 'Kode Promo';
        $page = 'promo';
        
        // Check
        $item = Promo::where('id', $id)
            ->first();
      
        if (empty($item))
        {
            return redirect('/');
        }

        $ppob_type = PpobType::orderBy('id', 'ASC')
            ->where('status','=','1')
            ->get();
        $ppob_type_check = PpobPromo::orderBy('id', 'ASC')
            ->where('promo_id','=',$id)
            ->select("ppob_type_id")
            ->get();
        $promo_type = PromoType::orderBy('id', 'ASC')
            ->get();
        $product_type = ProductType::get();
        $discount_type = DB::table('discount_type')->get();


        // dd($item,$ppob_type,$ppob_type_check,$promo_type);
        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'item' => $item,
            'ppob_type' => $ppob_type,
            'ppob_type_check' => $ppob_type_check,
            'promo_type'=>$promo_type,
            'discount_type'=>$discount_type,
            'product_type'=>$product_type
        ]);
    }

    public function usedList($id)
    {
        // Initialization
        $pageTitle = 'Daftar Penggunaan Kode Promo';
        $page = 'promo';

        // Check
        $promo = Promo::where('id', $id)->with('type')->first();

        $totalPerPayment = Transaction::join('transaction_promo', 'transaction_promo.id', '=', 'transactions.promo_id')
            ->join('transaction_payment', 'transaction_payment.id', '=', 'transactions.payment_id')
            ->join('transaction_gateways', 'transaction_gateways.id', '=', 'transaction_payment.gateway_id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transaction_promo.promo_id', $id)
            ->whereNotNull('transactions.promo_id')
            ->whereNotNull('transactions.payment_id')
            ->where('transaction_payment.transaction_status', 'settlement')
            ->groupBy('transaction_payment.gateway_id')
            ->selectRaw('count(*) AS total, transaction_payment.gateway_id, transaction_gateways.title')
            ->get();

        $dataPayment = [];
        foreach ($totalPerPayment as $key => $value) {
            $data = [$value->title, $value->total];
            array_push($dataPayment,$data);
        }

        if (empty($promo))
        {
            return redirect('/');
        }

        // Return View
        return view('admin.'.$page.'.used-list')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'promo' => $promo,
            'id'    => $id,
            'dataPayment' => $dataPayment
        ]);
    }

    public function usedListData($id)
    {
        // Check
        $promo = Promo::where('id', $id)->first();
      
        if (empty($promo))
        {
            return redirect('/');
        }

        $transPromo = Transaction::join('transaction_promo', 'transaction_promo.id', '=', 'transactions.promo_id')
            ->join('transaction_payment', 'transaction_payment.id', '=', 'transactions.payment_id')
            ->join('transaction_gateways', 'transaction_gateways.id', '=', 'transaction_payment.gateway_id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transaction_promo.promo_id', $id)
            ->whereNotNull('transactions.promo_id')
            ->whereNotNull('transactions.payment_id')
            ->where('transaction_payment.transaction_status', 'settlement')
            ->select('transactions.id', 'transaction_promo.promo_id', 'transactions.total', 'users.name', 'users.email', 'users.phone', 'transaction_payment.gateway_id', 'transaction_gateways.title AS gateway', 'transaction_payment.payment_type', 'transaction_payment.gross_amount', 'transaction_promo.price AS discount', 'transaction_payment.created_at AS payment_date')
            ->get();

        foreach ($transPromo as $item) 
        {
            // Array
            $items[] = array(
                'id' => $item->id,
                'customer' => '<div><p class="mb-1">Nama : <b>'. $item->name . '</b></p><p class="mb-1">phone : <b>'. $item->phone . '</b></p><p class="mb-0">email : <b>'. $item->email . '</b></p></div>',
                'payment_date' => $item->payment_date,
                'payment_type' => '<div><b>' . $item->gateway . ' - ' . ucwords(str_replace("_", " ", $item->payment_type)) . '</b><div>',
                'gross_amount' => 'Rp. ' . number_format($item->gross_amount, 0, ',', '.'),
                'discount' => 'Rp. ' . number_format($item->discount, 0, ',', '.'),
                'total' => 'Rp. ' . number_format($item->total, 0, ',', '.'),
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function update(Request $request)
    {
        // Validation
        $validated = $request->validate([
            // 'type_id' => 'required|integer',
            'name' => 'required|max:255',
            'code' => 'required|max:255',
            'expired' => 'required|date',
            'transaction_min' => 'required|numeric',
            'quota' => 'required|numeric',
        ]);
        // Initialization
        $id = $request->id; // promo_id
        // dd($id);
        $expired = $request->expired;
        $quota = $request->quota;
        $pageTitle = 'Kode Promo';
        $page = 'promo';
        
        // Check
        $item = Promo::where('id', $id)
            ->first();
        $ppob_type = PpobPromo::where('promo_id',$id)->get();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction
        DB::beginTransaction();
        try {
            $update = Promo::find($id);
            $update->user_id = Auth::user()->id;
            $update->type_id = $request->promo_type_id;
            $update->name = $request->name;
            $update->code = $request->code;
            $update->expired = $request->expired;
            $update->transaction_min = $request->transaction_min;
            $update->quota = $request->quota;
            $update->quota_user_day = $request->quota_user_day;
            $update->discount_type_id  = $request->discount_type_id;
            $update->total_quota       = $request->total_quota;
            $update->quota_user_total = $request->quota_user_total;


            if($item->type_id == 1){
                $update->discount_price = $request->discount_price;
                $update->discount_max= null;
                $update->discount_percent = null;
    
            }
            if($item->type_id == 2){
                $update->discount_max = $request->discount_max;
                $update->discount_percent  = $request->discount_percent;
                $update->discount_price = null;
    
            }
            if($item->type_id == 3){
                if($request->check_type == 0){
                    $update->discount_price = $request->discount_price;
                    $update->discount_max  = null;
                    $update->discount_percent = null;
                }
                else{
                    $update->discount_price =null;
                    $update->discount_max  =  $request->discount_max;
                    $update->discount_percent = $request->discount_percent;
                }
            }

            
            if($request->has('shipping_code')){
                // $update->shipping_code = ($request->has('shipping_code')) ? $request->shipping_code : null;
                $update->shipping_code = $request->shipping_code;
            }
            
           
            if($request->has('term_condition')){
                $update->term_condition = $request->term_condition;
            }
            // dd($request,$update);
            
            $update->save();

            if($request->has('ppob_type')){
                $item = PpobPromo::where('promo_id', $id)->delete();
                $ppob_type = $request->ppob_type;
                foreach($ppob_type as $p){
                    $arr = new PpobPromo;
                    $arr->promo_id = $id;
                    $arr->ppob_type_id= $p;
                    $arr->save();
                }
            }

            if($request->has('product_type')){
                $item = PromoProductType::where('promo_id', $id)->delete();
                $product_type = $request->product_type;
                foreach($product_type as $p){
                    $arr = new PromoProductType;
                    $arr->promo_id = $id;
                    $arr->product_type_id= $p;
                    $arr->save();
                }
            }

            DB::commit();
            return redirect()->route('admin.'.$page)
            ->with('status', 'Perubahan '.$pageTitle.' telah berhasil disimpan');
        } catch (\Exception $e) {
            // Return redirect back and rollback transaction when update failed
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }   
    }

    public function delete(Request $request)
    {
        // dd($request);
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $pageTitle = 'Kode Promo';
        $page = 'promo';
        
        // Check
        $item = Promo::where('id', $id)
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Transaction Delete
        DB::beginTransaction();
        try {
             // Delete
            $items = PpobPromo::where('promo_id', $id)->delete();
            $promo_product_type = PromoProductType::where('promo_id', $id)->delete();
            $promo = Promo::where('id', $id)->delete();

            DB::commit();
            return redirect()->route('admin.'.$page)
            ->with('status', 'Hapus '.$pageTitle.' telah berhasil');

        }
        catch (\Exception $e) {
            // Return redirect back and rollback transaction when update failed
            DB::rollBack();
            return redirect()->back()->with('warning', 'Oops!!, Terjadi kesalahan. <br>Pesan kesalahan: ' . $e);
        }   
    }
}
