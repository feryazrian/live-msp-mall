<?php

namespace Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Marketplace\Coupon;

use Marketplace\TransactionProduct;
class CouponController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Coupon';
        $page = 'coupon';
      
        return view('admin.'.$page.'.index')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
        ]);
    }

    public function data(Request $request)
    {
        // Initialization
        $items = array();
        $pageTitle = 'Coupon';
        $page = 'coupon';
        $data = Coupon::join('transactions', 'coupons.transaction_id', '=', 'transactions.id')
            ->leftjoin('users','users.id','=', 'transactions.user_id') 
            ->leftjoin('transaction_promo','transaction_promo.id','=', 'transactions.promo_id') 
            ->select('transactions.id','coupons.user_id','users.name','users.username','users.email','users.phone','transaction_promo.code')
            // ->where('transaction_promo.code','=','SHAWNMENDESKL')
            // ->distinct()
            ->orderByRaw("RAND()")
            ->get();
            foreach ($data as $key => $item) 
            {
                $items[] = array(
                    'no'=>$key +1,
                    'id'=> $item->id,                
                    'customer' => ($item)?
                        "<div>Name <b>: ".$item->name."</b></div>".
                        "<div>Username <b>: ".$item->username."</b></div>".
                        "<div>Email  <b>: ".$item->email."</b></div>".
                        "<div>Phone  <b>: ".$item->phone."</b></div>"
                    :
                        "No Users",
                    'code'=> $item->code,                
                    'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split">
                    <span class="la la-edit ks-icon"></span><span class="ks-text">Detail '.$pageTitle.'</span></button></a>',
                );
            }
        // Return Array
        return array('aaData' => $items);
    }

    public function edit(Request $request)
    {
        // Initialization
        $id = $request->id;
        $directory = 'coupon';
        $pageTitle = 'Coupon';
        $page = 'coupon';
        $item = Coupon::join('transactions', 'coupons.transaction_id', '=', 'transactions.id')
            ->leftjoin('users','users.id','=', 'transactions.user_id') 
            ->leftjoin('transaction_promo','transaction_promo.id','=', 'transactions.promo_id') 
            ->select('transactions.id','coupons.user_id','users.name','users.username','users.email','users.phone','transaction_promo.code')
            // ->where('transaction_promo.code','=','SHAWNMENDESKL')
            ->where('coupons.id','=',$id)
            ->distinct()
            // ->orderBy('users.name', 'DESC')
            // ->orderByRaw("RAND()")
            ->first();

        if (empty($item))
        {
            return redirect('/');
        }

        // Return View
        return view('admin.'.$page.'.edit')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'directory' => $directory,     
            'item' => $item
        ]);
    }
}
