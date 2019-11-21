<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
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
use Marketplace\Banner;
use Marketplace\LifePoint;
use Marketplace\LifePointTransaction;
use Marketplace\PpobCheckout;


use Marketplace\Service\PromoService;
use Curl;
use Ramsey\Uuid\Uuid;
use Auth;
use Session;
use Marketplace\PpobOperator;

class DigitalController extends Controller
{

    protected $balanceController;
    protected $pointController;
    public function __construct()
    {
        $this->balanceController = new BalanceController;
        $this->pointController = new LifePointController;
        // $myBalance = new BalanceController;
        // $myPoint = new LifePointController;
    }

    public function index()
    {
        // Initialization
        $pageTitle = 'Topup & Tagihan';
        $banner = Banner::where("flag", 1)->where("publish_date", "<=", now())->where("end_date", ">", now())->get();

        // Return View
        return view('digital.index')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'banner' => $banner
        ]);
    }

    public function ppob_type()
    {
        $ppobType = PpobType::get();
        if (count($ppobType) == 0) {
            $responses = array(
                'status_code' => 404,
                'status_message' => "PPOB Type tidak ditemukan",
                'items' => null,
            );
            return response()->json($responses, $responses['status_code']);
        }
        $responses = array(
            'status_code' => 200,
            'status_message' => "OK",
            'items' => $ppobType,
        );
        return response()->json($responses, $responses['status_code']);
    }

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
        $pathType = null;
        $pathProvider = null;
        if (isset($request->type)) {
            $pathType = '/' . $request->type;
            if (isset($request->provider)) {
                $pathProvider = '/' . $request->provider;
            }
        }

        // dd($username,$apiKey,$signature,$header,$pathType,$pathProvider);

        $responseApi = Curl::to(env("MOBILE_PULSA_URI") . $pathType . $pathProvider)
            ->withData($header)
            ->asJson()
            ->post();

        return response()->json($responseApi);
    }
    public function pricelistDetail($pulsa_code, $type)
    {
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
        return $item;
    }

    public function bannerDetail($slug, Request $request)
    {
        // Initialization
        $pageTitle = str_replace('-', ' ', $slug);
        $banner = Banner::where("flag", 1)
            ->where("publish_date", "<=", now())
            ->where("end_date", ">", now())
            ->where("slug", $slug)
            ->first();
        // Return View
        return view('digital.banner.detail')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'banner' => $banner
        ]);
    }
    public function inquiry(Request $request)
    {
        $dataUser = Auth::user();
        $input = $request->all();

        $exp = explode('@', $dataUser->email);
        if (count($exp) >= 2) {
            $expDomainDot = explode('.', $exp[1]);
            $expDot = substr_count($exp[0], '.');
            $whitelist = ['gmail', 'yahoo', 'hotmail'];
            $isWhiteList = in_array($expDomainDot[0], $whitelist);

            if ($isWhiteList == false || $expDot > 2 && $dataUser->activated != 2) {
                $dataUser->activated = 2;
                $dataUser->save();
                return redirect()->back()
                    ->with('danger', 'Maaf akun anda teridentifikasi sebagai robot. Jika anda bukan robot silahkan hubungi customer service MSP Mall.');
            }
        }

        $itemResponse = $this->pricelistDetail($request["pulsa_code"], $input['type']);
        $username = env("MOBILE_PULSA_USERNAME");
        $apiKey = env("MOBILE_PULSA_KEY");
        $request->commands = "topup";
        $saldo = 0;
        $life_data  = $this->pointController->get_life_data($dataUser);
        $ref_id =  Uuid::uuid4()->toString();
        $user_id = $dataUser->id;
        $additionalCost = 0;
        $paymentGateway = TransactionGateway::where('id', $input["paymentMethod"])->first();
        $ppobOperator = DB::table('ppob_operators')->where("slug", $itemResponse->pulsa_op)->orWhere("name", $itemResponse->pulsa_op)->first();
        $ppobTransaction = PpobTransaction::where('user_id', $dataUser->id)->where('payment_id', null)->where('transaction_id',null)->first();

        if ($dataUser->activated == 2) {
            return redirect()->back()
                ->with('danger', 'Maaf akun anda kami blokir sementara karena terindikasi melakukan kecurangan. Jika anda tidak melakukan kecurangan, silahkan hubungi customer service MSP Mall.');
        } else if($dataUser->activated == 0){
            return redirect()->back()
                ->with('warning', 'Silahkan aktivasi akun anda sebelum melakukan transaksi pembelian.');
        }

        $totalTransaction = 0;
        $promo = null;
        if (!empty($request->promo_code)) {
            $promoRequest = [
                "code" => $request->promo_code,
                "type_ppob_id" => $request->type_ppob_id,
                "total_transaction" => $itemResponse->pulsa_price
            ];
            $promo  = PromoService::CheckPromo($promoRequest);
            if (!$promo["status"]) {
                return redirect()->back()
                    ->with('warning', $promo["message"]);
            }
        } else {
            $promo["status"] = false;
            $promo["promo_price"] = 0;
            $promo["promo_id"] = null;
            $promo["promo"] = null;
        }
        if ($input["paymentMethod"] == 2) {
            $saldo = $this->balanceController->myBalance();
            $discount = 0;
            if ($promo["status"]) {
                if ($promo["promo"]->discount_type_id != 1) {
                    $discount = $promo["promo_price"];
                }
            }
            $totalTransaction = $itemResponse->pulsa_price +  $additionalCost - $discount;
        } else if ($input["paymentMethod"] == 3) {
            $saldo = $this->pointController->get_life_point($dataUser);
            $totalTransaction = $itemResponse->pulsa_price +  $additionalCost;
        }
        if ($saldo < $totalTransaction) {
            return redirect()->back()
                ->with('warning', 'Maaf, saldo anda tidak mencukupi untuk melakukan pembelian.');
        }
        if ($ppobTransaction) {
            DB::beginTransaction();
            try {
                $inputTransaction = [
                    "user_id" => $dataUser->id,
                    "total" => $itemResponse->pulsa_price,
                    "gateway_id" => $input["paymentMethod"]
                ];
                $transaction = Transaction::create($inputTransaction);
                $data = [
                    "nominal" => 1,
                    "request" => $input,
                    "reff_id" => $ref_id,
                    "transaction" => $transaction,
                    "itemResponse" => $itemResponse,
                    "additional_cost" => $additionalCost,
                    "promo_price" => $promo["promo_price"],
                    "promo_id" => $promo["promo_id"],
                    "promo" => $promo["promo"],
                    "user_id" => $user_id,
                    "lifePoint" => $life_data,
                    "paymentGateway" => $paymentGateway,
                    "total_transaction" => $totalTransaction

                ];
                // $payment = $this->payment($data);
                // dd($payment);
                //payment 
                if ($promo["promo"] != null) {
                    $dataTransactionPromo = [
                        "transaction_id" => $transaction->id,
                        "user_id" => $transaction->user_id,
                        "promo_id" => $promo["promo_id"],
                        "type" => $promo["promo"]->type->name,
                        "name" => $promo["promo"]->name,
                        "code" => $promo["promo"]->code,
                        "expired" => $promo["promo"]->expired,
                        "price" => $promo["promo_price"],
                    ];
                    $insertTransactionPromo =  TransactionPromo::create($dataTransactionPromo);
                    $taransaction = Transaction::where("id", $transaction->id)->update(["promo_id" => $insertTransactionPromo->id]);
                }
                $inputDataPayment = [
                    "user_id" => $user_id,
                    'transaction_id' => $ref_id,
                    "order_id" => $transaction->id,
                    'gateway_id' => $input["paymentMethod"],
                    'status_code' => 200,
                    'status_message' => 'Success',
                    'gross_amount' => $totalTransaction,
                    'transaction_time' => now(),
                    'transaction_status' => 'settlement',
                    'payment_type' => $paymentGateway->slug,
                    'fraud_status' => 'accept',
                    'finish_redirect_url' => '',
                    "result" => ""
                ];

                $paymentHistory = TransactionPaymentHistory::insert([$inputDataPayment]);
                $paymentTransaction = TransactionPayment::create(
                    $inputDataPayment
                );
                $updatePaymentId = PpobTransaction::where('id',$ppobTransaction->id)->update(['payment_id'=>$paymentTransaction->id]);
                $balance = "";
                if ($input["paymentMethod"] == 2) {
                    $inputBalanceTransaction = [
                        "user_id" => $user_id,
                        "seller_id" => null,
                        "transaction_id" => $transaction->id,
                        "status" => 0,
                    ];
                    $balanceTransaction = BalanceTransaction::create($inputBalanceTransaction);
                    $inputBalance = [
                        "user_id" => $user_id,
                        "transaction_id" => $balanceTransaction->id,
                        "notes" => "Pembelian Produk PPOB",
                    ];
                    $balance = Balance::create($inputBalance);
                } else if ($input["paymentMethod"] == 3) {
                    $dataLifePointTransaction = [
                        "transaction_point" => $totalTransaction,
                        "life_point_id" =>  $life_data->id,
                        "point_operator" => 0, //0=minus or positif=1
                        "status" => 1, //1 berhasil
                        "transaction_id" => $transaction->id,
                        "point_transaction_type_id" => 4,
                        "user_id" => $user_id,
                        "description" => "Pembelian Produk PPOB"

                    ];
                    $lifepointTransaction = $this->pointController->create_life_point_transaction($dataLifePointTransaction);
                    $dataUpdateLifePoint = [
                        "total_point" =>  $life_data->total_point - $totalTransaction
                    ];
                    $lifePointUpdate = $this->pointController->update_life_point($life_data->id, $dataUpdateLifePoint);
                }
                $signature  = md5($username . $apiKey . $ref_id);
                $header = [
                    "commands"   => $request->commands,
                    "username"   => $username,
                    "ref_id"     => $ref_id,
                    "hp"         => $request->hp,
                    "pulsa_code" => $request->pulsa_code,
                    "sign"       => $signature
                ];

                $response = Curl::to(env("MOBILE_PULSA_URI"))
                    ->withData($header)
                    ->asJson()
                    ->post();
                $responseData = $response->data;
                if ($responseData->rc == 00 || $responseData->rc == 39) {
                    $updateDataPpobTransaction = [
                        "transaction_id" => $transaction->id,
                        "product" => $itemResponse->pulsa_code,
                        "ref_id" => $ref_id,
                        "cust_number" => $input['hp'],
                        "tr_code" => $responseData->code,
                        "tr_id" => $responseData->tr_id,
                        "price" => $totalTransaction,
                        "status" => $responseData->status,
                        "reff_id" => $responseData->ref_id,
                        "serial_number" => "",
                        "balance" => "",
                        "r_balance" => $responseData->balance,
                        "pin" => "",
                    ];
                    $updatePpobTransaction  = PpobTransaction::where('id', $ppobTransaction->id)->update($updateDataPpobTransaction);
                    $transactionUpdate = Transaction::where('id', $paymentTransaction->order_id)
                        ->where('user_id', Auth::user()->id)
                        ->update([
                            'payment_id' => $paymentTransaction->id,
                            'total' => $paymentTransaction->gross_amount
                        ]);
                    if ($input["paymentMethod"] == 2) {
                        $balance = Balance::where('id', $balance->id)
                            ->where("user_id", Auth::user()->id)
                            ->update([
                                "ppob_id" => $ppobTransaction->id
                            ]);
                    } else if ($input["paymentMethod"] == 3) { }
                    DB::commit();
                    $pageTitle = "Inovice number" . $responseData->ref_id;
                    return redirect("digital/" . $input["type"] . "/thank-you/" . $paymentTransaction->transaction_id)
                        ->with([
                            'headTitle' => true,
                            'pageTitle' => $pageTitle,
                            'data' => $ppobTransaction,
                        ]);
                } else {
                    DB::rollBack();
                    return redirect()->back()
                            ->with('warning',"Oops! ".$responseData->message);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                return redirect('digital/' . $input['type'])->with('danger', 'Oops! Internal Server Error.');

            }
        } else {
            return redirect()->back()
                ->with('warning', 'Maaf, sesi anda telah berakhir silahkan refresh halaman anda.');
        }
    }
    public function payment($result)
    {
        DB::beginTransaction();
        try {
            if ($result["promo"] != null) {
                $dataTransactionPromo = [
                    "transaction_id" => $result["transaction"]->id,
                    "user_id" => $result["transaction"]->user_id,
                    "promo_id" => $result["promo_id"],
                    "type" => $result["promo"]->type->name,
                    "name" => $result["promo"]->name,
                    "code" => $result["promo"]->code,
                    "expired" => $result["promo"]->expired,
                    "price" => $result["promo_price"],
                ];
                $insertTransactionPromo =  TransactionPromo::create($dataTransactionPromo);
                $taransaction = Transaction::where("id", $result["transaction"]->id)->update(["promo_id" => $insertTransactionPromo->id]);
            }
            $inputDataPayment = [
                "user_id" => $result['user_id'],
                'transaction_id' => $result["transaction"]->id,
                "order_id" => $result["reff_id"],
                'gateway_id' => $result["request"]["paymentMethod"],
                'status_code' => 200,
                'status_message' => 'Success',
                'gross_amount' => $result["total_transaction"],
                'transaction_time' => now(),
                'transaction_status' => 'settlement',
                'payment_type' => $result["paymentGateway"]->slug,
                'fraud_status' => 'accept',
                'finish_redirect_url' => '',
                "result" => ""
            ];

            $paymentHistory = TransactionPaymentHistory::insert([$inputDataPayment]);
            $paymentTransaction = TransactionPayment::create(
                $inputDataPayment
            );
            $balance = "";
            if ($result["request"]["paymentMethod"] == 2) {
                $inputBalanceTransaction = [
                    "user_id" => $result['user_id'],
                    "seller_id" => null,
                    "transaction_id" => $result["transaction"]->id,
                    "status" => 0,
                ];
                $balanceTransaction = BalanceTransaction::create($inputBalanceTransaction);
                $inputBalance = [
                    "user_id" => $result['user_id'],
                    "transaction_id" => $balanceTransaction->id,
                    "notes" => "Pembelian Produk PPOB",
                ];
                $balance = Balance::create($inputBalance);
            } else if ($result["request"]["paymentMethod"] == 3) {
                $dataLifePointTransaction = [
                    "transaction_point" => $result["total_transaction"],
                    "life_point_id" => $result["lifePoint"]->id,
                    "point_operator" => 0, //0=minus or positif=1
                    "status" => 1, //1 berhasil
                    "transaction_id" => $result["transaction"]->id,
                    "point_transaction_type_id" => 4,
                    "user_id" => $result['user_id'],
                    "description" => "Pembelian Produk PPOB"

                ];
                $lifepointTransaction = $this->pointController->create_life_point_transaction($dataLifePointTransaction);
                $dataUpdateLifePoint = [
                    "total_point" => $result["lifePoint"]->total_point - $result["total_transaction"]
                ];
                $lifePointUpdate = $this->pointController->update_life_point($result["lifePoint"]->id, $dataUpdateLifePoint);
            }

            DB::commit();
            $data = [
                "status" => true,
                "transaction" => $result["transaction"],
                "payment" => $paymentTransaction,
                "balance" => $balance
            ];
            return $data;
        } catch (Exception $e) {
            DB::rollBack();
            $data = [
                "status" => false,
                "message" => $e->getMessage()
            ];
            return $data;
        }
    }


    public function checkout($type, Request $request)
    {
        if (!$type) {
            return redirect('digital')->with('danger', 'Oops!. Something When Wrong. Err: Sesi Anda telah berakhir.');
        }
        $pageTitle = 'checkout';
        $myBalance = new BalanceController;
        $myBalance = $myBalance->myBalance();
        $user = Auth::user();

        $lifePoint = LifePoint::where('user_id', $user->id)->first();
        if ($lifePoint == null) {
            $myLifePoint = new LifePointController;
            $lifePoint = $myLifePoint->create_new($user);
        }

        DB::beginTransaction();
        try {
            // Payment Method List
            $paymentIdCanUse = [2, 3];
            $paymentMethod = TransactionGateway::whereIn('id', $paymentIdCanUse)->where('status', 1)->orderBy('id', 'ASC')->get();

            // PPOB Type        
            $item = $this->pricelistDetail($request["pulsa_code"], $type);

            if ($item && $item->pulsa_price > 100000) {
                return redirect('digital/' . $type)->with('danger', 'Oops! Mohon maaf, saat ini tidak diizinkan melakukan pembelian pulsa diatas Rp.100.000.');
                // if ($user->created_at >= '2019-07-31 00:00:00') {
                    // return redirect('digital/' . $type)->with('danger', 'Oops! Mohon maaf, user yang baru mendaftar tidak diizinkan melakukan pembelian pulsa diatas Rp.100.000.');
                // }
            }

            $ppobType = PpobType::where('slug', $type)->first();
            $ppobOprt = PpobOperator::where('name', $item->pulsa_op)->orWhere('slug', $item->pulsa_op)->first();
            $PpobData = [
                "user_id" => $user->id,
                "type_id" => $ppobType->id, //pulsa or data
                "operator_id" => $ppobOprt->id,
                "plan_id" => $ppobType->plan_id,
                "payment_id" => null,
                "transaction_id" => null,
                "product" => $item->pulsa_code,
                "ref_id" => null,
                "cust_number" => $request["hp"],
                "tr_code" => null,
                "tr_id" => null,
                "price" => $item->pulsa_price,
                "status" => 0,
                "reff_id" => null,
                "serial_number" => "",
                "balance" => "",
                "r_balance" => null,
                "pin" => "",
                "date_transaction" => now()
            ];

            //find or create if ppob transaction not exist
            PpobTransaction::updateOrCreate(
                ["user_id" => $user->id, 'payment_id' => null, 'transaction_id' => null],
                $PpobData
            );

            $dataResponse = [
                'headTitle' => true,
                'pageTitle' => $pageTitle,
                'operator' => $item->pulsa_op,
                'nominal' => $item->pulsa_nominal,
                'price' => $item->pulsa_price,
                'pulsa_code' => $item->pulsa_code,
                'phone_number' => $request["hp"],
                'myBalance' => $myBalance,
                'lifePoint' => $lifePoint,
                'type' => $type,
                'ppobType' => $ppobType,
                'paymentMethod' => $paymentMethod
            ];

            DB::commit();
            // Return View
            return view('digital.ppob.transaction.checkout')->with($dataResponse);
        } catch (\Throwable $th) {
            DB::rollback();
            // Return View
            return redirect('digital')->with('danger', 'Oops!. Something When Wrong. Err: ' . $th->getMessage());
        }
    }

    public function thankYou($type, $inv, Request $request)
    {
        // Initialization
        $pageTitle = 'Thank You';
        $ppobTransaction = PpobTransaction::join('transactions', 'transactions.id', 'ppob_transactions.transaction_id')->where("ppob_transactions.transaction_id", $inv)->first();
        if (!$ppobTransaction) {
            return redirect('transaction/buy/digital');
        }
        $operator = DB::table('ppob_operators')->where('id', $ppobTransaction->operator_id)->first();
        $item = $this->pricelistDetail($ppobTransaction->product, $type);
        $promoTransaction = TransactionPromo::where('transaction_id', $ppobTransaction->transaction_id)->first();
        $orderDate = date('d M Y H:i:s', strtotime($ppobTransaction->created_at));
        // Return View
        return view('digital.ppob.transaction.thank-you')->with([
            'headTitle' => true,
            'pageTitle' => $pageTitle,
            'data' => $ppobTransaction,
            'type' => $type,
            'operator' => $operator,
            'product' => $item,
            'promo' => $promoTransaction,
            'orderDate' => $orderDate
        ]);
    }

    public function invoice($type, $inv, Request $request)
    {
        // Initialization
        $pageTitle = 'Invoice ' . $inv;
        $ppobTransaction = PpobTransaction::join('transactions', 'transactions.id', 'ppob_transactions.transaction_id')->where("ppob_transactions.reff_id", $inv)->first();
        if (!$ppobTransaction) {
            return redirect('transaction/buy/digital');
        }
        $operator = DB::table('ppob_operators')
            ->join('ppob_types', 'ppob_operators.type_id', '=', 'ppob_types.id')
            ->where('ppob_operators.id', $ppobTransaction->operator_id)
            ->select('ppob_operators.id', 'ppob_operators.slug AS opr_slug', 'ppob_operators.name AS opr_name', 'ppob_types.id AS type_id', 'ppob_types.slug AS type_slug', 'ppob_types.name AS type_name')
            ->first();
        $item = $this->pricelistDetail($ppobTransaction->product, $type);
        $promo = TransactionPromo::where('transaction_id', $ppobTransaction->transaction_id)->first();
        $orderDate = date('d M Y H:i:s', strtotime($ppobTransaction->created_at));

        // Return View
        return view('digital.ppob.transaction.invoice')->with([
            'headTitle' => false,
            'pageTitle' => $pageTitle,
            'data' => $ppobTransaction,
            'type' => $type,
            'operator' => $operator,
            'product' => $item,
            'promo' => $promo,
            'orderDate' => $orderDate
        ]);
    }
}
