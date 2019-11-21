<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Marketplace\Http\Requests;
use Marketplace\Http\Controllers\Controller;

use Marketplace\Jobs\SendTransactionEmail;

use Marketplace\Product;
use Marketplace\Transaction;
use Marketplace\TransactionProduct;
use Marketplace\TransactionShipping;
use Marketplace\TransactionPromo;
use Marketplace\TransactionAddress;
use Marketplace\TransactionPayment;
use Marketplace\TransactionPaymentHistory;
use Marketplace\Balance;
use Marketplace\BalanceDeposit;
use Marketplace\BalanceTransaction;
use Marketplace\VoucherTransaction;
use Marketplace\PointProduct;
use Marketplace\PointTopup;

use Auth;

use Marketplace\Veritrans\Midtrans;
use Marketplace\Veritrans\Veritrans;

class SnapController extends Controller
{
    public function __construct()
    {
        Midtrans::$serverKey = env('MIDTRANS_SERVER_KEY');
        Veritrans::$serverKey = env('MIDTRANS_SERVER_KEY');
        
        Midtrans::$isProduction = env('MIDTRANS_PRODUCTION');
        Veritrans::$isProduction = env('MIDTRANS_PRODUCTION');
    }

    public function token(Request $request)
    {  
        // SNAP
        $midtrans = new Midtrans;

        // VT Web
        // $vt = new Veritrans;

        // Initialization
        $orderType = null;
        $transactionId = null;
        $transactionBalance = null;
        $transactionPoint = null;
        $transactionVoucher = null;

		// Transaction Token
        DB::beginTransaction();
        
        // Transaction
        if (!empty($request->transaction)) {
            $transactionId = $request->transaction;
        }

        // Balance
        if (!empty($request->balance)) {
            if ($request->balance < 10000) {
                return '';
            }
            $transactionBalance = $request->balance;

            $orderType = config('app.balance_code');
        }

        // Point
        if (!empty($request->point)) {
            if ($request->point < 1) {
                return '';
            }
            $transactionPoint = $request->point;

            $orderType = config('app.point_code');
        }

        // Voucher
        if (!empty($request->voucher)) {
            // Voucher
            $transactionVoucher = $request->voucher;

            // Product Check
            $product = Product::where('id', $transactionVoucher)
                ->first();
                
            if (empty($product)) {
                return '';
            }

            // Unit Validation
            $unit = $request->unit;
            
            if ($request->unit < 1) {
                return '';
            }
            if ($request->unit > $product->stock) {
                return '';
            }

            // Type
            $orderType = config('app.voucher_code');
        }

        // Order Type
        switch ($orderType) {
            // Transaction Voucher
            case config('app.voucher_code'):
                // Initialization
                $transactionId = config('app.voucher_code').abs(crc32(hexdec(uniqid())));

                // Delete
                $voucherCheck = VoucherTransaction::where('user_id', Auth::user()->id)
                    ->where('payment_id', null)
                    ->where('status', 0)
                    ->delete();
                
                // Price
                $price = $product->price * $unit;

                // Insert
                $insert = new VoucherTransaction;
                $insert->user_id = Auth::user()->id;
                $insert->payment_id = null;
                $insert->transaction_id = $transactionId;
                $insert->product_id = $transactionVoucher;
                $insert->name = $product->name;
                $insert->unit = $unit;
                $insert->price = $price;
                $insert->voucher_expired = $product->voucher_expired;
                $insert->save();

                // Transaction
                $transaction_details = array(
                    'order_id'      => $transactionId,
                    'gross_amount'  => $price
                );

                $items = [
                    array(
                        'id'        => config('app.voucher_code').$product->id,
                        'price'     => $product->price,
                        'quantity'  => $unit,
                        'name'      => str_limit($product->name, 40)
                    )
                ];

                $billing_address = null;
                $shipping_address = null;

                // Populate customer's Info
                $customer_details = array(
                    'first_name'            => Auth::user()->name,
                    'last_name'             => '@'.Auth::user()->username,
                    'email'                 => Auth::user()->email,
                    'phone'                 => Auth::user()->phone
                );
                break;

            // Transaction Point
            case config('app.point_code'):
                // Initialization
                $transactionId = config('app.point_code').abs(crc32(hexdec(uniqid())));

                // Point
                $point = PointProduct::where('id', $transactionPoint)
                    ->first();

                if (empty($point)) {
                    return '';
                }

                // Delete
                $pointTopupCheck = PointTopup::where('user_id', Auth::user()->id)
                    ->where('payment_id', null)
                    ->where('status', 0)
                    ->delete();

                // Insert
                $insert = new PointTopup;
                $insert->user_id = Auth::user()->id;
                $insert->payment_id = null;
                $insert->transaction_id = $transactionId;
                $insert->product_id = $transactionPoint;
                $insert->name = $point->name;
                $insert->price = $point->price;
                $insert->point = $point->point;
                $insert->save();

                // Transaction
                $transaction_details = array(
                    'order_id'      => $transactionId,
                    'gross_amount'  => $point->price
                );

                $items = [
                    array(
                        'id'        => config('app.point_code').$point->id,
                        'price'     => $point->price,
                        'quantity'  => 1,
                        'name'      => str_limit($point->name, 40)
                    )
                ];

                $billing_address = null;
                $shipping_address = null;

                // Populate customer's Info
                $customer_details = array(
                    'first_name'            => Auth::user()->name,
                    'last_name'             => '@'.Auth::user()->username,
                    'email'                 => Auth::user()->email,
                    'phone'                 => Auth::user()->phone
                );
                break;
        
            // Transaction Balance
            case config('app.balance_code'):
                // Initialization
                $transactionId = config('app.balance_code').abs(crc32(hexdec(uniqid())));

                $transaction_details = array(
                    'order_id'      => $transactionId,
                    'gross_amount'  => $transactionBalance
                );

                $items = [
                    array(
                        'id'        => config('app.balance_code').$transactionBalance,
                        'price'     => $transactionBalance,
                        'quantity'  => 1,
                        'name'      => str_limit('Saldo '.config('app.name'), 40)
                    )
                ];

                $billing_address = null;
                $shipping_address = null;

                // Populate customer's Info
                $customer_details = array(
                    'first_name'            => Auth::user()->name,
                    'last_name'             => '@'.Auth::user()->username,
                    'email'                 => Auth::user()->email,
                    'phone'                 => Auth::user()->phone
                );
                break;
            
            // Transaction Mall
            default:
                // Validation
                if (empty($request->transaction)) {
                    return '';
                }

                // Transaction Address
                $transactionAddress = TransactionAddress::where('transaction_id', $transactionId)
                    ->first();

                // Populate items
                $items = null;

                // Transaction Shipping - Total Price
                $transactionShippingPrice = 0;
                $transactionShippingList = TransactionShipping::where('transaction_id', $transactionId)
                    ->get();

                foreach ($transactionShippingList as $shippingList) {

                    $transactionShippingPrice += $shippingList->price;
                    $shippingDescription = str_replace(' - Rp '.number_format($shippingList->price,0,",","."), '', $shippingList->description);

                    $shippingName = $shippingDescription.' - dari ('.$shippingList->user->name.')';

                    $items[] = array('id' => 'MALLSHIPPING'.$shippingList->id, 'price' => $shippingList->price, 'quantity' => 1, 'name' => str_limit($shippingName, 40));

                }

                // Transaction Product - Total Price
                $transactionProductPrice = 0;
                $transactionProductPoint = 0;
                $transactionProductPointPrice = 0;
                $transactionProductList = TransactionProduct::where('transaction_id', $transactionId)
                    ->get();

                foreach ($transactionProductList as $productList) {
                    // Product
                    $items[] = array('id' => $productList->product_id, 'price' => $productList->price, 'quantity' => $productList->unit, 'name' => str_limit($productList->product->name, 40));

                    $transactionProductPrice += ($productList->price * $productList->unit);

                    // Point
                    if ($productList->point > 0)
                    {
                        $transactionProductPoint += $productList->point;
                        $transactionProductPointPrice += ($productList->point * $productList->point_price);
                        
                        $items[] = array('id' => 'POINT'.$productList->product_id, 'price' => -$productList->point_price, 'quantity' => $productList->point, 'name' => 'MSP Point');
                    }
                }

                // Transaction Promo - Total Price
                $transactionPromoPrice = 0;
                $transactionPromoList = TransactionPromo::where('transaction_id', $transactionId)
                    ->get();

                foreach ($transactionPromoList as $promoList) {
                    $transactionPromoPrice += $promoList->price;

                    $items[] = array('id' => 'PROMO'.$promoList->id, 'price' => -$promoList->price, 'quantity' => 1, 'name' => str_limit($promoList->name, 40));
                }

                // Transaction ID - Total Price
                $transaction_details = array(
                    'order_id'         => $transactionId,
                    'gross_amount'     => ($transactionProductPrice + $transactionShippingPrice - $transactionProductPointPrice - $transactionPromoPrice)
                );

                // Populate customer's billing address
                $billing_address = array(
                    'first_name'        => $transactionAddress->first_name,
                    'last_name'         => $transactionAddress->last_name,
                    'address'           => $transactionAddress->address_name.' ('.$transactionAddress->provinsi->name.') - '.$transactionAddress->address.', '.$transactionAddress->kecamatan->name.', ',
                    'city'              => $transactionAddress->kabupaten->name,
                    'postal_code'       => $transactionAddress->postal_code,
                    'phone'             => $transactionAddress->phone,
                    'country_code'      => 'IDN'
                    );

                // Initialize Address Information
                $transactionAddressFirstName = $transactionAddress->first_name;
                $transactionAddressLastName = $transactionAddress->last_name;
                $transactionAddressPhone = $transactionAddress->phone;

                // Check Dropshipper Name
                if (!empty($transactionAddress->dropshipper_name)) {
                    $transactionAddressFirstName = $transactionAddress->dropshipper_name;
                    $transactionAddressLastName = null;
                }

                // Check Dropshipper Phone
                if (!empty($transactionAddress->dropshipper_phone)) {
                    $transactionAddressPhone = $transactionAddress->dropshipper_phone;
                }

                // Populate customer's shipping address
                $shipping_address = array(
                    'first_name'        => $transactionAddressFirstName,
                    'last_name'         => $transactionAddressLastName,
                    'address'           => $transactionAddress->address_name.' ('.$transactionAddress->provinsi->name.') - '.$transactionAddress->address.', '.$transactionAddress->kecamatan->name.', ',
                    'city'              => $transactionAddress->kabupaten->name,
                    'postal_code'       => $transactionAddress->postal_code,
                    'phone'             => $transactionAddressPhone,
                    'country_code'=> 'IDN'
                );
                break;
        }

        // Populate customer's Info
        $customer_details = array(
            'first_name'            => Auth::user()->name,
            'last_name'             => '@'.Auth::user()->username,
            'email'                     => Auth::user()->email,
            'phone'                     => Auth::user()->phone,
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address
        );

        // Data yang akan dikirim untuk request redirect_url.
        $transaction_data = array(
            'transaction_details'=> $transaction_details,
            'item_details'           => $items,
            'customer_details'   => $customer_details,
            'credit_card' => array(
                'secure' => true,
            ),

            /*'payment_type'          => 'vtweb', 
            'vtweb'                         => array(
                //'enabled_payments'    => [],
                'credit_card_3d_secure' => true
            ),*/
        );

        try
        {
            // Snap
            $snap_token = $midtrans->getSnapToken($transaction_data);
            echo $snap_token;

            // VT Web
            // $vtweb_url = $vt->vtweb_charge($transaction_data);
            // return redirect($vtweb_url);
        }
        catch (Exception $e)
        {
            return $e->getMessage;
        }

		DB::commit();
    }

    public function finish(Request $request)
    {
        $transactionTitle = null;
        $resultEncode = $request->input('result_data');
        $result = json_decode($resultEncode);

        if (!empty($result))
        {
            DB::beginTransaction();

            $statusCode = $result->status_code;
            $statusMessage = $result->status_message;
            $orderId = $result->order_id;
            $resultContent = $resultEncode;
            $orderType = substr($orderId, 0, 4); //Order Type by First 4 Digit

            // Transaction Payment - Check Availability
            $transactionPaymentCheck = TransactionPayment::where('user_id', Auth::user()->id)
                ->where('order_id', $orderId)
                ->first();

            // Transaction Payment - Create
            if (empty($transactionPaymentCheck))
            {
                // Transaction Payment History
                $transactionPaymentHistory = new TransactionPaymentHistory;

                $transactionPaymentHistory->user_id = Auth::user()->id;
                $transactionPaymentHistory->status_code = $result->status_code;
                $transactionPaymentHistory->status_message = $result->status_message;
                $transactionPaymentHistory->transaction_id = $result->transaction_id;
                $transactionPaymentHistory->order_id = $orderId;
                $transactionPaymentHistory->gross_amount = $result->gross_amount;

                $transactionPaymentHistory->payment_type = $result->payment_type;
                $transactionPaymentHistory->transaction_time = $result->transaction_time;
                $transactionPaymentHistory->transaction_status = $result->transaction_status;

                if (!empty($result->fraud_status)) {
                    $transactionPaymentHistory->fraud_status = $result->fraud_status;
                }

                $transactionPaymentHistory->finish_redirect_url = $result->finish_redirect_url;
                $transactionPaymentHistory->result = $resultContent;
                $transactionPaymentHistory->save();

                // Transaction Payment
                $transactionPayment = new TransactionPayment;

                $transactionPayment->user_id = Auth::user()->id;
                $transactionPayment->status_code = $result->status_code;
                $transactionPayment->status_message = $result->status_message;
                $transactionPayment->transaction_id = $result->transaction_id;
                $transactionPayment->order_id = $orderId;
                $transactionPayment->gross_amount = $result->gross_amount;

                $transactionPayment->payment_type = $result->payment_type;
                $transactionPayment->transaction_time = $result->transaction_time;
                $transactionPayment->transaction_status = $result->transaction_status;

                if (!empty($result->fraud_status)) {
                    $transactionPayment->fraud_status = $result->fraud_status;
                }

                $transactionPayment->finish_redirect_url = $result->finish_redirect_url;
                $transactionPayment->result = $resultContent;
                $transactionPayment->save();

                // Transaction Payment ID
                $transactionPaymentId = $transactionPayment->id;
            }

            // Transaction Payment - Update
            if (!empty($transactionPaymentCheck))
            {
                // Transaction Payment History
                $transactionPaymentHistory = new TransactionPaymentHistory;

                $transactionPaymentHistory->user_id = Auth::user()->id;
                $transactionPaymentHistory->status_code = $result->status_code;
                $transactionPaymentHistory->status_message = $result->status_message;
                $transactionPaymentHistory->transaction_id = $result->transaction_id;
                $transactionPaymentHistory->order_id = $orderId;
                $transactionPaymentHistory->gross_amount = $result->gross_amount;

                $transactionPaymentHistory->payment_type = $result->payment_type;
                $transactionPaymentHistory->transaction_time = $result->transaction_time;
                $transactionPaymentHistory->transaction_status = $result->transaction_status;

                if (!empty($result->fraud_status)) {
                    $transactionPaymentHistory->fraud_status = $result->fraud_status;
                }

                $transactionPaymentHistory->finish_redirect_url = $result->finish_redirect_url;
                $transactionPaymentHistory->result = $resultContent;
                $transactionPaymentHistory->save();

                // Transaction Payment
                $transactionPayment = TransactionPayment::where('user_id', Auth::user()->id)
                    ->where('order_id', $orderId)
                    ->update([
                        'status_code' => $result->status_code,
                        'status_message' => $result->status_message,
                        'transaction_id' => $result->transaction_id,
                        'gross_amount' => $result->gross_amount,
                        'payment_type' => $result->payment_type,
                        'transaction_time' => $result->transaction_time,
                        'transaction_status' => $result->transaction_status,
                        'finish_redirect_url' => $result->finish_redirect_url,
                        'result' => $resultContent
                ]);

                // Transaction Payment ID
                $transactionPaymentId = $transactionPaymentCheck->id;
            }

            // Transaction Payment Status Validation
            $transaction = $result->transaction_status;
            $type = $result->payment_type;
            $order_id = $result->order_id;
            $gross_amount = $result->gross_amount;
            $fraud = null;
            $pdf_url = null;
            $transactionSuccess = null;

            if (!empty($result->fraud_status)) {
                $fraud = $result->fraud_status;
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

                $pdf_url = null;

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
            }

            // Transaction Payment View Check
            if (empty($transactionView)) {
                return redirect('/');
            }

            // Order Type
            switch ($orderType) {
                // Transaction Voucher
                case config('app.voucher_code'):
                    // Check
                    $voucherCheck = VoucherTransaction::where('transaction_id', $orderId)
                        ->where('user_id', Auth::user()->id)
                        ->first();    

                    // Stock
                    if (empty($voucherCheck->payment_id)) {
                        $productId = $voucherCheck->product_id;

                        // Product Check
                        $product = Product::where('id', $productId)
                            ->first();
                                
                        if (!empty($product)) {
                            $productStock = ($product->stock - $voucherCheck->unit);

                            $productUpdate = Product::where('id', $productId)
                                ->update([
                                    'stock' => $productStock
                            ]);
                        }
                    }

                    // Update
                    $voucherUpdate = VoucherTransaction::where('transaction_id', $orderId)
                        ->where('user_id', Auth::user()->id)
                        ->update([
                            'payment_id' => $transactionPaymentId
                    ]);

                    // Check
                    $voucherCheck = VoucherTransaction::where('transaction_id', $orderId)
                        ->where('user_id', Auth::user()->id)
                        ->first();

                    $voucherId = $voucherCheck->id;

                    // Transaction Update Status
                    if (!empty($transactionSuccess)) {
                        $voucherStatusCheck = VoucherTransaction::where('transaction_id', $orderId)
                            ->where('user_id', Auth::user()->id)
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
                    break;
            
                // Transaction Point
                case config('app.point_code'):
                    // Update
                    $pointTopupUpdate = PointTopup::where('transaction_id', $orderId)
                        ->where('user_id', Auth::user()->id)
                        ->update([
                            'payment_id' => $transactionPaymentId
                    ]);

                    // Check
                    $pointTopupCheck = PointTopup::where('transaction_id', $orderId)
                        ->where('user_id', Auth::user()->id)
                        ->first();

                    $pointTopupId = $pointTopupCheck->id;

                    // Transaction Update Status
                    if (!empty($transactionSuccess)) {
                        $pointTopupStatusCheck = PointTopup::where('transaction_id', $orderId)
                            ->where('user_id', Auth::user()->id)
                            ->where('status', 1)
                            ->first();

                        if (empty($pointTopupStatusCheck)) {
                            // Point
                            $operation = 'update_mspoint';
        
                            $point = $pointTopupCheck->point;
                
                            $username = $pointTopupCheck->user->username;
                
                            // Point Minus
                            $response = new MsplifeController;
                            $response = $response->update_mspoint($operation, $username, $point);

                            // Status
                            $pointTopupStatus = PointTopup::where('transaction_id', $orderId)
                                ->where('user_id', Auth::user()->id)
                                ->update(['status' => 1]);
                        }
                    }
                    break;
            
                // Transaction Balance
                case config('app.balance_code'):
                    // Transaction - Update Transaction Payment ID
                    $balanceDepositCheck = BalanceDeposit::where('transaction_id', $orderId)
                        ->where('user_id', Auth::user()->id)
                        ->first();

                    if (empty($balanceDepositCheck)) {
                        $balanceDeposit = new BalanceDeposit;
                        $balanceDeposit->user_id = Auth::user()->id;
                        $balanceDeposit->payment_id = $transactionPaymentId;
                        $balanceDeposit->transaction_id = $orderId;
                        $balanceDeposit->save();

                        $balanceDepositId = $balanceDeposit->id;
                    }

                    if (!empty($balanceDepositCheck)) {
                        $balanceDeposit = BalanceDeposit::where('transaction_id', $orderId)
                            ->where('user_id', Auth::user()->id)
                            ->update([
                                'payment_id' => $transactionPaymentId
                        ]);

                        $balanceDepositId = $balanceDepositCheck->id;
                    }

                    // Transaction Update Status
                    if (!empty($transactionSuccess)) {
                        $balanceDepositStatusCheck = BalanceDeposit::where('transaction_id', $orderId)
                            ->where('user_id', Auth::user()->id)
                            ->where('status', 1)
                            ->first();

                        if (empty($balanceDepositStatusCheck)) {
                            $balanceNew = new Balance;
                            $balanceNew->user_id = Auth::user()->id;
                            $balanceNew->deposit_id = $balanceDepositId;
                            $balanceNew->notes = 'Penambahan Saldo';
                            $balanceNew->save();

                            $balanceDepositStatus = BalanceDeposit::where('transaction_id', $orderId)
                                ->where('user_id', Auth::user()->id)
                                ->update(['status' => 1]);
                        }
                    }
                    break;
                
                // Transaction Mall
                default:
                    // Transaction - Update Transaction Payment ID
                    $transactionUpdate = Transaction::where('id', $orderId)
                        ->where('user_id', Auth::user()->id)
                        ->update([
                            'payment_id' => $transactionPaymentId
                    ]);

                    // Transaction Update Status
                    if(!empty($transactionSuccess)) {
                        $transactionProductStatus = TransactionProduct::where('transaction_id', $transactionSuccess)
                            ->where('status', '0')
                            ->update([
                                'status' => '1'
                        ]);
                    }
                    break;
            }

            DB::commit();

            // Transaction Payment View
            return view($transactionView)->with([
                'pageTitle' => $transactionTitle,
                'transactionCode' => $order_id,
                'transactionTotal' => $gross_amount,
                'transactionAttachment' => $pdf_url,
            	'transactionMessage' => $transactionMessage
            ]);
        }

        return redirect('/');
    }

    public function notification()
    {
        $midtrans = new Veritrans;
        
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        if (empty($result)) {
           return '';
        }

        $notif = $midtrans->status($result->order_id);

        //error_log(print_r($result,TRUE));

        /*$json_result = $request->all();
        $result = json_decode($json_result);

        $notif = $midtrans->status($result->order_id);
        $json_result = json_encode($notif);
        print_r($notif);*/

        // Transaction Payment Status Validation
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = '';
        $finish_redirect_url = '';
        $transactionSuccess = null;
        $transactionCancel = null;

        if(!empty($notif->fraud_status)) {
            $fraud = $notif->fraud_status;
        }

        if(!empty($notif->finish_redirect_url)) {
            $finish_redirect_url = $notif->finish_redirect_url;
        }

        // Transaction Payment Status
        if ($transaction == 'capture') {
            // For credit card transaction, transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    echo "Transaction order_id: " . $order_id ." is challenged by FDS";

                    // Menunggu - Approve Admin
                }
                else {
                    // TODO set payment status in merchant's database to 'Success'
                    echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;

                    // Transaksi Sukses
                    $transactionSuccess = $order_id;
                }
            }
        }
        else if ($transaction == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;

            // Transaksi Sukses
            $transactionSuccess = $order_id;
        }
        else if($transaction == 'pending'){
            // TODO set payment status in merchant's database to 'Pending'
            echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;

            // Menunggu - Pengguna Menyelesaikan Transaksi
        }
        else if ($transaction == 'deny' || $transaction == 'cancel' || $transaction == 'expire') {
            // TODO set payment status in merchant's database to 'Denied'
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";

            // Transaksi Ditolak
            $transactionCancel = $order_id;
        }

        if (!empty($notif))
        {
            DB::beginTransaction();

            $result = $notif;
            $statusCode = $result->status_code;
            $statusMessage = $result->status_message;
            $transactionId = $result->transaction_id;
            $orderId = $result->order_id;
            $resultContent = $json_result;
            $orderType = substr($orderId, 0, 4); //Order Type by First 4 Digit

            // Order Type
            switch ($orderType) {
                // Transaction Voucher
                case config('app.voucher_code'):
                    $voucherCheck = VoucherTransaction::where('transaction_id', $orderId)
                        ->first();

                    if (empty($voucherCheck))
                    {
                        return '';
                    }

                    $userId = $voucherCheck->user_id;
                    break;

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

            // Transaction Payment Check
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

                // Transaction Payment ID
                $transactionPaymentId = $transactionPaymentCheck->id;
            }

            // Order Type
            switch ($orderType) {
                // Transaction Voucher
                case config('app.voucher_code'):
                    // Check
                    $voucherCheck = VoucherTransaction::where('transaction_id', $orderId)
                        ->where('user_id', $userId)
                        ->first();    

                    // Stock
                    if (empty($voucherCheck->payment_id)) {
                        $productId = $voucherCheck->product_id;
                        
                        // Product Check
                        $product = Product::where('id', $productId)
                            ->first();
                                
                        if (!empty($product)) {
                            $productStock = ($product->stock - $voucherCheck->unit);

                            $productUpdate = Product::where('id', $productId)
                                ->update([
                                    'stock' => $productStock
                            ]);
                        }
                    }

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
                                ->where('user_id', $userId)
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
                    break;

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
                
                            // Point Minus
                            $response = new MsplifeController;
                            $response = $response->update_mspoint($operation, $username, $point);

                            // Status
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

                        if (empty($balanceDepositStatusCheck)) {
                            $balanceNew = new Balance;
                            $balanceNew->user_id = $userId;
                            $balanceNew->deposit_id = $balanceDepositId;
                            $balanceNew->notes = 'Penambahan Saldo';
                            $balanceNew->save();

                            $balanceDepositStatus = BalanceDeposit::where('transaction_id', $orderId)
                                ->where('user_id', $userId)
                                ->update(['status' => 1]);
                        }
                    }

                    // Transaction Cancel Status
                    if (!empty($transactionCancel)) {
                        $balanceDepositStatusCheck = BalanceDeposit::where('transaction_id', $orderId)
                            ->where('user_id', $userId)
                            ->where('status', 1)
                            ->first();

                        if (empty($balanceDepositStatusCheck)) {
                            $balanceDepositStatus = BalanceDeposit::where('transaction_id', $orderId)
                                ->where('user_id', $userId)
                                ->update(['status' => 7]);
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
                        $this->successPayment($transactionSuccess);
                    }

                    // Transaction Cancel Status
                    if (!empty($transactionCancel)) {
                        $this->cancelPayment($transactionCancel);
                    }
                    break;
            }
            
		    DB::commit();
        }

    }
    public function successPayment($transactionId)
    {
        // Success
        $transactionProductStatus = TransactionProduct::where('transaction_id', $transactionId)
            ->where('status', '0')
            ->update([
                'status' => '1'
        ]);
        
        // Cancel to Success
        // Transaction Product Check
        $transactionProductGroup = TransactionProduct::where('transaction_id', $transactionId)
            ->where('status', 7)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->groupBy('user_id')
            ->get();

        foreach ($transactionProductGroup as $transactionProduct)
        {
            // Transaction Product Authorization Check
            $sellerId = $transactionProduct->user_id;
            $buyerId = $transactionProduct->transaction->user_id;

            // Transaction Product Cancel
            $transactionProductUpdate = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->update(['cancel' => null]);

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
                        $productStock = ($transaction->product->stock - $transaction->unit);
                        $productSold = Product::where('id', $transaction->product_id)
                            ->update([
                                'stock' => $productStock
                        ]);
                    }
                }
            }

            // Success
            $transactionProductStatus = TransactionProduct::where('transaction_id', $transactionProduct->transaction_id)
                ->where('user_id', $sellerId)
                ->update([
                    'status' => 1
            ]);
        }

        // Send Transaction Email
        $transactionProduct = TransactionProduct::where('transaction_id', $transactionId)
            ->where('status', '1')
            ->groupBy('user_id')
            ->get();

        foreach ($transactionProduct as $item)
        {
            dispatch(new SendTransactionEmail(1, $item, $item->user));
        }
    }
    public function cancelPayment($transactionId)
    {
        // Initialization
        $cancel = "Pembeli Melebihi Batas Waktu Penyelesaian Pembayaran";

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
    }
    
    public function cancel(Request $request)
    {  
        // SNAP
        $midtrans = new Veritrans;
        $cancel = $midtrans->cancel($request->id);
        var_dump($cancel);
    }
}
