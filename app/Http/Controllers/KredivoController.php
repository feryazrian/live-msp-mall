<?php

namespace Marketplace\Http\Controllers;

use Curl;

use Illuminate\Http\Request;
use Marketplace\TransactionProduct;
use Marketplace\TransactionShipping;
use Marketplace\TransactionPromo;
use Marketplace\TransactionAddress;

class KredivoController extends Controller
{
    public $server_key;
    public $endpoint;
    
    public function __construct() {
        $this->server_key = (env('APP_ENV') === 'production') ? env('KREDIVO_PRODUCTION_SERVER_KEY') : env('KREDIVO_SANDBOX_SERVER_KEY');
        $this->endpoint = (env('APP_ENV') === 'production') ? env('KREDIVO_PRODUCTION_ENDPOINT') : env('KREDIVO_SANDBOX_ENDPOINT') ;
    }

    public function post($path, $body) {
        $url = $this->endpoint . $path;
        $body['server_key'] = $this->server_key;

        $post = Curl::to($url)
            ->withData($body)
            ->asJson()
            ->post();

        return $post;
    }

    public function get($pathParams){
        $url = $this->endpoint . $pathParams;

        $get = Curl::to($url)
            ->asJson()
            ->get();

        return $get;
    }

    public function checkoutUrl(Request $request) {
        // Initialize
        $pathUrl = 'v2/checkout_url';
        $transaction_id = $request->id;
        $payment_type = ($request->payment_type_id) ? $request->payment_type_id : '30_days';
        $items = []; $seller = [];
        $discountAmount = 0; $discountName = '';
        $paymentMessageStatus = null;

        // Transaction Address
        $customerAddress = TransactionAddress::where('transaction_id', $transaction_id)
            ->first();

        // Customer Details
        $customerDetails = [
            "first_name"    => $customerAddress->first_name,
            "last_name"     => $customerAddress->last_name,
            "email"         => $customerAddress->userAddress->user->email,
            "phone"         => $customerAddress->userAddress->user->phone
        ];

        // Shipping address
        $shippingAddress = [
            "first_name"    => $customerAddress->first_name,
            "last_name"     => $customerAddress->last_name,
            "address"       => $customerAddress->address,
            "city"          => $customerAddress->kabupaten->name,
            "postal_code"   => $customerAddress->postal_code,
            "phone"         => $customerAddress->phone,
            "country_code"  => "IDN"
        ];

        // Transaction Shipping
        $transactionShippingList = TransactionShipping::where('transaction_id', $transaction_id)->get();
        $shippingAmount = $transactionShippingList->sum('price');
        $shippingName = $transactionShippingList->implode('description', ' + ');

        // Collect Shipping Fee
        $shippingItems = [
            "id"            => "shippingfee",
            "name"          => $shippingName,
            "price"         => $shippingAmount,
            "quantity"      => 1,
        ];
        // Push Shipping Fee
        array_push($items, $shippingItems);
        
        // Transaction Product
        $transactionProductList = TransactionProduct::where('transaction_id', $transaction_id)->get();
        $transactionProduct = TransactionProduct::getCalculateTransactionProductsByTransId($transaction_id);
        $transactionProductPrice = (int) $transactionProduct->total;
        $discountAmount = (int) $transactionProduct->point_price;
        $discountName = ($discountAmount > 0) ? 'Penggunaan MSP Point - Rp.' . $discountAmount  . ' + ' : '' ;

        foreach ($transactionProductList as $productList) {
            // Collect Product Items
            $item = [
                "id"            => $productList->id,
                "name"          => $productList->name,
                "price"         => $productList->price,
                "url"           => route('product.detail', ['slug' => $productList->product->slug]),
                "type"          => $productList->product->category->name,
                "quantity"      => $productList->unit,
                "parent_type"   => "SELLER",
                "parent_id"     => $productList->user_id
            ];

            // Collect Seller Item
            $sellerItems = [
                "id"        => $productList->user_id,
                "name"      => $productList->user->name,
                "email"     => $productList->user->email,
                "url"       => route('user.detail', ['username' => $productList->user->username]),
                "address"   => [
                    "first_name"    => $productList->merchant->name,
                    "last_name"     => $productList->merchant->address->name,
                    "address"       => $productList->merchant->address->address,
                    "city"          => $productList->merchant->address->kabupaten->name,
                    "postal_code"   => $productList->merchant->address->postal_code,
                    "phone"         => $productList->user->phone,
                    "country_code"  => "IDN"
                ]
            ];

            // Push Product items & Seller Item
            array_push($items, $item);
            array_push($seller, $sellerItems);
        }

        // Transaction Promo - Total Price
        $transactionPromo = TransactionPromo::where('transaction_id', $transaction_id)->first();
        if ($transactionPromo) {
            $transactionPromoName = $transactionPromo->name . ' - Rp ' . (int) $transactionPromo->price;
            $discountAmount += (int) $transactionPromo->price;
            $discountItems = [
                "id" => 'discount',
                "name" => $discountName . $transactionPromoName,
                "price" => $discountAmount,
                "quantity" => 1
            ];
            // Push promo item
            array_push($items, $discountItems);
        }

        // Transaction ID - Total Price
        $transaction_details = (object) [
            'order_id'  => $transaction_id,
            'amount'    => ($transactionProductPrice + $shippingAmount - $discountAmount)
        ];

        // Collect body data
        $bodyData = [
            'payment_type'          => $payment_type,
            'transaction_details'   => [
                'amount'            => $transaction_details->amount,
                'order_id'          => $transaction_details->order_id,
                'items'             => $items
            ],
            'seller'                => $seller,
            'customer_details'      => $customerDetails,
            'shipping_address'      => $shippingAddress,
            'billing_address'       => $shippingAddress,
            'push_uri'              => route('notification.kredivo'),
            'back_to_store_uri'     => route('transaction.detail', ['id' => $transactionProductList[0]->id])
        ];

        try {
            // Fetch Checkout URL
            $post = $this->post($pathUrl, $bodyData);

            // Checing if any error
            if (!empty($post->status) === 'ERROR') {
                // $paymentMessageStatus = 'Metode pembayaran kredivo sedang mengalami gangguan! Silahkan gunakan metode pembayaran yang lain.';
                $paymentMessageStatus = 'Pembayaran dengan Kredivo Gagal. Pesan Kesalahan: ' . $post->error->message;
                return redirect()->back()->with(['warning' => $paymentMessageStatus]);
            }

            return redirect($post->redirect_url);
        } catch (Exception $e) {
            $paymentMessageStatus = 'Metode pembayaran kredivo sedang mengalami gangguan! Silahkan gunakan metode pembayaran yang lain.';
            return redirect()->route('gateway')->with(['paymentMessageStatus' => $paymentMessageStatus]);
        }
    }

    public function getStatusCodeByTransStatus($trans_status){
        // settlement: Transaction is successful  -> value = 200
        // pending: User has not completed the transaction  -> value = 201
        // deny: Transaction has been denied by Kredivo  -> value = 202
        // cancel: Transaction has been cancelled by merchant  -> value = 200
        // expire: User did not complete transaction, thus transaction is expired  -> value = 407
        switch ($trans_status) {
            case 'pending':
                $status_code = 201;
                break;
            case 'deny':
                $status_code = 202;
                break;
            case 'expiry':
                $status_code = 407;
                break;
            default:
                $status_code = 200;
                break;
        }
        return $status_code;
    }

    public function getStatusValueByTransStatus($trans_status){
        // settlement: Transaction is successful  -> value = 1
        // pending: User has not completed the transaction  -> value = 0
        // deny: Transaction has been denied by Kredivo  -> value = 7
        // cancel: Transaction has been cancelled by merchant  -> value = 6
        // expire: User did not complete transaction, thus transaction is expired  -> value = 7
        switch ($trans_status) {
            case 'pending':
                $value = 0;
                break;
            case 'settlement':
                $value = 1;
                break;
            default:
                $value = 7;
                break;
        }
        return $value;
    }

    public function cancelTransaction($data){
        try {
            $path = 'v2/cancel_transaction';
            $body = [
                'order_id'              => strval($data['order_id']),
                'transaction_id'        => strval($data['transaction_id']),
                'cancellation_reason'   => strval($data['reason']),
                'cancelled_by'          => strval($data['cancel_by']),
                'cancellation_date'     => time()
            ];
            $post = $this->post($path, $body);

            return (object) [
                'status' => $post->status, 
                'message' => ($post->status === 'OK') ? $post->message : $post->error->message
            ];
        } catch (\Throwable $th) {
            //throw $th;
            report($th);
            return 'Oopss!.. Something when wrong. Please contact the Administrator. ERROR: '. $th;
        }
    }
}
