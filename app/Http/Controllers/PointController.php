<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Marketplace\PointWelcome;
use Marketplace\PointShare;
use Marketplace\PointReferral;
use Marketplace\PointGame;
use Marketplace\PointBonus;
use Marketplace\PointProduct;
use Marketplace\PointTopup;
use Marketplace\PointCoupon;
use Marketplace\Option;
use Marketplace\Coupon;

use Auth;
use QrCode;
use Carbon\Carbon;

class PointController extends Controller
{
	public function index()
	{
        // Initialization
        $pageTitle = 'My QR';
		$operation = 'check_mspoint';
        $point = 0;

		$username = Auth::user()->username;
        $url = route('referral', ['username' => Auth::user()->username]);

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

        // QR Code Generate
        $qrcode = QrCode::format('png')
            ->size(300)
            ->generate($url);
        
        $qrcode ='data:image/png;base64, '.base64_encode($qrcode);

		// Return View
	    return view('point.index')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'point' => $point,
			'qrcode' => $qrcode,
		]);
    }
    
	public function topup()
	{
        // Initialization
        $pageTitle = 'Top Up MSP';
		$operation = 'check_mspoint';
        $point = 0;

		$username = Auth::user()->username;
        $url = route('referral', ['username' => Auth::user()->username]);

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
        
        // Lists
        $lists = PointProduct::orderBy('price', 'ASC')
            ->get();

		// Return View
	    return view('point.topup')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'point' => $point,
			'lists' => $lists,
		]);
    }
    
	public function welcome()
	{
        // Initialization
        $pageTitle = 'My Gift';
        $user_id = Auth::user()->id;
        $point = 30;

        // Check
        $check = PointWelcome::where('user_id', $user_id)
            ->first();

		// Return View
	    return view('point.welcome')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'check' => $check,
			'point' => $point,
		]);
	}
	public function storeWelcome(Request $request)
	{        
        // Initialization
        $user_id = Auth::user()->id;

        // Check
        $check = PointWelcome::where('user_id', $user_id)
            ->first();

        if (empty($check))
        {
            // Plus Point
            $operation = 'update_mspoint';
            $username = Auth::user()->username;
            $point = 30;

            // Plus
            $response = new MsplifeController;
            $response = $response->update_mspoint($operation, $username, $point);
            
            // Insert
            $insert = new PointWelcome;
            $insert->user_id = $user_id;
            $insert->point = $point;
            $insert->save();
        }

        return redirect()
            ->route('point.welcome');
    }
	public function share()
	{
        // Initialization
        $pageTitle = 'My Gift';
        $point = 1;

		// Return View
	    return view('point.share')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'point' => $point,
		]);
	}
	public function storeShare(Request $request)
	{
        // Initialization
        $provider = $request->provider;
        $user_id = Auth::user()->id;

        $title = config('app.name');
        $url = url('/');

        // Check
        $check = PointShare::where('user_id', $user_id)
            ->where('provider', $provider)
            ->whereYear('created_at', '=', date('Y'))
            ->whereMonth('created_at', '=', date('m'))
            ->whereDay('created_at', '=', date('d'))
            ->first();
        
        if (empty($check))
        {
            // Plus Point
            $operation = 'update_mspoint';
            $username = Auth::user()->username;
            $point = 1;

            // Plus
            $response = new MsplifeController;
            $response = $response->update_mspoint($operation, $username, $point);
            
            // Insert
            $insert = new PointShare;
            $insert->user_id = $user_id;
            $insert->point = $point;
            $insert->provider = $provider;
            $insert->save();
        }

        // Return
        if ($provider == 'facebook')
        {
            return 'https://www.facebook.com/sharer/sharer.php?u='.$url.'&t='.str_limit($title,60);
        }

        if ($provider == 'whatsapp')
        {
            return 'whatsapp://send?text='.str_replace(' ', '%20', str_limit($title,60)).'%20'.$url;
        }
	}
	public function referral()
	{
        // Initialization
        $pageTitle = 'My Gift';
        $point = 5;
        $username = Auth::user()->username;
        $url = route('referral.install', ['username' => $username]);

        // QR Code Generate
        $qrcode = QrCode::format('png')
            ->size(300)
            ->generate($url);
        
        $qrcode ='data:image/png;base64, '.base64_encode($qrcode);

		// Return View
	    return view('point.referral')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'point' => $point,
			'url' => $url,
			'qrcode' => $qrcode,
		]);
	}
	public function game()
	{
        // Initialization
        $pageTitle = 'My Gift';
        $user_id = Auth::user()->id;
        $today = Carbon::now()->format('Y-m-d');
        $point = 0;

        // Point
        $point = PointGame::where('user_id', $user_id)
            ->where('date', $today)
            ->sum('point');

        // Today
        $today = PointGame::where('user_id', $user_id)
            ->where('date', $today)
            ->first();

        // Repeat
        $repeat = PointGame::where('user_id', $user_id)
            ->where('status', 1)
            ->get()
            ->count();

		// Return View
	    return view('point.game')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'today' => $today,
			'repeat' => $repeat,
			'point' => $point,
		]);
	}
	public function storeGame(Request $request)
	{
        // Initialization
        $point = $request->point;
        $user_id = Auth::user()->id;

        $date = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        // Point Spinner
        $point_spinner = Option::where('type', 'point-spinner')->first();
        $point_spinner = $point_spinner->content;
        
        // Validation
        if ($point > 50 OR $point < 0)
        {
            return '';
        }

        // Check Today
        $today = PointGame::where('user_id', $user_id)
            ->where('date', $date)
            ->first();
            
        if (!empty($today))
        {
            return '';
        }

        // Check Yesterday
        $yesterday = PointGame::where('user_id', $user_id)
            ->where('date', $yesterday)
            ->first();
            
        if (empty($yesterday))
        {
            $update = PointGame::where('user_id', $user_id)->update([
                'status' => 0,
            ]);
        }

        // Today Point
        if (empty($today))
        {
            // Transaction
            //DB::beginTransaction();

            // Plus Point
            $operation = 'update_mspoint';
            $username = Auth::user()->username;

            // Plus
            $response = new MsplifeController;
            $response = $response->update_mspoint($operation, $username, $point);
            
            // Insert
            $insert = new PointGame;
            $insert->user_id = $user_id;
            $insert->point = $point;
            $insert->date = $date;
            $insert->status = 1;
            $insert->bonus = 0;
            $insert->save();

            // Repeat
            $repeat = PointGame::where('user_id', $user_id)
                ->where('status', 1)
                ->where('bonus', 0)
                ->get()
                ->count();

            // Bonus
            $bonus = PointBonus::where('day', $repeat)
                ->first();

            if (!empty($bonus))
            {
                // Point
                $point_bonus = $bonus->point;

                // Plus
                $response = new MsplifeController;
                $response = $response->update_mspoint($operation, $username, $point_bonus);

                // Insert
                $insert = new PointGame;
                $insert->user_id = $user_id;
                $insert->point = $point_bonus;
                $insert->date = $date;
                $insert->status = 0;
                $insert->bonus = 1;
                $insert->save();

                $point = $point.' + '.$point_bonus;

                // Snipper Off
                if ($point_spinner != 1)
                {
                    $point = $point_bonus;
                }
            }

            // Reset 30 Days
            if ($repeat == 30)
            {
                $update = PointGame::where('user_id', $user_id)->update([
                    'status' => 0,
                ]);
            }

		    //DB::commit();

            return $point;
        }
        
        return '';
    }
    
	public function coupon()
	{
		// Initliazation
		$pageTitle = 'Kupon Lucky Draw';

		// Lists
        $coupons = PointCoupon::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
			->get();
		
		// Return View
		return view('coupon.point')->with([
			'pageTitle' => $pageTitle,
			'coupons' => $coupons,
		]);
	}
	public function createCoupon()
	{
        // Initialization
        $pageTitle = 'Tukar Lucky Draw';
		$operation = 'check_mspoint';
        $point = 0;

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

        // Coupon MSP
        $price = Option::where('type', 'coupon-msp')->first();
        $price = $price->content;

		// Return View
	    return view('coupon.create')->with([
            'headTitle' => true,
			'pageTitle' => $pageTitle,
			'point' => $point,
			'price' => $price,
		]);
    }
	public function storeCoupon(Request $request)
	{
        // Initialization
		$pageTitle = 'Penukaran Lucky Draw';
		$user_id = Auth::user()->id;
		$username = Auth::user()->username;
        $coupon = $request->coupon;
        $point = 0;

		// Check Point
        $operation = 'check_mspoint';
        
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

        // Coupon MSP
        $price = Option::where('type', 'coupon-msp')->first();
        $price = $price->content;

        // Total
        $total = $coupon * $price;

        // Validation
        if ($total > $point) {
            return redirect()
                ->route('coupon.create')
                ->with('warning', 'Maaf, MSP Point anda Tidak Mencukupi!');
        }

        // Point
        $operation = 'deduct_mspoint';
        
        // Point Minus
        $response = new MsplifeController;
        $response = $response->deduct_mspoint($operation, $username, $total);

        // Insert
        for ($p = 1; $p <= $coupon; $p++)
        {
            $insert = new PointCoupon;
            $insert->user_id = $user_id;
            $insert->point = $price;
            $insert->total = $total;
            $insert->coupon = $coupon;
            $insert->price = $price;
            $insert->save();
        }

        // Return Success
        return redirect()
            ->route('coupon.point')
            ->with('status', 'Selamat!! '.$pageTitle.' telah berhasil');
    }
}
