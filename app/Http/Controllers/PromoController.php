<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
use Marketplace\Service\PromoService;


class PromoController extends Controller
{
    //
    public function check_promo(Request $request){
        $promoRequest = [
            "code"=>$request->code,
            "type_ppob_id"=> $request->type_ppob_id,
            "total_transaction"=>$request->total_transaction
        ];
        $promo  = PromoService::CheckPromo($promoRequest);
        if(!$promo["status"])
        {
            $responses = array(
                'status_code' => 400,
                'status_message' => $promo["message"],
                'items' => null,
            );
            return response()->json($responses, $responses['status_code']);

        }
        $responses = array(
    		'status_code' => 200,
    		'status_message' => $promo["message"],
    		'items' => $promo["promo"],
    	);

        return response()->json($responses, $responses['status_code']);
     
    }
}
