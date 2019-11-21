<?php

namespace Marketplace\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;
use Marketplace\Slide;
use Marketplace\Banner;

use Curl;

use Illuminate\Support\Facades\DB;


use Marketplace\TransactionPayment;
use Marketplace\TransactionGateway;
use Marketplace\TransactionPaymentHistory;
use Marketplace\Balance;
use Marketplace\BalanceTransaction;
use Marketplace\PpobTransaction;
use Marketplace\Transaction;
use Marketplace\TransactionPromo;
use Marketplace\PpobType;
use Marketplace\LifePoint;
use Marketplace\LifePointTransaction;
use Marketplace\PpobCheckout;


use Marketplace\Service\PromoService;
use Ramsey\Uuid\Uuid;
use Auth;
use Session;
use Marketplace\PpobOperator;

/**
 * @group Digital
 *
 * API untuk memproses digital seperti ppob dll.
 * 
 */

class DigitalController extends Controller
{
    /**
     * Price List
     * Menampilkan semua digital price list seperti berdasarkan type & provider
     * @bodyParam type string required menentukan type digital yang akan ditampilkan seperti pulsa,data
     * @bodyParam provider string required  menentukan provider apa yang akan ditampilkan seperti INDOSAT, XL, AXIS, TELKOMSEL, SMARTFREN, TREE
     * @responseFile responses/pricelist.get.json
     */
    public function priceList(Request $request)
    {
        $username = env("MOBILE_PULSA_USERNAME");
        $apiKey = env("MOBILE_PULSA_KEY");
        $signature  = md5($username . $apiKey . "pl");
        $header = [
            "commands" => "pricelist",
            "username" => $username,
            "sign"     => $signature,
            "status"   => "active"
        ];

        $type = $request->type;
        $provider = $request->provider;
        if (!$type) {
			return response()->api(400, 'Type harus diisi');
        }
        if (!$provider) {
			return response()->api(400, 'Provider harus diisi');
		}
        $pathType = null;
        $pathProvider = null;
        if (isset($type)) {
            $pathType = '/' . $type;
            if (isset($provider)) {
                $pathProvider = '/' . $provider;
            }
        }

        $responseApi = Curl::to(env("MOBILE_PULSA_URI") . $pathType . $pathProvider)
            ->withData($header)
            ->asJson()
            ->post();
        return response()->api(200, 'Data berhasil ditampilkan', $responseApi->data);
    }
    
     /**
     * Price List Detail
     * Menampilkan detail price list seperti berdasarkan type & pulsa_code
     * 
     * @bodyParam type string required type wajib diisi seperti pulsa atau data. Example:pulsa
     * @bodyParam pulsa_code string required pulsa_code wajib seperti INDOSAT(hindosat, isatdata) ; XL ( xld, xldata ) ; AXIS ( haxis, axisdata ) ; TELKOMSEL ( htelkomsel, tseldata) ; SMARTFREN ( hsmart ) ;  THREE ( hthree, threedata).  Example: haxis50000
     * 
     * @responseFile responses/pricelistDetail.post.json
    */
    public function pricelistDetail(Request $request)
    {
        
        $pulsa_code = $request->pulsa_code;
        $type = $request->type;
        if (!$pulsa_code) {
			return response()->api(400, 'pulsa_code harus diisi');
		}
        if (!$type) {
			return response()->api(400, 'Type harus diisi');
        }
     
        $username = env("MOBILE_PULSA_USERNAME");
        $apiKey = env("MOBILE_PULSA_KEY");
        $signature  = md5($username . $apiKey . "pl");
        $path = ($type) ? $type : 'pulsa';

        $header = [
            "commands" => "pricelist",
            "username" => $username,
            "sign"     => $signature,
            "status"   => "all"
        ];

        // $item  = "pulsa_code";
        $responseApi = Curl::to(env("MOBILE_PULSA_URI") . "/" . $path)
            ->withData($header)
            ->asJson()
            ->post();
        $item = null;
        foreach ($responseApi->data as $response) {
            
            if ($response->pulsa_code == $pulsa_code) {
                
                $item = $response;
                break;
            }
        }

        if ($item == null) {
            return response()->api(400, 'Data Kosong');
        }
        
        return response()->api(200, 'Data berhasil ditampilkan', $item);
    }
}
