<?php

namespace Marketplace\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use Marketplace\User;
use Marketplace\Coupon;
use Marketplace\Lottery;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

class LotteryController extends Controller
{
    public function index()
    {
        // Initialization
        $pageTitle = 'Lottery';
        $page = 'lottery';

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
        $pageTitle = 'Lottery';
        $page = 'lottery';
       
        $lists = Lottery::join('users', 'lottery.user_id', '=', 'users.id')
            ->select(
                'lottery.id','users.id AS user_id','users.name','users.username','users.email','users.photo','lottery.status AS lottery_status'
            )
            ->where('users.activated','=','1')

            ->orderBy('lottery.id', 'DESC')
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
                'user_id'=> $item->user_id,
                'status' => ($item->lottery_status === 0 ) ? '<span class="badge badge-success">Belum Diundi</span>': '<span class="badge badge-danger">Sudah Diundi</span>',
                // 'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                // 'updated_at'=> $item->updated_at->format('Y-m-d H:i:s'),
                'action' => '<a href="'.route('admin.'.$page.'.edit', ['id' => $item->id]).'"><button class="btn btn-primary ks-split mr-2"><span class="la la-edit ks-icon"></span><span class="ks-text">Ubah '.$pageTitle.'</span></button></a>',
            );
        }

        // Return Array
        return array('aaData' => $items);
    }

    public function create(Request $request)
    {
        // Initialization
        $pageTitle = 'Lottery';
        $page = 'lottery';
        $id = $request->id;
        // $coupon = User::where('id','>','0')
        //     ->select('id as user_id','users.name','users.username','users.email','users.phone')
        //     ->get();
        $coupon = User::join('transactions','transactions.user_id', '=','users.id' )
            ->leftjoin('transaction_promo','transaction_promo.id','=', 'transactions.promo_id')
            ->leftjoin('transaction_payment','transaction_payment.order_id','=', 'transactions.id') 
            // 'transaction_payment.transaction_status'
            ->select('transactions.id AS transaction_id','users.id as user_id','users.name','users.username','users.email','users.phone','transaction_payment.transaction_status')
            ->where('transaction_promo.code','=','SHAWNMENDESKL')
            ->where('transactions.promo_id','!=',null)
            ->where('transaction_payment.transaction_status','=','settlement')
            ->get();


        // $coupon = Coupon::join('transactions', 'coupons.transaction_id', '=', 'transactions.id')
        //     ->leftjoin('users','users.id','=', 'transactions.user_id') 
        //     // ->leftjoin('transaction_promo','transaction_promo.id','=', 'transactions.promo_id') 
        //     // ->leftjoin('auto_debet','auto_debet.user_id','=', 'coupons.user_id') 
        //     ->select('transactions.id','coupons.user_id','users.name','users.username','users.email','users.phone')
        //     // 'transaction_promo.code')
            
        //     //CODE SHAWNMENDES
        //     // ->where('transaction_promo.code','=','SHAWNMENDESKL')

        //     //FILTER PEGAWAI MONSPACE
        //     // ->whereNotIn('coupons.user_id',function($query) {
        //     //     $query->select('auto_debet.user_id')->from('auto_debet');
        //     // })

        //     //FILTER User yang telah diundi
        //     // ->whereNotIn('coupons.user_id',function($query) {
        //     //     $query->select('lottery.user_id')->from('lottery');
        //     // })

        //     ->distinct()
        //     ->get();

        // Return View
        return view('admin.'.$page.'.create')->with([
            'pageTitle' => $pageTitle,
            'page' => $page,
            'coupon'=>$coupon,
        ]);
    }

  
    public function store(Request $request)
    {
        // dd($request);
        // Validation
        $validated = $request->validate([
            'user_id' => 'required|max:255',
        ]);

        // Initialization
        $user_id = $request->user_id;

        $pageTitle = 'Lottery';
        $page = 'lottery';

        $check_user = Lottery::orderBy('id', 'ASC')
            ->where('user_id','=',$user_id)
            ->first();
            
        if(!empty($check_user)){
            return redirect()->back()->with('warning', 'user telah didaftarkan ke dalam Lottery');
        }
     
       
         // Transaction
         DB::beginTransaction();
         try {
            $now = Carbon::now();
            $now = $now->toDateTimeString();

            // Insert AutoDebet
            $insert = new Lottery;
            $insert->user_id = $user_id;
            $insert->status = 0;
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

    public function update_status(Request $request){
        $id_lottery = $request->id_lottery;
        DB::beginTransaction();
        try {
            $update = Lottery::where('id', $id_lottery)->update(['status' => 1]);
            $pageTitle = 'Lottery';
            $page = 'lottery';

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
        $directory = 'lottery';
        $pageTitle = 'Lottery';
        $page = 'lottery';
        
        // Check
        $item = Lottery::where('id', $id)
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
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $status = $request->status;

        $pageTitle = 'Lottery';
        $page = 'lottery';

         // Transaction
         DB::beginTransaction();
         try {
              // Check
            $item = Lottery::where('id', $id)
                ->first();

            if (empty($item))
            {
                return redirect('/');
            }

            // Update
            $update = Lottery::where('id', $id)->update([
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
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        // Initialization
        $id = $request->id;
        $pageTitle = 'Lottery';
        $page = 'lottery';

        // Delete
        DB::beginTransaction();
        try {
             // Check
            $item = Lottery::where('id', $id)->first();

            if (empty($item))
            {
                return redirect('/');
            }

            $item = Lottery::where('id', $id)->delete();
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
}
