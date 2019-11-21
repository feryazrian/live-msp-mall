<?php

namespace Marketplace\Service;

use Illuminate\Support\Facades\DB;

use Marketplace\Promo;
use Marketplace\PromoType;
use Marketplace\TransactionPromo;

use Carbon\Carbon;
use Auth;


class PromoService
{
    public static function AddPromo($request)
    {

        // dd($request,'ada');
        // DB::beginTransaction();

        // // Insert
        // $dataTransactionPromo=[
        //     "transaction_id" => $request->id,
        //     "user_id" => $request->user_id,
        //     "promo_id" => $promo->id,
        //     "type" => $promo->type->name,
        //     "name" => $promo->name,
        //     "code" => $promo->code,
        //     "expired" => $promo->expired,
        //     "price" => $totalPromoPrice,
        // ];


        // $insertTransactionPromo =  TransactionPromo::create();

    }

    public static function CheckPromo($request)
    {
        $code = $request["code"];
        $type_ppob_id = $request["type_ppob_id"];
        $total_transaction = $request["total_transaction"];
        $totalPromoPrice = 0;

        $promo = Promo::where('code', $code)
            // ->join('ppob_promo','ppob_promo.promo_id','promo.id')
            ->where('expired', '>', Carbon::now()->format('Y-m-d H:i:s'))
            ->first();
        if (empty($promo)) {
            // Return Redirect
            $response = [
                "code" => 400,
                "status" => false,
                "message" => 'Maaf, Kode Promo yang anda masukkan Sudah Tidak Tersedia.'
            ];
            return $response;
        }
        //check promo product type
        if (count($promo->product_type) < 1) {
            $response = [
                "code" => 400,
                "status" => false,
                "message" => 'Maaf, Kode Promo yang anda masukkan Tidak Tersedia untuk product tersebut.'
            ];
            return $response;
        } else {
            $promoType = $promo->product_type->whereIn('id', [3]);
            //check if promo type null
            if (count($promoType) < 1) {
                $response = [
                    "code" => 400,
                    "status" => false,
                    "message" => 'Maaf, Kode Promo yang anda masukkan Tidak Tersedia untuk product tersebut.'
                ];
                return $response;
            }
            // if($promoType[0]->id != 3){
            //     $response =[
            //         "code"=>400,
            //         "status" => false,
            //         "message" => 'Maaf, Kode Promo yang anda masukkan Tidak Tersedia untuk product tersebut.'
            //     ];
            //     return $response;
            // }
        }

        if (count($promo->promoppob) > 0) {
            $promoType = $promo->promoppob->where('id', $type_ppob_id);
            if (count($promoType) == 0) {
                $response = [
                    "code" => 400,
                    "status" => false,
                    "message" => 'Maaf, Kode Promo yang anda masukkan Sudah Tidak Tersedia untuk type tersebut.'
                ];
                return $response;
            }
        }
        if ($total_transaction < $promo->transaction_min) {
            $response = [
                "code" => 400,
                "status" => false,
                "message" => 'Maaf, Kode Promo hanya dapat digunakan dengan Transaksi Minimal Rp ' . number_format($promo->transaction_min, 0, ',', '.')
            ];
            return $response;
            // Return Redirect

        }

        // Promo Quota
        $quotaTotal = TransactionPromo::where('promo_id', $promo->id)
            ->whereHas('transaction', function ($q) {
                $q->whereNotNull('payment_id');
            });
        $quotaUser = TransactionPromo::where('promo_id', $promo->id)
            ->whereHas('transaction', function ($q) {
                $q->whereNotNull('payment_id');
            })
            ->where('user_id', Auth::user()->id);
        
        $quota_total = $quotaTotal->count(); //4
        $quota_day = $quotaTotal->whereDate('updated_at', Carbon::today())->count(); //3
        $quota_user_total = $quotaUser->count(); //3
        $quota_user = $quotaUser->whereDate('updated_at', Carbon::today())->count(); // 2


        // dd($quota_user, $quota_total,$quota_day,$quota_user_total,$promo);
        if ($quota_total >= $promo->total_quota) {
            $response = [
                "code" => 400,
                "status" => false,
                "message" => 'Maaf, Kode Promo telah melebihi Kuota penggunaan'
            ];
            return $response;
        }
        if ($quota_day >= $promo->quota) {
            $response = [
                "code" => 400,
                "status" => false,
                "message" => 'Maaf, Kode Promo telah melebihi Kuota penggunaan per Hari'
            ];
            return $response;
        }
        // dd($quota_day,$quota_total);

        if ($quota_user_total >= $promo->quota_user_total) {
            $response = [
                "code" => 400,
                "status" => false,
                "message" => 'Maaf, User anda telah melebihi Kuota penggunaan'
            ];
            return $response;
        }

        if ($quota_user >= $promo->quota_user_day) {
            $response = [
                "code" => 400,
                "status" => false,
                "message" => 'Maaf, User anda telah melebihi Kuota penggunaan per Hari'
            ];
            return $response;
        }



        // Promo Shipping
        // if ($promo->type_id == 1)
        // {
        // 	$shippingPromoPrice = $totalShippingPrice;

        // 	if ($totalShippingPrice > $promo->discount_price)
        // 	{
        // 		$shippingPromoPrice = $promo->discount_price;
        // 	}

        // 	$totalPromoPrice = $shippingPromoPrice;
        // }

        // // Promo Transaction
        // if ($promo->type_id == 2)
        // {
        // 	$totalBeforePromo = ($totalProductPrice + $totalShippingPrice);

        // 	$totalPromoPrice = $totalProductPrice * ($promo->discount_percent / 100);

        // 	if ($totalPromoPrice > $promo->discount_max) {
        // 		$totalPromoPrice = $promo->discount_max;
        // 	}
        // }

        //promo PPOB
        if ($promo->type_id == 3) {
            if ($promo->discount_price != null) {
                $totalPromoPrice = $promo->discount_price;
            } else {
                $totalPromoPrice = $total_transaction * ($promo->discount_percent / 100);
                if ($totalPromoPrice > $promo->discount_max) {
                    $totalPromoPrice = $promo->discount_max;
                }
            }
        }
        $promo["promo_price"] = $totalPromoPrice;
        $response = [
            "code" => 200,
            "status" => True,
            "promo" => $promo,
            "promo_price" => $totalPromoPrice,
            "promo_id" => $promo->id,
            "message" => "Promo tersedia"
        ];
        return $response;
    }
}
