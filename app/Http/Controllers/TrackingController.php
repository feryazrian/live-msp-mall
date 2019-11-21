<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Marketplace\Http\Requests;
use Marketplace\Http\Controllers\Controller;

use Marketplace\PpobTransaction;
use Marketplace\Transaction;
use Marketplace\TransactionProduct;
use Marketplace\TransactionShipping;
use Marketplace\TransactionReview;
use Marketplace\TransactionPayment;
use Marketplace\TransactionPaymentHistory;
use Marketplace\TransactionPromo;
use Marketplace\Product;
use Marketplace\Balance;
use Marketplace\BalanceDeposit;
use Marketplace\BalanceTransaction;
use Marketplace\VoucherTransaction;
use Marketplace\VoucherClaim;
use Marketplace\PointTopup;
use Marketplace\PointProduct;
use Marketplace\Option;
use Marketplace\Coupon;
use Marketplace\CronLog;

use Auth;
use Curl;
use Carbon\Carbon;

use Marketplace\Veritrans\Veritrans;

class TrackingController extends Controller
{
    private $kredivo;

    public function __construct()
    {
        Veritrans::$serverKey = env('MIDTRANS_SERVER_KEY');
        Veritrans::$isProduction = env('MIDTRANS_PRODUCTION');
        $this->kredivo = new KredivoController;
    }

    public function listSell(Request $request)
    {
        // Initialization
		$pageTitle = 'Transaksi Penjualan';
        $transactionProduct = array();

        // List Sell Product
        $transactionProductCheck = TransactionProduct::where('user_id', Auth::user()->id)
            ->where('status', '>', 0)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id')
                ->orderBy('created_at', 'desc');
            })
            ->groupBy('transaction_id')
            ->orderBy('updated_at', 'DESC')
            ->get();

        foreach ($transactionProductCheck as $transaction) {
            // Product Price
            $sellerPrice = 0;

            $transactionCheck = TransactionProduct::where('user_id', Auth::user()->id)
                ->where('transaction_id', $transaction->transaction_id)
                ->get();

            foreach ($transactionCheck as $transactionCount) {
                $sellerPrice += ($transactionCount->price * $transactionCount->unit);
            }
            
            // Shipping Price
            $transactionShipping = TransactionShipping::where('transaction_id', $transaction->transaction_id)
                ->where('user_id', $transaction->user_id)
                ->first();

            if (!empty($transactionShipping))
            {
                $sellerPrice = $sellerPrice + $transactionShipping->price;
            }

            // Array Price
            $arrayAdd = array_add($transaction, 'sellerprice', $sellerPrice);

            $transactionProduct[] = $arrayAdd;
        }

        // Return View
		return view('tracking.list-sell')->with([
            'pageTitle' => $pageTitle,
			'transactionProduct' => $transactionProduct,
		]);
    }

    public function listBuy(Request $request)
    {
        // Initialization
        $pageTitle = 'Pembelian Produk';
        $transactionProduct = array();

        // List Buy Product
        $transactionProductCheck = TransactionProduct::whereHas('transaction', function($q) {
                $q->where('user_id', Auth::user()->id)
                    ->whereNotNull('address_id')
                    ->whereNotNull('payment_id')
                    ->orderBy('updated_at', 'desc');
            })
            ->groupBy('user_id', 'transaction_id')
            ->orderBy('updated_at', 'DESC')
            ->get();


        foreach ($transactionProductCheck as $transaction) {
            // Product Price
            $sellerPrice = 0;
            $sellerUnit = 0;
            
            $transactionCheck = TransactionProduct::where('transaction_id', $transaction->transaction_id)
                ->where('user_id', $transaction->user_id)
                ->get();

            foreach ($transactionCheck as $transactionCount) {
                $sellerUnit += $transactionCount->unit;
                $sellerPrice += ($transactionCount->price * $transactionCount->unit);
            }
            
            // Shipping Price
            $transactionShipping = TransactionShipping::where('transaction_id', $transaction->transaction_id)
                ->where('user_id', $transaction->user_id)
                ->first();

            if (!empty($transactionShipping))
            {
                $sellerPrice = $sellerPrice + $transactionShipping->price;
            }

            // Array price
            $arrayAdd2 = array_add($transaction, 'sellerprice', $sellerPrice);
            $arrayAdd = array_add($arrayAdd2, 'sellerunit', $sellerUnit);

            $transactionProduct[] = $arrayAdd;
        }

        // Return View
		return view('tracking.list-buy')->with([
            'pageTitle' => $pageTitle,
			'transactionProduct' => $transactionProduct,
		]);
    }

    public function listBuyVoucher(Request $request)
    {
        // Initialization
        $pageTitle = 'Pembelian E-Voucher';

        // List Buy Product
        $transactionVoucher = VoucherTransaction::where('user_id', Auth::user()->id)
            ->whereNotNull('payment_id')
            ->orderBy('created_at', 'DESC')
            ->get();

        // Return View
		return view('tracking.list-buy-voucher')->with([
            'pageTitle' => $pageTitle,
			'transactionVoucher' => $transactionVoucher,
		]);
    }

    public function listSellVoucher(Request $request)
    {
        // Initialization
        $pageTitle = 'Penjualan E-Voucher';

        // List Buy Product
        $transactionVoucher = VoucherTransaction::whereHas('product', function($q) {
                $q->where('user_id', Auth::user()->id);
            })
            ->whereNotNull('payment_id')
            ->where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->get();

        // Return View
		return view('tracking.list-sell-voucher')->with([
            'pageTitle' => $pageTitle,
			'transactionVoucher' => $transactionVoucher,
		]);
    }

    public function listBuyDigital(){
        // Initialization
        $pageTitle = 'Penjualan Digital';

        // List Buy Digital Product
        $transaction = PpobTransaction::join('transactions', 'ppob_transactions.transaction_id', '=', 'transactions.id')
            ->join('ppob_types', 'ppob_transactions.type_id', '=', 'ppob_types.id')
            ->join('ppob_operators', 'ppob_transactions.operator_id', '=', 'ppob_operators.id')
            ->where('ppob_transactions.user_id', Auth::user()->id)
            ->whereNotNull('ppob_transactions.payment_id')
            ->orderBy('ppob_transactions.created_at', 'DESC')
            ->select('ppob_transactions.id', 'ppob_transactions.plan_id', 'ppob_transactions.transaction_id', 'ppob_transactions.reff_id', 'ppob_transactions.cust_number', 'ppob_transactions.tr_code', 'ppob_transactions.price', 'transactions.total', 'ppob_transactions.status', DB::raw('DATE_FORMAT(ppob_transactions.created_at, "%d %b %Y %H:%i") AS order_date'), 'ppob_transactions.response_json', 'ppob_operators.id AS opr_id', 'ppob_operators.slug AS opr_slug', 'ppob_operators.name AS opr_name', 'ppob_types.id AS type_id', 'ppob_types.slug AS type_slug', 'ppob_types.name AS type_name')
            ->paginate('10');
        $priceDetail = new DigitalController;
        foreach ($transaction as $key => $value) {
            $transaction[$key]->priceDetail = $priceDetail->pricelistDetail($value->tr_code, $value->type_slug);
        }

        return view('tracking.list-buy-digital')
            ->with([
                'pageTitle' => $pageTitle,
                'transaction' => $transaction
            ]);
    }

    public function tracking(Request $request)
    {
        // Initialization
        $pageTitle = null;
        $transactionAccess = false;
        $transactionProductId = $request->id;
        // dd($request);

        if (empty($transactionProductId)) {
        	return redirect('/');
        }

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            // ->orWhere('transaction_id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->first();

        // Transaction Product Availability Check
        if (empty($transactionProduct)) {
        	return redirect('/');
        }
        
        // Transaction Approval Expired (2 Days)
        if ($transactionProduct->status == 1) {

            // Preorder
            if (!empty($transactionProduct->product->preorder_target)) {
                if (Carbon::now() >= $transactionProduct->product->preorder_expired) {
                    $preorderCount = TransactionProduct::where('product_id', $transactionProduct->product_id)
                        ->where('status', 1)
                        ->sum('unit');
                    
                    if ($preorderCount < $transactionProduct->product->preorder_target) {
                        // Transaction Refund Payment
                        $this->refundPayment($transactionProductId, 2);
                    }
                }
            }

            // Non Preorder
            if (empty($transactionProduct->product->preorder_target))
            {
                if (Carbon::now() >= $transactionProduct->updated_at->addDays(2)) 
                {
                    // Transaction Refund Payment
                    $this->refundPayment($transactionProductId, 1);

                    // Transaction Product Recheck
                    $transactionProduct = TransactionProduct::where('id', $transactionProductId)
                        ->whereHas('transaction', function($q) {
                            $q->whereNotNull('address_id')
                            ->whereNotNull('payment_id');
                        })
                        ->first();
                }
            }
        }

        // Transaction Product Authorization Check
        $sellerId = $transactionProduct->user_id;
        $buyerId = $transactionProduct->transaction->user_id;

        if ($sellerId == Auth::user()->id || $buyerId == Auth::user()->id) {
            $transactionAccess = true;
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
        	return redirect('/');
        }

        if ($sellerId == Auth::user()->id) {
            $transactionAccess = 1;
        }

        if ($buyerId == Auth::user()->id) {
            $transactionAccess = 2;
        }

        $productList = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->get();

        $transactionShipping = TransactionShipping::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->first();

        // Shippping Tracking History
        $shippingTracking = null;

        if (!empty($transactionShipping->code))
        {
            // Initialization
            $key = env('RAJAONGKIR_APIKEY');
            $waybill = $transactionShipping->code;
            $courier = strtolower($transactionShipping->service);
            $response = null;

            // Check User
            $response = Curl::to(env('RAJAONGKIR_ENDPOINTAPI').'/waybill')
                ->withData(array(
                    'key' => $key,
                    'waybill' => $waybill,
                    'courier' => $courier
                ))
                ->asJson()
                ->post();
            
            if (!empty($response)) {
                $shippingTracking = $response->rajaongkir;
            }

            if ($courier == 'mse')
            {
                $express = new Shipping\TrackingController;
                
                $response = $express->json($waybill);

                $response =  json_encode($response, JSON_FORCE_OBJECT);
                $response = json_decode($response);
                
                $shippingTracking = $response->items;
            }

            // Transaction Auto Complete (2 Days)
            if ($transactionProduct->status == 2) {
                if (!empty($shippingTracking)) {
                    if (!empty($shippingTracking->result)) {
                        if ($shippingTracking->result->delivered == 1) {
                            if (Carbon::now() >= Carbon::parse($shippingTracking->result->delivery_status->pod_date)->addDays(2)) {
                                $this->confirmPayment($transactionProductId, 5, 'Terimakasih');
                            }
                        }
                    }
                }
            }
        }

        if ($transactionProduct->status == 0)
        {
            // START - Transaction Status Check
            if ($transactionProduct->transaction->gateway->id === 1) {
                $midtrans = new Veritrans;
                $notif = $midtrans->status($transactionProduct->transaction_id);
            } else {
                $url = $this->kredivo->endpoint . '/transaction/status';
                $bodyData = [
                    "server_key"    => $this->kredivo->server_key,
                    "order_id"      => $transactionProduct->transaction_id
                ];

                $notif = Curl::to($url)
                    ->withData($bodyData)
                    ->asJson()
                    ->post();

                $notif->status_code = $this->kredivo->getStatusCodeByTransStatus($notif->transaction_status);
                $notif->status_message = $notif->message;
                $notif->gross_amount = $notif->amount;
                $notif->payment_type = $transactionProduct->transaction->gateway->slug;
            }

            $json_result = json_encode($notif);
            switch ($notif->payment_type) {
                case 'gopay':
                    $expired_time = 'Transaksi menunggu pembayaran pelanggan, transaksi akan kadaluarsa dalam waktu 15 menit';
                    break;
                case 'kredivo':
                    $expired_time = 'Transaksi menunggu konfirmasi pembayaran dari Kredivo';
                    break;
                default:
                    $expired_time = 'Transaksi menunggu pembayaran pelanggan, transaksi akan kadaluarsa dalam waktu 24 jam'; // include expired time
                    break;
            }

            // Transaction Payment Status Validation
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = '';
            $finish_redirect_url = '';
            $gross_amount = '';
            $pdf_url = '';
            $transactionSuccess = null;

            if (!empty($notif->pdf_url)) {
                $pdf_url = $notif->pdf_url;
            }

            if (!empty($notif->gross_amount)) {
                $gross_amount = $notif->gross_amount;
            }

            if (!empty($notif->fraud_status)) {
                $fraud = $notif->fraud_status;
            }

            if (!empty($notif->finish_redirect_url)) {
                $finish_redirect_url = $notif->finish_redirect_url;
            }

            // Transaction Payment Status
            if ($transaction == 'capture')
            {
                if ($type == 'credit_card')
                {
                    if ($fraud == 'challenge')
                    {
                        // Menunggu - Approve Admin
                        $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> melalui <b>" . $type . "</b> menunggu konfirmasi dari admin (challenged by FDS)";
                        $transactionView = 'transaction.validation';
                    }
                    else
                    {
                        // Transaksi Sukses
                        $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> telah berhasil menggunakan <b>" . $type . "</b>";
                        $transactionView = 'transaction.success';

                        $transactionSuccess = $order_id;
                    }
                }

            }
            else if ($transaction == 'settlement')
            {
                // Transaksi Sukses
                $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> telah berhasil menggunakan <b>" . $type . "</b>";
                $transactionView = 'transaction.success';

                $transactionSuccess = $order_id;

            }
            else if($transaction == 'pending')
            {
                // Menunggu - Pengguna Menyelesaikan Transaksi
                $transactionMessage = "Menunggu anda untuk menyelesaikan Transaksi dengan Kode: <b>" . $order_id . "</b> menggunakan <b>" . $type . "</b>";
                $transactionView = 'transaction.pending';

                if (!empty($notif->pdf_url)) {
                    $pdf_url = $notif->pdf_url;
                }
            }
            else if ($transaction == 'deny' || $transaction == 'cancel' || $transaction == 'expire')
            {
                // Transaksi Ditolak
                $transactionMessage = "Pembayaran melalui " . $type . " pada Transaksi dengan Kode: <b>" . $order_id . "</b> di Tolak.";
                $transactionView = 'transaction.error';

                $this->cancelPayment($transactionProduct->transaction_id);
            }

            if (!empty($notif))
            {
                $result = $notif;
                $statusCode = $result->status_code;
                $statusMessage = $result->status_message;
                $transactionId = $result->transaction_id;
                $orderId = $result->order_id;
                $resultContent = $json_result;
                $orderType = substr($orderId, 0, 4);


                // Order Type
                switch ($orderType) {
                    // Transaction Point
                    case config('app.point_code'):
                        $pointTopupCheck = PointTopup::where('transaction_id', $orderId)
                            ->first();

                        if (empty($pointTopupCheck))
                        {
                            return '';
                        }

                        $userId = $pointTopupCheck->user_id;
                        break;
                
                    // Transaction Balance
                    case config('app.balance_code'):
                        $balanceDepositCheck = BalanceDeposit::where('transaction_id', $orderId)
                            ->first();

                        if (empty($balanceDepositCheck))
                        {
                            return '';
                        }

                        $userId = $balanceDepositCheck->user_id;
                        break;
                    
                    // Transaction Mall
                    default:
                        $transactionUserCheck = Transaction::where('id', $orderId)
                            ->first();

                        $userId = $transactionUserCheck->user_id;
                        break;
                }

                $transactionPaymentCheck = TransactionPayment::where('user_id', $userId)
                    ->where('order_id', $orderId)
                    ->first();

                // Transaction Payment - Create
                if (empty($transactionPaymentCheck))
                {
                    // Transaction Payment History
                    $transactionPaymentHistory = new TransactionPaymentHistory;

                    $transactionPaymentHistory->user_id = $userId;
                    $transactionPaymentHistory->status_code = $result->status_code;
                    $transactionPaymentHistory->status_message = $result->status_message;
                    $transactionPaymentHistory->transaction_id = $result->transaction_id;
                    $transactionPaymentHistory->order_id = $orderId;
                    $transactionPaymentHistory->gross_amount = $result->gross_amount;

                    $transactionPaymentHistory->payment_type = $result->payment_type;
                    $transactionPaymentHistory->transaction_time = $result->transaction_time;
                    $transactionPaymentHistory->transaction_status = $result->transaction_status;

                    $transactionPaymentHistory->fraud_status = $fraud;
                    $transactionPaymentHistory->finish_redirect_url = $finish_redirect_url;
                    $transactionPaymentHistory->result = $resultContent;
                    $transactionPaymentHistory->save();

                    // Transaction Payment
                    $transactionPayment = new TransactionPayment;

                    $transactionPayment->user_id = $userId;
                    $transactionPayment->status_code = $result->status_code;
                    $transactionPayment->status_message = $result->status_message;
                    $transactionPayment->transaction_id = $result->transaction_id;
                    $transactionPayment->order_id = $orderId;
                    $transactionPayment->gross_amount = $result->gross_amount;

                    $transactionPayment->payment_type = $result->payment_type;
                    $transactionPayment->transaction_time = $result->transaction_time;
                    $transactionPayment->transaction_status = $result->transaction_status;

                    $transactionPayment->fraud_status = $fraud;
                    $transactionPayment->finish_redirect_url = $finish_redirect_url;
                    $transactionPayment->result = $resultContent;
                    $transactionPayment->save();

                    // Transaction Payment ID
                    $transactionPaymentId = $transactionPayment->id;
                }

                // Transaction Payment - Update
                if (!empty($transactionPaymentCheck))
                {
                    $transactionPayment = TransactionPayment::where('user_id', $userId)
                        ->where('order_id', $orderId)
                        ->update([
                            'status_code' => $result->status_code,
                            'status_message' => $result->status_message,
                            'transaction_id' => $result->transaction_id,
                            'gross_amount' => $result->gross_amount,
                            'payment_type' => $result->payment_type,
                            'transaction_time' => $result->transaction_time,
                            'transaction_status' => $result->transaction_status,
                            'result' => $resultContent
                    ]);

                    $transactionPaymentId = $transactionPaymentCheck->id;
                }

                // Order Type
                switch ($orderType) {
                    // Transaction Point
                    case config('app.point_code'):
                        // Update
                        $pointTopupUpdate = PointTopup::where('transaction_id', $orderId)
                            ->where('user_id', $userId)
                            ->update([
                                'payment_id' => $transactionPaymentId
                        ]);
                        
                        // Check
                        $pointTopupCheck = PointTopup::where('transaction_id', $orderId)
                            ->where('user_id', $userId)
                            ->first();

                        $pointTopupId = $pointTopupCheck->id;

                        // Transaction Update Status
                        if (!empty($transactionSuccess)) {
                            $pointTopupStatusCheck = PointTopup::where('transaction_id', $orderId)
                                ->where('user_id', $userId)
                                ->where('status', 1)
                                ->first();

                            if (empty($pointTopupStatusCheck)) {
                                // Point
                                $operation = 'update_mspoint';
            
                                $point = $pointTopupCheck->point;
                    
                                $username = $pointTopupCheck->user->username;
                    
                                // Point Plus
                                $response = new MsplifeController;
                                $response = $response->update_mspoint($operation, $username, $point);
    
                                // Status
                                $pointTopupStatus = PointTopup::where('transaction_id', $orderId)
                                    ->where('user_id', $userId)
                                    ->update(['status' => 1]);
                            }
                        }
                        break;
                
                    // Transaction Balance
                    case config('app.balance_code'):
                        // Transaction - Update Transaction Payment ID
                        $balanceDepositCheck = BalanceDeposit::where('transaction_id', $orderId)
                            ->where('user_id', $userId)
                            ->first();

                        if (empty($balanceDepositCheck)) {
                            $balanceDeposit = new BalanceDeposit;
                            $balanceDeposit->user_id = $userId;
                            $balanceDeposit->payment_id = $transactionPaymentId;
                            $balanceDeposit->transaction_id = $orderId;
                            $balanceDeposit->save();

                            $balanceDepositId = $balanceDeposit->id;
                        }

                        if (!empty($balanceDepositCheck)) {
                            $balanceDeposit = BalanceDeposit::where('transaction_id', $orderId)
                                ->where('user_id', $userId)
                                ->update([
                                    'payment_id' => $transactionPaymentId
                            ]);

                            $balanceDepositId = $balanceDepositCheck->id;
                        }

                        // Transaction Update Status
                        if (!empty($transactionSuccess)) {
                            $balanceDepositStatusCheck = BalanceDeposit::where('transaction_id', $orderId)
                                ->where('user_id', $userId)
                                ->where('status', 1)
                                ->first();

                            if(empty($balanceDepositStatusCheck)) {
                                $balanceNew = new Balance;
                                $balanceNew->user_id = $userId;
                                $balanceNew->deposit_id = $balanceDepositId;
                                $balanceNew->notes = 'Penambahan Saldo';
                                $balanceNew->save();

                                $balanceDepositStatus = BalanceDeposit::where('transaction_id', $orderId)
                                    ->where('user_id', $userId)
                                    ->update([
                                        'status' => 1
                                ]);
                            }
                        }
                        break;
                    
                    // Transaction Mall
                    default:
                        // Transaction - Update Transaction Payment ID
                        $transactionUpdate = Transaction::where('id', $orderId)
                            ->where('user_id', $userId)
                            ->update([
                                'payment_id' => $transactionPaymentId
                        ]);

                        // Transaction Update Status
                        if (!empty($transactionSuccess)) {
                            $transactionProductStatus = TransactionProduct::where('transaction_id', $transactionSuccess)
                                ->where('status', '0')
                                ->update([
                                    'status' => '1'
                            ]);
                        }
                        break;
                }
            }
            // END - Transaction Status Check
        }

        // Page Title
        $pageTitle = 'Transaksi '.$transactionProduct->transaction_id.'#'.$transactionProduct->user_id;

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            // ->orWhere('transaction_id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->first();

        if ($transactionProduct->status == 0) {
            // Transaction Payment - Result
            $paymentResult = null;

            $transactionPaymentHistory = TransactionPaymentHistory::where('user_id', $userId)
                ->where('order_id', $orderId)
                ->where('finish_redirect_url', '!=', '')
                ->orderBy('id', 'ASC')
                ->first();

            if (!empty($transactionPaymentHistory)) {
                $paymentResult = json_decode($transactionPaymentHistory->result);

                if (!empty($paymentResult->pdf_url)) {
                    $pdf_url = $paymentResult->pdf_url;
                }
            }
            
            // Return View
            return view('tracking.tracking')->with([
                'pageTitle' => $pageTitle,
    			'transactionProduct' => $transactionProduct,
    			'shipping' => $transactionShipping,
    			'shippingTracking' => $shippingTracking,
    			'productList' => $productList,
    			'status' => $transactionProduct->status,
                'access' => $transactionAccess,
                'expired_time' => $expired_time,
                'transactionCode' => $order_id,
                'transactionTotal' => $gross_amount,
                'transactionAttachment' => $pdf_url,
                'transactionView' => $transactionView,
                'transactionMessage' => $transactionMessage,
                'paymentResult' => $paymentResult
            ]);
        }

        // Return View
        return view('tracking.tracking')->with([
            'pageTitle' => $pageTitle,
			'transactionProduct' => $transactionProduct,
			'shipping' => $transactionShipping,
			'shippingTracking' => $shippingTracking,
			'productList' => $productList,
			'status' => $transactionProduct->status,
            'access' => $transactionAccess
        ]);
    }
    public function invoice(Request $request)
    {
        // Initialization
        $pageTitle = null;
        $transactionAccess = false;
        $transactionId = $request->id;

        if (empty($transactionId)) {
        	return redirect('/');
        }

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->first();

        // Transaction Product Availability Check
        if (empty($transactionProduct)) {
        	return redirect('/');
        }

        // Transaction Product Authorization Check
        $sellerId = $transactionProduct->user_id;
        $buyerId = $transactionProduct->transaction->user_id;

        if ($buyerId == Auth::user()->id) {
            $transactionAccess = true;
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
        	return redirect('/');
        }

        if ($sellerId == Auth::user()->id) {
            $transactionAccess = 1;
        }

        if ($buyerId == Auth::user()->id) {
            $transactionAccess = 2;
        }

        $productList = TransactionProduct::where('transaction_id', $transactionId)
            ->get();

        $shippingList = TransactionShipping::where('transaction_id', $transactionId)
            ->get();

        $promoList = TransactionPromo::where('transaction_id', $transactionId)
            ->get();

        // Page Title
        $pageTitle = 'Transaksi '.$transactionId;

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->first();

        // Return View
        return view('tracking.invoice')->with([
            'pageTitle' => $pageTitle,
            'transactionProduct' => $transactionProduct,
            
			'shippingList' => $shippingList,
            'productList' => $productList,
            'promoList' => $promoList,
            
			'access' => $transactionAccess,
        ]);
    }
    public function cancelPayment($transactionId)
    {
        // Initialization
        $transactionId = $transactionId;
        $cancel = "Pembeli Melebihi Batas Waktu Penyelesaian Pembayaran";
        
        DB::beginTransaction();

        // Transaction Product Check
        $transactionProductGroup = TransactionProduct::where('transaction_id', $transactionId)
            ->where('status', 0)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->groupBy('user_id')
            ->get();

        foreach ($transactionProductGroup as $transactionProduct)
        {
            // Cancel Kredivo Payment Transaction
            $transactionPayment = TransactionPayment::where('order_id', $transactionProduct->transaction_id)->first();
            if ($transactionPayment->gateway_id === 4) {
                try {
                    $data = [
                        'order_id'          => $transactionProduct->transaction_id,
                        'transaction_id'    => $transactionPayment->transaction_id,
                        'reason'            => $cancel,
                        'cancel_by'         => $transactionProduct->user->name,
                    ];

                    $post = $this->kredivo->cancelTransaction($data);
                    if ($post->status === 'ERROR') {
                        return redirect()->back()->with(['warning' => $post->message]);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                    return redirect()->back()->with(['warning' => 'Oopss!.. Something when wrong. Please contact the Administrator']);
                }
            }

            // Transaction Product Authorization Check
            $sellerId = $transactionProduct->user_id;
            $buyerId = $transactionProduct->transaction->user_id;

            // Transaction Product Cancel
            $transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->update(['cancel' => $cancel]);

            // Transaction Status Check Cancel
            if ($transactionProduct->status != 6) {
                // Transaction Product List
                $transactionProductList = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                    ->where('user_id', $sellerId)
                    ->get();

                // Transaction Product Update Stock
                foreach ($transactionProductList as $transaction) {
                    if (!empty($transaction->product))
                    {
                        $productStock = ($transaction->product->stock + $transaction->unit);
                        $productSold = Product::where('id', $transaction->product_id)
                            ->update([
                                'stock' => $productStock
                        ]);
                    }
                }
            }

            // Transaction Product Status Cancel
            $transactionStatus= TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->update([
                    'status' => 7
            ]);
        }

        DB::commit();
    }

    public function balance(Request $request)
    {
        // Initialization
        $pageTitle = 'Detail Transaksi';
        $balanceId = $request->id;
        $userId = Auth::user()->id;

        if (empty($balanceId)) {
        	return redirect('/');
        }

        $balanceDeposit = BalanceDeposit::where('id', $balanceId)
            ->where('user_id', $userId)
            ->first();

        if (empty($balanceDeposit)) {
            return redirect('/');
        }
    
        // START - Transaction Status Check
        $midtrans = new Veritrans;

        if(!isset($midtrans->status)){
            $transaction_payment_histories = TransactionPayment::where('order_id',$balanceDeposit->transaction_id)
                                            ->orderBy('status_code','asc')
                                            ->first();

            if ($transaction_payment_histories->transaction_status == 'settlement')
            {
                // Transaksi Sukses
                $transactionTitle = "Transaksi Sukses";
                $transactionMessage = "Transaksi dengan Kode: <b>" . $balanceDeposit->transaction_id . "</b> telah berhasil menggunakan <b>" . $transaction_payment_histories->payment_type . "</b>";
                $transactionView = 'transaction.success';
                $transactionSuccess = $balanceDeposit->transaction_id;
            }
            else if($transaction_payment_histories->transaction_status == 'pending')
            {
                // Menunggu - Pengguna Menyelesaikan Transaksi
                $transactionTitle = "Transaksi Pending";
                $transactionMessage = "Menunggu anda untuk menyelesaikan Transaksi dengan Kode: <b>" . $balanceDeposit->transaction_id  . "</b> menggunakan <b>" . $transaction_payment_histories->payment_type . "</b>";
                $transactionView = 'transaction.pending';
            }
            else if ($transaction_payment_histories->transaction_status == 'deny' || $transaction_payment_histories->transaction_status == 'cancel' || $transaction_payment_histories->transaction_status == 'expire')
            {
                // Transaksi Ditolak
                $transactionTitle = "Transaksi Ditolak";
                $transactionMessage = "Pembayaran melalui " . $transaction_payment_histories->payment_type. " pada Transaksi dengan Kode: <b>" . $balanceDeposit->transaction_id  . "</b> di Tolak.";
                $transactionView = 'transaction.error';
            }

            return view($transactionView)->with([
                'pageTitle' => $transactionTitle,
                'transactionCode' => $balanceDeposit->transaction_id,
                'transactionTotal' => $transaction_payment_histories->gross_amount,
                'transactionMessage' => $transactionMessage
            ]);
        }
        $notif = $midtrans->status($balanceDeposit->transaction_id);      

        $json_result = json_encode($notif);

        // Transaction Payment Status Validation
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = '';
        $finish_redirect_url = '';
        $gross_amount = '';
        $pdf_url = '';
        $transactionSuccess = null;

        if (!empty($notif->pdf_url)) {
            $pdf_url = $notif->pdf_url;
        }

        if (!empty($notif->gross_amount)) {
            $gross_amount = $notif->gross_amount;
        }

        if (!empty($notif->fraud_status)) {
            $fraud = $notif->fraud_status;
        }

        if (!empty($notif->finish_redirect_url)) {
            $finish_redirect_url = $notif->finish_redirect_url;
        }

        // Transaction Payment Status
        if ($transaction == 'capture')
        {
            if ($type == 'credit_card')
            {
                if ($fraud == 'challenge')
                {
                    // Menunggu - Approve Admin
                    $transactionTitle = "Menunggu Approve Admin";
                    $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> melalui <b>" . $type . "</b> menunggu konfirmasi dari admin (challenged by FDS)";
                    $transactionView = 'transaction.validation';
                }
                else
                {
                    // Transaksi Sukses
                    $transactionTitle = "Transaksi Sukses";
                    $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> telah berhasil menggunakan <b>" . $type . "</b>";
                    $transactionView = 'transaction.success';

                    $transactionSuccess = $order_id;
                }
            }

        }
        else if ($transaction == 'settlement')
        {
            // Transaksi Sukses
            $transactionTitle = "Transaksi Sukses";
            $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> telah berhasil menggunakan <b>" . $type . "</b>";
            $transactionView = 'transaction.success';

            $transactionSuccess = $order_id;

        }
        else if($transaction == 'pending')
        {
            // Menunggu - Pengguna Menyelesaikan Transaksi
            $transactionTitle = "Transaksi Pending";
            $transactionMessage = "Menunggu anda untuk menyelesaikan Transaksi dengan Kode: <b>" . $order_id . "</b> menggunakan <b>" . $type . "</b>";
            $transactionView = 'transaction.pending';

            if (!empty($result->pdf_url)) {
                $pdf_url = $result->pdf_url;
            }
        }
        else if ($transaction == 'deny' || $transaction == 'cancel' || $transaction == 'expire')
        {
            // Transaksi Ditolak
            $transactionTitle = "Transaksi Ditolak";
            $transactionMessage = "Pembayaran melalui " . $type . " pada Transaksi dengan Kode: <b>" . $order_id . "</b> di Tolak.";
            $transactionView = 'transaction.error';

            $transactionStatus = TransactionProduct::where('transaction_id', $order_id)
                ->update([
                    'status' => 7,
                    'cancel' => 'Pembeli Melebihi Batas Waktu Penyelesaian Pembayaran'
            ]);
        }

        if (!empty($notif))
        {
            $result = $notif;
            $statusCode = $result->status_code;
            $statusMessage = $result->status_message;
            $transactionId = $result->transaction_id;
            $orderId = $result->order_id;
            $resultContent = $json_result;
            $orderType = substr($orderId, 0, 4);

            // Transaction Payment - Check Availability
            $transactionPaymentCheck = TransactionPayment::where('user_id', $userId)
                ->where('order_id', $orderId)
                ->first();

            // Transaction Payment - Create
            if (empty($transactionPaymentCheck))
            {
                // Transaction Payment History
                $transactionPaymentHistory = new TransactionPaymentHistory;

                $transactionPaymentHistory->user_id = $userId;
                $transactionPaymentHistory->status_code = $result->status_code;
                $transactionPaymentHistory->status_message = $result->status_message;
                $transactionPaymentHistory->transaction_id = $result->transaction_id;
                $transactionPaymentHistory->order_id = $orderId;
                $transactionPaymentHistory->gross_amount = $result->gross_amount;

                $transactionPaymentHistory->payment_type = $result->payment_type;
                $transactionPaymentHistory->transaction_time = $result->transaction_time;
                $transactionPaymentHistory->transaction_status = $result->transaction_status;

                $transactionPaymentHistory->fraud_status = $fraud;
                $transactionPaymentHistory->finish_redirect_url = $finish_redirect_url;
                $transactionPaymentHistory->result = $resultContent;
                $transactionPaymentHistory->save();

                // Transaction Payment
                $transactionPayment = new TransactionPayment;

                $transactionPayment->user_id = $userId;
                $transactionPayment->status_code = $result->status_code;
                $transactionPayment->status_message = $result->status_message;
                $transactionPayment->transaction_id = $result->transaction_id;
                $transactionPayment->order_id = $orderId;
                $transactionPayment->gross_amount = $result->gross_amount;

                $transactionPayment->payment_type = $result->payment_type;
                $transactionPayment->transaction_time = $result->transaction_time;
                $transactionPayment->transaction_status = $result->transaction_status;

                $transactionPayment->fraud_status = $fraud;
                $transactionPayment->finish_redirect_url = $finish_redirect_url;
                $transactionPayment->result = $resultContent;
                $transactionPayment->save();

			    // Transaction Payment ID
                $transactionPaymentId = $transactionPayment->id;
            }

            // Transaction Payment - Update
            if (!empty($transactionPaymentCheck))
            {
                $transactionPayment = TransactionPayment::where('user_id', $userId)
                    ->where('order_id', $orderId)
                    ->update([
                        'status_code' => $result->status_code,
                        'status_message' => $result->status_message,
                        'transaction_id' => $result->transaction_id,
                        'gross_amount' => $result->gross_amount,
                        'payment_type' => $result->payment_type,
                        'transaction_time' => $result->transaction_time,
                        'transaction_status' => $result->transaction_status,
                        'result' => $resultContent
                ]);

                $transactionPaymentId = $transactionPaymentCheck->id;
            }

            // START | IF Transaction == Balance Deposit
            if ($orderType == config('app.balance_code'))
            {
                // Transaction - Update Transaction Payment ID
                $balanceDepositCheck = BalanceDeposit::where('transaction_id', $orderId)
                    ->where('user_id', $userId)
                    ->first();

                if (empty($balanceDepositCheck)) {
                    $balanceDeposit = new BalanceDeposit;
                    $balanceDeposit->user_id = $userId;
                    $balanceDeposit->payment_id = $transactionPaymentId;
                    $balanceDeposit->transaction_id = $orderId;
                    $balanceDeposit->save();

                    $balanceDepositId = $balanceDeposit->id;
                }

                if (!empty($balanceDepositCheck)) {
                    $balanceDeposit = BalanceDeposit::where('transaction_id', $orderId)
                        ->where('user_id', $userId)
                        ->update([
                            'payment_id' => $transactionPaymentId
                    ]);

                    $balanceDepositId = $balanceDepositCheck->id;
                }

                // Transaction Update Status
                if (!empty($transactionSuccess)) {
                    $balanceDepositStatusCheck = BalanceDeposit::where('transaction_id', $orderId)
                        ->where('user_id', $userId)
                        ->where('status', 1)
                        ->first();

                    if(empty($balanceDepositStatusCheck)) {
                        $balanceNew = new Balance;
                        $balanceNew->user_id = $userId;
                        $balanceNew->deposit_id = $balanceDepositId;
                        $balanceNew->notes = 'Penambahan Saldo';
                        $balanceNew->save();

                        $balanceDepositStatus = BalanceDeposit::where('transaction_id', $orderId)
                            ->where('user_id', $userId)
                            ->update([
                                'status' => 1
                        ]);
                    }
                }
            }
        }
        // END - Transaction Status Check
        
        // Transaction Payment View
        return view($transactionView)->with([
            'pageTitle' => $transactionTitle,
            'transactionCode' => $order_id,
            'transactionTotal' => $gross_amount,
            'transactionAttachment' => $pdf_url,
            'transactionMessage' => $transactionMessage
        ]);
    }

    public function point(Request $request)
    {
        // Initialization
        $pageTitle = 'Detail Transaksi';
        $topupId = $request->id;
        $userId = Auth::user()->id;

        if (empty($topupId)) {
        	return redirect('/');
        }

        $pointTopup = PointTopup::where('id', $topupId)
            ->where('user_id', $userId)
            ->first();

        if (empty($pointTopup)) {
            return redirect('/');
        }
    
        // START - Transaction Status Check
        $midtrans = new Veritrans;

        $notif = $midtrans->status($pointTopup->transaction_id);
        $json_result = json_encode($notif);

        // Transaction Payment Status Validation
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = '';
        $finish_redirect_url = '';
        $gross_amount = '';
        $pdf_url = '';
        $transactionSuccess = null;
        $transactionCancel = null;

        if (!empty($notif->pdf_url)) {
            $pdf_url = $notif->pdf_url;
        }

        if (!empty($notif->gross_amount)) {
            $gross_amount = $notif->gross_amount;
        }

        if (!empty($notif->fraud_status)) {
            $fraud = $notif->fraud_status;
        }

        if (!empty($notif->finish_redirect_url)) {
            $finish_redirect_url = $notif->finish_redirect_url;
        }

        // Transaction Payment Status
        if ($transaction == 'capture')
        {
            if ($type == 'credit_card')
            {
                if ($fraud == 'challenge')
                {
                    // Menunggu - Approve Admin
                    $transactionTitle = "Menunggu Approve Admin";
                    $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> melalui <b>" . $type . "</b> menunggu konfirmasi dari admin (challenged by FDS)";
                    $transactionView = 'transaction.validation';
                }
                else
                {
                    // Transaksi Sukses
                    $transactionTitle = "Transaksi Sukses";
                    $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> telah berhasil menggunakan <b>" . $type . "</b>";
                    $transactionView = 'transaction.success';

                    $transactionSuccess = $order_id;
                }
            }

        }
        else if ($transaction == 'settlement')
        {
            // Transaksi Sukses
            $transactionTitle = "Transaksi Sukses";
            $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> telah berhasil menggunakan <b>" . $type . "</b>";
            $transactionView = 'transaction.success';

            $transactionSuccess = $order_id;

        }
        else if($transaction == 'pending')
        {
            // Menunggu - Pengguna Menyelesaikan Transaksi
            $transactionTitle = "Transaksi Pending";
            $transactionMessage = "Menunggu anda untuk menyelesaikan Transaksi dengan Kode: <b>" . $order_id . "</b> menggunakan <b>" . $type . "</b>";
            $transactionView = 'transaction.pending';

            if (!empty($result->pdf_url)) {
                $pdf_url = $result->pdf_url;
            }
        }
        else if ($transaction == 'deny' || $transaction == 'cancel' || $transaction == 'expire')
        {
            // Transaksi Ditolak
            $transactionTitle = "Transaksi Ditolak";
            $transactionMessage = "Pembayaran melalui " . $type . " pada Transaksi dengan Kode: <b>" . $order_id . "</b> di Tolak.";
            $transactionView = 'transaction.error';
            
            $transactionCancel = $order_id;
        }

        if (!empty($notif))
        {
            $result = $notif;
            $statusCode = $result->status_code;
            $statusMessage = $result->status_message;
            $transactionId = $result->transaction_id;
            $orderId = $result->order_id;
            $resultContent = $json_result;
            $orderType = substr($orderId, 0, 4);

            // Transaction Payment - Check Availability
            $transactionPaymentCheck = TransactionPayment::where('user_id', $userId)
                ->where('order_id', $orderId)
                ->first();

            // Transaction Payment - Create
            if (empty($transactionPaymentCheck))
            {
                // Transaction Payment History
                $transactionPaymentHistory = new TransactionPaymentHistory;

                $transactionPaymentHistory->user_id = $userId;
                $transactionPaymentHistory->status_code = $result->status_code;
                $transactionPaymentHistory->status_message = $result->status_message;
                $transactionPaymentHistory->transaction_id = $result->transaction_id;
                $transactionPaymentHistory->order_id = $orderId;
                $transactionPaymentHistory->gross_amount = $result->gross_amount;

                $transactionPaymentHistory->payment_type = $result->payment_type;
                $transactionPaymentHistory->transaction_time = $result->transaction_time;
                $transactionPaymentHistory->transaction_status = $result->transaction_status;

                $transactionPaymentHistory->fraud_status = $fraud;
                $transactionPaymentHistory->finish_redirect_url = $finish_redirect_url;
                $transactionPaymentHistory->result = $resultContent;
                $transactionPaymentHistory->save();

                // Transaction Payment
                $transactionPayment = new TransactionPayment;

                $transactionPayment->user_id = $userId;
                $transactionPayment->status_code = $result->status_code;
                $transactionPayment->status_message = $result->status_message;
                $transactionPayment->transaction_id = $result->transaction_id;
                $transactionPayment->order_id = $orderId;
                $transactionPayment->gross_amount = $result->gross_amount;

                $transactionPayment->payment_type = $result->payment_type;
                $transactionPayment->transaction_time = $result->transaction_time;
                $transactionPayment->transaction_status = $result->transaction_status;

                $transactionPayment->fraud_status = $fraud;
                $transactionPayment->finish_redirect_url = $finish_redirect_url;
                $transactionPayment->result = $resultContent;
                $transactionPayment->save();

			    // Transaction Payment ID
                $transactionPaymentId = $transactionPayment->id;
            }

            // Transaction Payment - Update
            if (!empty($transactionPaymentCheck))
            {
                $transactionPayment = TransactionPayment::where('user_id', $userId)
                    ->where('order_id', $orderId)
                    ->update([
                        'status_code' => $result->status_code,
                        'status_message' => $result->status_message,
                        'transaction_id' => $result->transaction_id,
                        'gross_amount' => $result->gross_amount,
                        'payment_type' => $result->payment_type,
                        'transaction_time' => $result->transaction_time,
                        'transaction_status' => $result->transaction_status,
                        'result' => $resultContent
                ]);

                $transactionPaymentId = $transactionPaymentCheck->id;
            }

            // START | IF Transaction == Balance Deposit
            if ($orderType == config('app.point_code'))
            {
                // Update
                $pointTopupUpdate = PointTopup::where('transaction_id', $orderId)
                    ->where('user_id', $userId)
                    ->update([
                        'payment_id' => $transactionPaymentId
                ]);

                // Transaction Update Status
                if (!empty($transactionSuccess)) {
                    $pointTopupStatusCheck = PointTopup::where('transaction_id', $orderId)
                        ->where('user_id', $userId)
                        ->where('status', 1)
                        ->first();

                    if (empty($pointTopupStatusCheck)) {
                        $pointTopupStatus = PointTopup::where('transaction_id', $orderId)
                            ->where('user_id', $userId)
                            ->update(['status' => 1]);
                    }
                }

                // Transaction Update Status
                if (!empty($transactionCancel)) {
                    $pointTopupStatusCheck = PointTopup::where('transaction_id', $orderId)
                        ->where('user_id', $userId)
                        ->where('status', 1)
                        ->first();

                    if (empty($pointTopupStatusCheck)) {
                        $pointTopupStatus = PointTopup::where('transaction_id', $orderId)
                            ->where('user_id', $userId)
                            ->update(['status' => 7]);
                    }
                }
            }
        }
        // END - Transaction Status Check
        
        // Transaction Payment View
        return view($transactionView)->with([
            'pageTitle' => $transactionTitle,
            'transactionCode' => $order_id,
            'transactionTotal' => $gross_amount,
            'transactionAttachment' => $pdf_url,
            'transactionMessage' => $transactionMessage
        ]);
    }

    public function voucher(Request $request)
    {
        // Initialization
        $transactionAccess = false;
        $pageTitle = 'Detail Transaksi';
        $voucherId = $request->id;
        $userId = Auth::user()->id;
        $sellerId = null;

        if (empty($voucherId)) {
        	return redirect('/');
        }

        $voucherTransaction = VoucherTransaction::where('id', $voucherId)
            ->first();

        if (empty($voucherTransaction)) {
            return redirect('/');
        }
        
        // Transaction Product Authorization Check
        if (!empty($voucherTransaction->product)) {
            $sellerId = $voucherTransaction->product->user_id;
        }
        $buyerId = $voucherTransaction->user_id;

        if ($sellerId == Auth::user()->id || $buyerId == Auth::user()->id) {
            $transactionAccess = true;
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
            return redirect('/');
        }
        
        if ($buyerId == Auth::user()->id) {
            $transactionAccess = 2;
        }

        if ($sellerId == Auth::user()->id) {
            $transactionAccess = 1;
        }

        $userId = $buyerId;
    
        // START - Transaction Status Check
        $midtrans = new Veritrans;

        $notif = $midtrans->status($voucherTransaction->transaction_id);
        $json_result = json_encode($notif);

        // Transaction Payment Status Validation
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = '';
        $finish_redirect_url = '';
        $gross_amount = '';
        $pdf_url = '';
        $transactionSuccess = null;
        $transactionCancel = null;

        if (!empty($notif->pdf_url)) {
            $pdf_url = $notif->pdf_url;
        }

        if (!empty($notif->gross_amount)) {
            $gross_amount = $notif->gross_amount;
        }

        if (!empty($notif->fraud_status)) {
            $fraud = $notif->fraud_status;
        }

        if (!empty($notif->finish_redirect_url)) {
            $finish_redirect_url = $notif->finish_redirect_url;
        }

        // Transaction Payment Status
        if ($transaction == 'capture')
        {
            if ($type == 'credit_card')
            {
                if ($fraud == 'challenge')
                {
                    // Menunggu - Approve Admin
                    $transactionTitle = "Menunggu Approve Admin";
                    $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> melalui <b>" . $type . "</b> menunggu konfirmasi dari admin (challenged by FDS)";
                    $transactionView = 'transaction.validation';
                }
                else
                {
                    // Transaksi Sukses
                    $transactionTitle = "Transaksi Sukses";
                    $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> telah berhasil menggunakan <b>" . $type . "</b>";
                    $transactionView = 'transaction.success';

                    $transactionSuccess = $order_id;
                }
            }

        }
        else if ($transaction == 'settlement')
        {
            // Transaksi Sukses
            $transactionTitle = "Transaksi Sukses";
            $transactionMessage = "Transaksi dengan Kode: <b>" . $order_id . "</b> telah berhasil menggunakan <b>" . $type . "</b>";
            $transactionView = 'transaction.success';

            $transactionSuccess = $order_id;

        }
        else if($transaction == 'pending')
        {
            // Menunggu - Pengguna Menyelesaikan Transaksi
            $transactionTitle = "Transaksi Pending";
            $transactionMessage = "Menunggu anda untuk menyelesaikan Transaksi dengan Kode: <b>" . $order_id . "</b> menggunakan <b>" . $type . "</b>";
            $transactionView = 'transaction.pending';

            if (!empty($result->pdf_url)) {
                $pdf_url = $result->pdf_url;
            }
        }
        else if ($transaction == 'deny' || $transaction == 'cancel' || $transaction == 'expire')
        {
            // Transaksi Ditolak
            $transactionTitle = "Transaksi Ditolak";
            $transactionMessage = "Pembayaran melalui " . $type . " pada Transaksi dengan Kode: <b>" . $order_id . "</b> di Tolak.";
            $transactionView = 'transaction.error';
            
            $transactionCancel = $order_id;
        }

        if (!empty($notif))
        {
            $result = $notif;
            $statusCode = $result->status_code;
            $statusMessage = $result->status_message;
            $transactionId = $result->transaction_id;
            $orderId = $result->order_id;
            $resultContent = $json_result;
            $orderType = substr($orderId, 0, 4);

            // Transaction Payment - Check Availability
            $transactionPaymentCheck = TransactionPayment::where('user_id', $userId)
                ->where('order_id', $orderId)
                ->first();

            // Transaction Payment - Create
            if (empty($transactionPaymentCheck))
            {
                // Transaction Payment History
                $transactionPaymentHistory = new TransactionPaymentHistory;

                $transactionPaymentHistory->user_id = $userId;
                $transactionPaymentHistory->status_code = $result->status_code;
                $transactionPaymentHistory->status_message = $result->status_message;
                $transactionPaymentHistory->transaction_id = $result->transaction_id;
                $transactionPaymentHistory->order_id = $orderId;
                $transactionPaymentHistory->gross_amount = $result->gross_amount;

                $transactionPaymentHistory->payment_type = $result->payment_type;
                $transactionPaymentHistory->transaction_time = $result->transaction_time;
                $transactionPaymentHistory->transaction_status = $result->transaction_status;

                $transactionPaymentHistory->fraud_status = $fraud;
                $transactionPaymentHistory->finish_redirect_url = $finish_redirect_url;
                $transactionPaymentHistory->result = $resultContent;
                $transactionPaymentHistory->save();

                // Transaction Payment
                $transactionPayment = new TransactionPayment;

                $transactionPayment->user_id = $userId;
                $transactionPayment->status_code = $result->status_code;
                $transactionPayment->status_message = $result->status_message;
                $transactionPayment->transaction_id = $result->transaction_id;
                $transactionPayment->order_id = $orderId;
                $transactionPayment->gross_amount = $result->gross_amount;

                $transactionPayment->payment_type = $result->payment_type;
                $transactionPayment->transaction_time = $result->transaction_time;
                $transactionPayment->transaction_status = $result->transaction_status;

                $transactionPayment->fraud_status = $fraud;
                $transactionPayment->finish_redirect_url = $finish_redirect_url;
                $transactionPayment->result = $resultContent;
                $transactionPayment->save();

			    // Transaction Payment ID
                $transactionPaymentId = $transactionPayment->id;
            }

            // Transaction Payment - Update
            if (!empty($transactionPaymentCheck))
            {
                $transactionPayment = TransactionPayment::where('user_id', $userId)
                    ->where('order_id', $orderId)
                    ->update([
                        'status_code' => $result->status_code,
                        'status_message' => $result->status_message,
                        'transaction_id' => $result->transaction_id,
                        'gross_amount' => $result->gross_amount,
                        'payment_type' => $result->payment_type,
                        'transaction_time' => $result->transaction_time,
                        'transaction_status' => $result->transaction_status,
                        'result' => $resultContent
                ]);

                $transactionPaymentId = $transactionPaymentCheck->id;
            }

            // START | IF Transaction == Voucher Transaction
        
            // Update
            $voucherUpdate = VoucherTransaction::where('transaction_id', $orderId)
                ->where('user_id', $userId)
                ->update([
                    'payment_id' => $transactionPaymentId
            ]);

            // Check
            $voucherCheck = VoucherTransaction::where('transaction_id', $orderId)
                ->where('user_id', $userId)
                ->first();

            $voucherId = $voucherCheck->id;

            // Transaction Update Status
            if (!empty($transactionSuccess)) {
                $voucherStatusCheck = VoucherTransaction::where('transaction_id', $orderId)
                    ->where('user_id', $userId)
                    ->where('status', 1)
                    ->first();

                if (empty($voucherStatusCheck)) {
                    // Sold
                    if ($voucherCheck->status != 1) {
                        if (!empty($voucherCheck->product_id)) {
                            $productId = $voucherCheck->product_id;
                            
                            // Product Check
                            $product = Product::where('id', $productId)
                                ->first();
                                    
                            if (!empty($product)) {
                                $productSold = ($product->sold + $voucherCheck->unit);

                                $productUpdate = Product::where('id', $productId)
                                    ->update([
                                        'sold' => $productSold
                                ]);
                            }

                            // Balance Transaction Create Plus
                            $buyerId = $voucherCheck->user_id;
                            $sellerId = $product->user_id;
                            
                            $balanceNew = new Balance;
                            $balanceNew->user_id = $sellerId;
                            $balanceNew->voucher_id = $voucherId;
                            $balanceNew->notes = 'Pembelian E-Voucher';
                            $balanceNew->save();
                        }
                    }
                    
                    // Status
                    $voucherStatus = VoucherTransaction::where('transaction_id', $orderId)
                        ->where('user_id', Auth::user()->id)
                        ->update(['status' => 1]);
                }
            }

            // Transaction Update Status
            if (!empty($transactionCancel)) {
                $voucherStatusCheck = VoucherTransaction::where('transaction_id', $orderId)
                    ->where('user_id', $userId)
                    ->where('status', 1)
                    ->first();

                if (empty($voucherStatusCheck)) { 
                    // Stock
                    if ($voucherCheck->status != 7) {
                        if (!empty($voucherCheck->product_id)) {
                            $productId = $voucherCheck->product_id;
                            
                            // Product Check
                            $product = Product::where('id', $productId)
                                ->first();
                                    
                            if (!empty($product)) {
                                $productStock = ($product->stock + $voucherCheck->unit);

                                $productUpdate = Product::where('id', $productId)
                                    ->update([
                                        'stock' => $productStock
                                ]);
                            }
                        }
                    }

                    // Status
                    $voucherStatus = VoucherTransaction::where('transaction_id', $orderId)
                        ->where('user_id', $userId)
                        ->update(['status' => 7]);
                }
            }
        }

        $transactionPaymentHistory = TransactionPaymentHistory::where('user_id', $userId)
            ->where('order_id', $orderId)
            ->where('finish_redirect_url', '!=', '')
            ->orderBy('id', 'ASC')
            ->first();

        if (!empty($transactionPaymentHistory)) {
            $paymentResult = json_decode($transactionPaymentHistory->result);

            if (!empty($paymentResult->pdf_url)) {
                $pdf_url = $paymentResult->pdf_url;
            }
        }
        // END - Transaction Status Check

        // Check
        $voucherTransaction = VoucherTransaction::where('transaction_id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if ($voucherTransaction->status == 1) {
            // Initialization
            $pageTitle = 'Transaksi #'.$voucherTransaction->transaction_id;

            // Voucher
            $vouchers = array();
            for ($v = 1; $v <= $voucherTransaction->unit; $v++) {
                // Initialization
                $timestamp = '-';
                $status = '<div class="notes bg-success d-inline-block">Belum di Klaim</div>';

                // Code
                $code = $voucherTransaction->transaction_id.''.$v;
                $code = abs(crc32(hexdec($code)));

                // Check
                $claim = VoucherClaim::where('code', $code)
                    ->where('transaction_id', $voucherTransaction->id)
                    ->first();

                if (!empty($claim)) {
                    $status = '<div class="notes bg-secondary d-inline-block">Sudah di Klaim</div>';
                    $timestamp = $claim->created_at->format('Y-m-d H:i:s');
                }

                if (empty($claim)) {
                    if ($transactionAccess == 1) {
                        $code = '********'.substr($code, -2);
                    }
                }

                // Response
                $vouchers[] = array(
                    'code' => $code,
                    'status' => $status,
                    'timestamp' => $timestamp,
                );
            }

            $vouchers =  json_encode($vouchers, JSON_FORCE_OBJECT);
            $vouchers =  json_decode($vouchers);

            // Transaction Payment View
            return view('tracking.tracking-voucher')->with([
                'pageTitle' => $pageTitle,
                'transaction' => $voucherTransaction,
                'vouchers' => $vouchers,
                'access' => $transactionAccess,
                'status' => $voucherTransaction->status,
            ]);
        }
        
        // Transaction Payment View
        return view($transactionView)->with([
            'pageTitle' => $transactionTitle,
            'transactionCode' => $order_id,
            'transactionTotal' => $gross_amount,
            'transactionAttachment' => $pdf_url,
            'transactionMessage' => $transactionMessage
        ]);
    }

    public function claimVoucher(Request $request)
    {
        // Initialization
        $transactionAccess = false;
        $transactionId = $request->transaction;
        $code = $request->code;

        // Transaction Voucher Check
        $voucherTransaction = VoucherTransaction::where('id', $transactionId)
            ->first();

        if (empty($voucherTransaction)) {
            return redirect('/');
        }

        // Transaction Product Authorization Check
        if (!empty($voucherTransaction)) {
            $sellerId = $voucherTransaction->product->user_id;
            $buyerId = $voucherTransaction->user_id;

            if ($sellerId == Auth::user()->id) {
                $transactionAccess = true;
            }
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
        	return redirect('/');
        }

        DB::beginTransaction();

        // Expired
        if (date('Y-m-d H:i:s') > $voucherTransaction->voucher_expired) {
            // Return Redirect
            return redirect()
                ->route('voucher.transaction', ['id' => $transactionId])
                ->with('warning', 'Maaf, E-Voucher Telah Kadaluarsa');
        }

        // Check
        $claim = VoucherClaim::where('code', $code)
            ->where('transaction_id', $transactionId)
            ->first();

        if (!empty($claim)) {
            // Return Redirect
            return redirect()
                ->route('voucher.transaction', ['id' => $transactionId])
                ->with('warning', 'Maaf, Kode E-Voucher sudah pernah di Klaim sebelumnya');
        }

        $transactionAccess = false;

        for ($v = 1; $v <= $voucherTransaction->unit; $v++) {
            // Code
            $codeCheck = $voucherTransaction->transaction_id.''.$v;
            $codeCheck = abs(crc32(hexdec($codeCheck)));

            if ($code == $codeCheck) {
                $transactionAccess = true;
            }
        }

        if (empty($transactionAccess)) {
            // Return Redirect
            return redirect()
                ->route('voucher.transaction', ['id' => $transactionId])
                ->with('warning', 'Maaf, Kode E-Voucher yang anda masukkan Tidak Ditemukan');
        }

        // Claim Insert
        $insert = new VoucherClaim;
        $insert->user_id = Auth::user()->id;
        $insert->transaction_id = $transactionId;
        $insert->code = $code;
        $insert->save();

        DB::commit();

        // Return Redirect
        return redirect()
            ->route('voucher.transaction', ['id' => $transactionId])
            ->with('status', 'Selamat!! Klaim E-Voucher telah berhasil');
    }

    public function approveTransaction(Request $request)
    {
        // Initialization
        $transactionAccess = false;
        $transactionProductId = $request->transaction;
        $service = $request->service;
        $code = $request->code;

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->where('status', 1)
            ->first();

        // Transaction Product Authorization Check
        if (!empty($transactionProduct)) {
            $sellerId = $transactionProduct->user_id;
            $buyerId = $transactionProduct->transaction->user_id;

            if ($sellerId == Auth::user()->id) {
                $transactionAccess = true;
            }
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
        	return redirect('/');
        }

        DB::beginTransaction();

        // Transaction Point
        $transactionProductPoint = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('status', 1)
            ->sum('point');
        
        if ($transactionProductPoint > 0)
        {
            $operation = 'deduct_mspoint';

            $username = $transactionProduct->transaction->user->username;
            $point = $transactionProductPoint;

            // Point Minus
            $response = new MsplifeController;
            $response = $response->deduct_mspoint($operation, $username, $point);
        }

        // Transaction Status
        $transactionShipping = TransactionShipping::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', Auth::user()->id)
            ->update([
                'service' => $service,
                'code' => $code
        ]);

        $transactionStatus= TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', Auth::user()->id)
            ->update([
                'status' => 2
        ]);

        DB::commit();

        // Return Redirect
        return redirect()
            ->route('transaction.detail', ['id' => $transactionProductId])
            ->with('status', 'Selamat!! Konfirmasi Pengiriman Produk telah berhasil');
    }

    public function confirmTransaction(Request $request)
    {
        // Initialization
        $transactionAccess = false;
        $transactionProductId = $request->transaction;
        $rating = $request->rating;
        $review = $request->review;

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->where('status', 2)
            ->first();

        // Transaction Product Authorization Check
        if (!empty($transactionProduct)) {
            $sellerId = $transactionProduct->user_id;
            $buyerId = $transactionProduct->transaction->user_id;

            if ($buyerId == Auth::user()->id) {
                $transactionAccess = true;
            }
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
        	return redirect('/');
        }

        DB::beginTransaction();

        $transactionReviewCheck = TransactionReview::where('transaction_id', $transactionProduct->transaction_id)
            ->where('buyer_id', $buyerId)
            ->first();

        if (empty($transactionReviewCheck)) {
            $transactionReview = new TransactionReview;
            $transactionReview->transaction_id = $transactionProduct->transaction_id;
            $transactionReview->buyer_id = $buyerId;
            $transactionReview->rating = $rating;
            $transactionReview->review = $review;
            $transactionReview->save();
        }

        $transactionStatus= TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->update([
                'status' => 4
        ]);

        DB::commit();

        return redirect()
            ->route('transaction.detail', ['id' => $transactionProductId])
            ->with('status', 'Terimakasih telah memberikan Rating dan Ulasan!!');;
    }

    public function completeTransaction(Request $request) {
        $transactionAccess = false;
        $transactionProductId = $request->transaction;
        $totalCount = 0;
        $rating = $request->rating;
        $review = $request->review;

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->where('status', 4)
            ->first();

        // Transaction Product Authorization Check
        if (!empty($transactionProduct)) {
            $sellerId = $transactionProduct->user_id;
            $buyerId = $transactionProduct->transaction->user_id;

            if ($sellerId == Auth::user()->id) {
                $transactionAccess = true;
            }
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
        	return redirect('/');
        }

        DB::beginTransaction();

        // Transaction Review Check
        $transactionReviewCheck = TransactionReview::where('transaction_id', $transactionProduct->transaction_id)
            ->where('seller_id', $sellerId)
            ->first();

        // Transaction Review Create
        if (empty($transactionReviewCheck)) {
            $transactionReview = new TransactionReview;
            $transactionReview->transaction_id = $transactionProduct->transaction_id;
            $transactionReview->seller_id = $sellerId;
            $transactionReview->rating = $rating;
            $transactionReview->review = $review;
            $transactionReview->save();
        }

        // Transaction Status Check Complete
        if ($transactionProduct->status != 5)
        {
            // Transaction Product List
            $transactionProductList = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->get();

            // Transaction Product Update Sold Stock
            foreach ($transactionProductList as $transaction)
            {
                $totalCount += ($transaction->price * $transaction->unit);
                $reviewCount = 1;
                $ratingCount = $rating;
        
                $transactionProductComplete = TransactionProduct::where('product_id', $transaction->product_id)
                    ->where('status', 5)
                    ->get();
                
                foreach ($transactionProductComplete as $productReview)
                {
                    foreach ($productReview->review_buyer as $reviewBuyer)
                    {
                        $reviewCount += 1;
                        $ratingCount += $reviewBuyer->rating;
                    }
                }
                
                $ratingCount = floor($ratingCount / $reviewCount);

                if (!empty($transaction->product))
                {
                    $productStock = ($transaction->product->sold + $transaction->unit);
                    $productSold = Product::where('id', $transaction->product_id)
                        ->update([
                            'sold' => $productStock,
                            'review' => $reviewCount,
                            'rating' => $ratingCount,
                    ]);
                }

                if (!empty($transaction->point))
                {
                    $operation = 'update_mspoint';

                    $point_count = $transaction->point * $transaction->user->type->percent / 100;
                    $point = floor($point_count);

                    if ($point == 0 AND $point_count > 0)
                    {
                        $point = 1;
                    }
        
                    $username = $transaction->user->username;
        
                    // Point Plus
                    $response = new MsplifeController;
                    $response = $response->update_mspoint($operation, $username, $point);
                }
            }

            // Balance Transaction Create Plus
            $balanceTransaction = new BalanceTransaction;
            $balanceTransaction->transaction_id = $transactionProduct->transaction_id;
            $balanceTransaction->user_id = $sellerId;
            $balanceTransaction->seller_id = $sellerId;
            $balanceTransaction->status = 1;
            $balanceTransaction->save();

    		$balanceTransactionId = $balanceTransaction->id;

    		$balanceNew = new Balance;
    		$balanceNew->user_id = Auth::user()->id;
    		$balanceNew->transaction_id = $balanceTransactionId;
    		$balanceNew->notes = 'Penjualan Produk';
    		$balanceNew->save();

            // Coupon Price
            $coupon_price = Option::where('type', 'coupon-price')->first();
            $coupon_price = $coupon_price->content;
            
            // Transaction Coupon
            $couponTotal = floor($totalCount / $coupon_price);
    
            if ($couponTotal > 0)
            {
                for($c = 1; $c <= $couponTotal; $c++)
                {
                    // Coupon Insert
                    $insert = new Coupon;
                    $insert->user_id = $transactionProduct->transaction->user_id;
                    $insert->transaction_id = $transactionProduct->transaction_id;
                    $insert->number = $c;
                    $insert->total = $totalCount;
                    $insert->save();
                }
            }
    
            // Transaction Product Status Complete
            $transactionStatus= TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->update([
                    'status' => 5
            ]);
        }

        DB::commit();

        // Return Redirect
        return redirect()
            ->route('transaction.detail', ['id' => $transactionProductId])
            ->with('status', 'Transaksi Selesai!! Terimakasih telah memberikan Rating dan Ulasan');;
    }

    public function cancelTransaction(Request $request)
    {
        // Initialization
        $transactionAccess = false;
        $transactionProductId = $request->transaction;
        $cancel = $request->cancel;

        if (empty($request->cancel)) {
            $cancel = "Penjual Melebihi Batas Waktu Pengiriman";
        }

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->where('status', 1)
            ->first();

        // Transaction Product Availability Check
        if (empty($transactionProduct)) {
        	return redirect('/');
        }

        $sellerId = $transactionProduct->user_id;
        $buyerId = $transactionProduct->transaction->user_id;

        // Transaction Product Access Check
        if ($sellerId != Auth::user()->id) {
            return redirect('/');
        }

        DB::beginTransaction();

        // Transaction Product Cancel
        $transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->update(['cancel' => $cancel]);

        // Transaction Status Check Cancel
        if ($transactionProduct->status != 6) {
            // Transaction Product List
            $transactionProductList = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->get();

            // Transaction Product Update Stock
            foreach ($transactionProductList as $transaction) {
                if (!empty($transaction->product))
                {
                    $productStock = ($transaction->product->stock + $transaction->unit);
                    $productSold = Product::where('id', $transaction->product_id)
                        ->update([
                            'stock' => $productStock
                    ]);
                }
            }

            $transactionPayment = TransactionPayment::where('order_id', $transactionProduct->transaction_id)->first();
            if ($transactionPayment->gateway_id === 4) {
                try {
                    $data = [
                        'order_id'          => $transactionProduct->transaction_id,
                        'transaction_id'    => $transactionPayment->transaction_id,
                        'reason'            => $cancel,
                        'cancel_by'         => $transactionProduct->user->name,
                    ];

                    $post = $this->kredivo->cancelTransaction($data);
                    if ($post->status === 'ERROR') {
                        return redirect()->back()->with(['warning' => $post->message]);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                    return redirect()->back()->with(['warning' => 'Oopss!.. Something when wrong. Please contact the Administrator']);
                }
            } else {
                // Balance Transaction Create Plus
                $balanceTransaction = new BalanceTransaction;
                $balanceTransaction->transaction_id = $transactionProduct->transaction_id;
                $balanceTransaction->user_id = $buyerId;
                $balanceTransaction->seller_id = $sellerId;
                $balanceTransaction->status = 1;
                $balanceTransaction->save();
    
                $balanceTransactionId = $balanceTransaction->id;
    
                $balanceNew = new Balance;
                $balanceNew->user_id = $buyerId;
                $balanceNew->transaction_id = $balanceTransactionId;
                $balanceNew->notes = 'Pembatalan Pembelian Produk';
                $balanceNew->save();
            }
        }

        // Transaction Product Status Cancel
        $transactionStatus= TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->update([
                'status' => 6
        ]);

        DB::commit();

        return redirect()
            ->route('transaction.detail', ['id' => $transactionProductId])
            ->with('status', 'Transaksi telah Berhasil di Batalkan!!');
    }

    public function confirmPayment($transaction, $rating, $review)
    {
        // Initialization
        $transactionAccess = false;
        $transactionProductId = $transaction;

        // Validation
        if (empty($rating)) {
            $rating = 5;
        }

        if (empty($review)) {
            $review = 'Terimakasih';
        }

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->where('status', 2)
            ->first();

        // Transaction Product Authorization Check
        if (!empty($transactionProduct)) {
            $sellerId = $transactionProduct->user_id;
            $buyerId = $transactionProduct->transaction->user_id;

            if ($buyerId == Auth::user()->id) {
                $transactionAccess = true;
            }
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
        	return false;
        }

        DB::beginTransaction();

        $transactionReviewCheck = TransactionReview::where('transaction_id', $transactionProduct->transaction_id)
            ->where('buyer_id', $buyerId)
            ->first();

        if (empty($transactionReviewCheck)) {
            $transactionReview = new TransactionReview;
            $transactionReview->transaction_id = $transactionProduct->transaction_id;
            $transactionReview->buyer_id = $buyerId;
            $transactionReview->rating = $rating;
            $transactionReview->review = $review;
            $transactionReview->save();
        }

        $transactionStatus= TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->update([
                'status' => 4
        ]);

        DB::commit();
    }
    public function completePayment($transaction, $rating, $review)
    {
        // Initialization
        $transactionAccess = false;
        $transactionProductId = $transaction;
        $totalCount = 0;

        // Validation
        if (empty($rating)) {
            $rating = 5;
        }

        if (empty($review)) {
            $review = 'Terimakasih';
        }

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->where('status', 4)
            ->first();

        // Transaction Product Authorization Check
        if (!empty($transactionProduct)) {
            $sellerId = $transactionProduct->user_id;
            $buyerId = $transactionProduct->transaction->user_id;

            if ($sellerId == Auth::user()->id) {
                $transactionAccess = true;
            }
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
        	return false;
        }

        DB::beginTransaction();

        // Transaction Review Check
        $transactionReviewCheck = TransactionReview::where('transaction_id', $transactionProduct->transaction_id)
            ->where('seller_id', $sellerId)
            ->first();

        // Transaction Review Create
        if (empty($transactionReviewCheck)) {
            $transactionReview = new TransactionReview;
            $transactionReview->transaction_id = $transactionProduct->transaction_id;
            $transactionReview->seller_id = $sellerId;
            $transactionReview->rating = $rating;
            $transactionReview->review = $review;
            $transactionReview->save();
        }

        // Transaction Status Check Complete
        if ($transactionProduct->status != 5)
        {
            // Transaction Product List
            $transactionProductList = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->get();

            // Transaction Product Update Sold Stock
            foreach ($transactionProductList as $transaction)
            {
                $totalCount += ($transaction->price * $transaction->unit);
                $reviewCount = 1;
                $ratingCount = $rating;
        
                $transactionProductComplete = TransactionProduct::where('product_id', $transaction->product_id)
                    ->where('status', 5)
                    ->get();
                
                foreach ($transactionProductComplete as $productReview)
                {
                    foreach ($productReview->review_buyer as $reviewBuyer)
                    {
                        $reviewCount += 1;
                        $ratingCount += $reviewBuyer->rating;
                    }
                }
                
                $ratingCount = floor($ratingCount / $reviewCount);

                if (!empty($transaction->product))
                {
                    $productStock = ($transaction->product->sold + $transaction->unit);
                    $productSold = Product::where('id', $transaction->product_id)
                        ->update([
                            'sold' => $productStock,
                            'review' => $reviewCount,
                            'rating' => $ratingCount,
                    ]);
                }

                if (!empty($transaction->point))
                {
                    $operation = 'update_mspoint';

                    $point_count = $transaction->point * $transaction->user->type->percent / 100;
                    $point = floor($point_count);

                    if ($point == 0 AND $point_count > 0)
                    {
                        $point = 1;
                    }
        
                    $username = $transaction->user->username;
        
                    // Point Plus
                    $response = new MsplifeController;
                    $response = $response->update_mspoint($operation, $username, $point);
                }
            }

            // Balance Transaction Create Plus
            $balanceTransaction = new BalanceTransaction;
            $balanceTransaction->transaction_id = $transactionProduct->transaction_id;
            $balanceTransaction->user_id = $sellerId;
            $balanceTransaction->seller_id = $sellerId;
            $balanceTransaction->status = 1;
            $balanceTransaction->save();

    		$balanceTransactionId = $balanceTransaction->id;

    		$balanceNew = new Balance;
    		$balanceNew->user_id = Auth::user()->id;
    		$balanceNew->transaction_id = $balanceTransactionId;
    		$balanceNew->notes = 'Penjualan Produk';
    		$balanceNew->save();

            // Coupon Price
            $coupon_price = Option::where('type', 'coupon-price')->first();
            $coupon_price = $coupon_price->content;
            
            // Transaction Coupon
            $couponTotal = floor($totalCount / $coupon_price);
    
            if ($couponTotal > 0)
            {
                for($c = 1; $c <= $couponTotal; $c++)
                {
                    // Coupon Insert
                    $insert = new Coupon;
                    $insert->user_id = $transactionProduct->transaction->user_id;
                    $insert->transaction_id = $transactionProduct->transaction_id;
                    $insert->number = $c;
                    $insert->total = $totalCount;
                    $insert->save();
                }
            }
    
            // Transaction Product Status Complete
            $transactionStatus= TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->update([
                    'status' => 5
            ]);
        }

        DB::commit();
    }
    public function refundPayment($transaction, $cancel)
    {
        // Initialization
        $transactionProductId = $transaction;

        // Cancel Reason
        switch ($cancel) {
            case 2:
                $cancel = "Produk Group Buy Tidak Memenuhi Target";
                break;
            
            default:
                $cancel = "Penjual Melebihi Batas Waktu Pengiriman";
                break;
        }

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->where('status', 1)
            ->first();

        // Transaction Product Authorization Check
        if (empty($transactionProduct)) {
            return false;
        }

        $sellerId = $transactionProduct->user_id;
        $buyerId = $transactionProduct->transaction->user_id;

        DB::beginTransaction();

        // Transaction Product Cancel
        $transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->update(['cancel' => $cancel]);

        // Transaction Status Check Cancel
        if ($transactionProduct->status != 6) {
            // Transaction Product List
            $transactionProductList = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->get();

            // Transaction Product Update Stock
            foreach ($transactionProductList as $transaction) {
                if (!empty($transaction->product))
                {
                    $productStock = ($transaction->product->stock + $transaction->unit);
                    $productSold = Product::where('id', $transaction->product_id)
                        ->update([
                            'stock' => $productStock
                    ]);
                }
            }

            // Balance Transaction Create Plus
            $balanceTransaction = new BalanceTransaction;
            $balanceTransaction->transaction_id = $transactionProduct->transaction_id;
            $balanceTransaction->user_id = $buyerId;
            $balanceTransaction->seller_id = $sellerId;
            $balanceTransaction->status = 1;
            $balanceTransaction->save();

    		$balanceTransactionId = $balanceTransaction->id;

    		$balanceNew = new Balance;
    		$balanceNew->user_id = $buyerId;
    		$balanceNew->transaction_id = $balanceTransactionId;
    		$balanceNew->notes = 'Pembatalan Pembelian Produk';
    		$balanceNew->save();
        }

        // Transaction Product Status Cancel
        $transactionStatus= TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->update([
                'status' => 6
        ]);

        DB::commit();
    }

    public function cronTransaction(Request $request)
    {
        // Inser Log
        $insert = new CronLog;
        $insert->type = 'transaction';
        $insert->user_ip = $request->ip();
        $insert->user_agent = $request->server('HTTP_USER_AGENT');
        $insert->save();

        // Transaction Product Check
        $transactionProducts = TransactionProduct::whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->groupBy('transaction_id')
            ->get();

        foreach ($transactionProducts as $transactionProduct)
        {
            DB::beginTransaction();
            
            $transactionProductId = $transactionProduct->id;

            // Transaction Approval Expired (2 Days)
            if ($transactionProduct->status == 1) {

                // Preorder
                if (!empty($transactionProduct->product->preorder_target)) {
                    if (Carbon::now() >= $transactionProduct->product->preorder_expired) {
                        $preorderCount = TransactionProduct::where('product_id', $transactionProduct->product_id)
                            ->where('status', 1)
                            ->sum('unit');
                        
                        if ($preorderCount < $transactionProduct->product->preorder_target) {
                            // Transaction Refund Payment
                            $this->refundPayment($transactionProductId, 2);
                        }
                    }
                }

                // Non Preorder
                if (empty($transactionProduct->product->preorder_target))
                {
                    if (Carbon::now() >= $transactionProduct->updated_at->addDays(2)) 
                    {
                        // Transaction Refund Payment
                        $this->refundPayment($transactionProductId, 1);

                        // Transaction Product Recheck
                        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
                            ->whereHas('transaction', function($q) {
                                $q->whereNotNull('address_id')
                                ->whereNotNull('payment_id');
                            })
                            ->first();
                    }
                }
            }

            DB::commit();
        }
    }

/*
    public function cronPreorder()
    {
        // Initialization
        $transactionAccess = false;
        $transactionProductId = $request->transaction;
        $cancel = $request->cancel;

        if (empty($request->cancel)) {
            $cancel = "Penjual Melebihi Batas Waktu Pengiriman";
        }

        // Transaction Product Check
        $transactionProduct = TransactionProduct::where('id', $transactionProductId)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->first();

        // Transaction Product Authorization Check
        if (!empty($transactionProduct)) {
            $sellerId = $transactionProduct->user_id;
            $buyerId = $transactionProduct->transaction->user_id;

            if ($sellerId == Auth::user()->id || $buyerId == Auth::user()->id) {
                $transactionAccess = true;
            }
        }

        // Transaction Product Access Check
        if (empty($transactionAccess)) {
        	return redirect('/');
        }

        DB::beginTransaction();

        // Transaction Product Cancel
        $transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->update(['cancel' => $cancel]);

        // Transaction Status Check Cancel
        if ($transactionProduct->status != 6) {
            // Transaction Product List
            $transactionProductList = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->get();

            // Transaction Product Update Stock
            foreach ($transactionProductList as $transaction) {
                if (!empty($transaction->product))
                {
                    $productStock = ($transaction->product->stock + $transaction->unit);
                    $productSold = Product::where('id', $transaction->product_id)
                        ->update([
                            'stock' => $productStock
                    ]);
                }
            }

            // Balance Transaction Create Plus
            $balanceTransaction = new BalanceTransaction;
            $balanceTransaction->transaction_id = $transactionProduct->transaction_id;
            $balanceTransaction->user_id = $buyerId;
            $balanceTransaction->seller_id = $sellerId;
            $balanceTransaction->status = 1;
            $balanceTransaction->save();

    		$balanceTransactionId = $balanceTransaction->id;

    		$balanceNew = new Balance;
    		$balanceNew->user_id = $buyerId;
    		$balanceNew->transaction_id = $balanceTransactionId;
    		$balanceNew->notes = 'Pembatalan Pembelian Produk';
    		$balanceNew->save();
        }

        // Transaction Product Status Cancel
        $transactionStatus= TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
            ->where('user_id', $sellerId)
            ->update([
                'status' => 6
        ]);

        DB::commit();

        return redirect()
            ->route('transaction.detail', ['id' => $transactionProductId])
            ->with('status', 'Transaksi telah Berhasil di Batalkan!!');
    }
*/
}
