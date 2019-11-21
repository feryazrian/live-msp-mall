<?php

namespace Marketplace\Http\Controllers\Api;

use Marketplace\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Marketplace\Http\Controllers\LifePointController;


use Marketplace\Jobs\SendVerificationEmail;

use Marketplace\User;
use Marketplace\UserAuth;
use Marketplace\Blacklist;
use Marketplace\UserProvider;
use Ramsey\Uuid\Uuid;
use Marketplace\Balance;
use Marketplace\BalanceDeposit;
use Marketplace\BalanceTransaction;
use Marketplace\LifePoint;
use Marketplace\PpobTransaction;

use Auth;
use Curl;
use Hash;
use Validator;
use Response;


class ApiPpob extends Controller
{
    protected $pointController;
    public function __construct()
    {
        $this->pointController = new LifePointController;
    }

    public function checkBalance(Request $request)
    {
        $username = env("MOBILE_PULSA_USERNAME");
        $apiKey = env("MOBILE_PULSA_KEY");
        $commands = $request->commands;
        if ($commands == "balance") {
            $signature  = md5($username . $apiKey . 'bl');
        } elseif ($commands == "pricelist") {
            $signature  = md5($username . $apiKey . 'pl');
        }
        $header = [
            "commands" => $commands,
            "username" => env("MOBILE_PULSA_USERNAME"),
            "sign" => $signature
        ];
        $response = Curl::to(env("MOBILE_PULSA_URI"))
            ->withData($header)
            ->asJson()
            ->post();
        dd($response, $header);
        return response()->json($response);
    }

    public function buyPulsa(Request $request)
    {
        $ref_id =  Uuid::uuid4()->toString();
        // dd($order_id);
        $username = env("MOBILE_PULSA_USERNAME");
        $apiKey = env("MOBILE_PULSA_KEY");

        $signature  = md5($username . $apiKey . $ref_id);
        $header = [
            "commands"   => $request->commands,
            "username"   => $username,
            "ref_id"     => $ref_id,
            "hp"         => $request->hp,
            "pulsa_code" => $request->pulsa_code,
            "sign"       => $signature
        ];
        // dd($header);
        DB::beginTransaction();
        try {

            $response = Curl::to(env("MOBILE_PULSA_URI"))
                ->withData($header)
                ->asJson()
                ->post();
            dd($header, $response);

            $insert = DB::table('orders_ppob')->insert(
                [
                    "ref_id" => $response->ref_id,
                    "status" => $response->status,
                    "code" => $response->code,
                    "hp" => $response->hp,
                    "price" => $response->price,
                    "message" => $response->message,
                    "balance" => $response->balance,
                    "tr_id" => $response->tr_id,
                    "sign" => $response->sign,
                    "order_json" => json_encode($response),
                    "rc" => $response->rc
                ]
            );
            return response()->json($response);
        } catch (Execption $e) {
            return;
        }
    }
    public function callbackPulsa(Request $request)
    {

        $input = $request->all();
        $data = $input["data"];

        $ppobTransaction = PpobTransaction::where('ref_id', $data['ref_id'])->where('status', 0)->first();
        if ($ppobTransaction) {
            DB::table('ppob_transactions')
                ->where('reff_id', $data["ref_id"])
                ->update(
                    [
                        'status' => $data["status"],
                        'response_json' => json_encode($request->all())
                    ]
                );
            $paymentTransaction = $ppobTransaction->payment;
            $transaction = $ppobTransaction->transaction;

            $user = User::where('id', $ppobTransaction->user_id)->first();
            if ($data['status'] == 1) {
                if ($ppobTransaction->payment->gateway_id == 2) {
                    if (!empty($transaction->promo->promo->discount_type_id) == 1) {
                        $data = [
                            "transaction_point" => $transaction->promo->price,
                            "point_operator" => 1,
                            "status" => 1,
                            "transaction_id" => $paymentTransaction->transaction_id,
                            "point_transaction_type_id" => 2,
                            "user_id" => $paymentTransaction->user_id,
                            "description" => "Cashback Pembelian Produk PPOB",
                        ];
                        $lifepointTransaction = $this->pointController->add_life_point_transaction($data);
                    }
                }
            } else if ($data["status"] == 2) {
                if ($ppobTransaction->payment->gateway_id == 2) {
                    // Balance Transaction Create Plus
                    $balanceTransaction = new BalanceTransaction;
                    $balanceTransaction->transaction_id = $ppobTransaction->transaction_id;
                    $balanceTransaction->user_id = $ppobTransaction->user_id;
                    $balanceTransaction->seller_id = null;
                    $balanceTransaction->status = 1;
                    $balanceTransaction->save();

                    $balanceTransactionId = $balanceTransaction->id;

                    $balanceNew = new Balance;
                    $balanceNew->user_id = $ppobTransaction->user_id;
                    $balanceNew->transaction_id = $balanceTransactionId;
                    $balanceNew->ppob_id = $ppobTransaction->id;
                    $balanceNew->notes = 'Pembatalan Pembelian Produk PPOB';
                    $balanceNew->save();
                } else if ($ppobTransaction->payment->gateway_id == 3) {
                    $data = [
                        "transaction_point" => $paymentTransaction->gross_amount,
                        "point_operator" => 1,
                        "status" => 1,
                        "transaction_id" => $paymentTransaction->transaction_id,
                        "point_transaction_type_id" => 2,
                        "user_id" => $paymentTransaction->user_id,
                        "description" => "Pembatalan Pembelian Produk PPOB",
                    ];
                    $lifepointTransactionData = $this->pointController->add_life_point_transaction($data);
                }
            }

            return response("OK", 200);
        }
    }

    public function paymentPulsa()
    { }

    public function ppob_transaction(Request $request)
    {
        $ref_id =  Uuid::uuid4()->toString();
        // dd($order_id);
        $username = env("MOBILE_PULSA_USERNAME");
        $apiKey = env("MOBILE_PULSA_KEY");

        $signature  = md5($username . $apiKey . $ref_id);
        $header = [
            "commands"   => $request->commands,
            "username"   => $username,
            "ref_id"     => $ref_id,
            "hp"         => $request->hp,
            "pulsa_code" => $request->pulsa_code,
            "sign"       => $signature
        ];
        // dd($header);
        DB::beginTransaction();
        try {

            $response = Curl::to(env("MOBILE_PULSA_URI"))
                ->withData($header)
                ->asJson()
                ->post();

            $input_ppob = [
                "user_id" => "1",
                "type_id" => "1",
                "operator_id" => "1",
                "plan_id" => "1",
                "payment_id" => "1",
                "transaction_id" => "1",
                "product" => $response->data->code,
                "ref_id" => $ref_id,
                "cust_number" => "2",
                "tr_code" => $response->data->code,
                "tr_id" => $response->data->tr_id,
                "price" => $response->data->price,
                "status" => $response->data->status,
                "reff_id" => $ref_id,
                "serial_number" => $response->data->rc,
                "balance" => $response->data->balance,
                "r_balance" => $response->data->balance,
                "pin" => "123",
                'response_json' =>  json_encode($response->data)
            ];
            $ppob = PpobTransaction::insert($input_ppob);
            DB::commit();
            return response()->json($input_ppob);
        } catch (Execption $e) {
            return;
        }
    }
}
