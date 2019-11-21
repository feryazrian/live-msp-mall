<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;

use Marketplace\Provinsi;
use Marketplace\Kabupaten;
use Marketplace\Kecamatan;
use Marketplace\Desa;
use Marketplace\Message;
use Marketplace\Transaction;
use Marketplace\TransactionGateway;
use Marketplace\TransactionProduct;
use Marketplace\VoucherTransaction;
use Marketplace\PpobTransaction;

use Auth;

class JsonController extends Controller
{
	public function stats()
	{
        // Validation
        if (empty(Auth::user()->id))
        {
            $data = array(
                'cart' => null,
                'message' => null,
                'buy' => null,
                'sell' => null,
            );
    
            // Return Json
            return response()->json($data, 200);
        }

		// Initialization
        $userId = Auth::user()->id;
        $messages = 0;
        $carts = 0;
        $buy = 0;
        $sell = 0;

		// Messages
		$messages = Message::where('receiver_id', $userId)
            ->where('receiver_view', '>', 0)
            ->orWhere('sender_id', $userId)
			->where('sender_view', '>', 0)
			->get()
            ->count();
            
		// Transaction
		// Transaction Check
		$transaction = Transaction::where('user_id', $userId)
            ->where('payment_id', null)
            ->first();

        if (empty($transaction)) {
            $transaction = new Transaction;
            $transaction->user_id = $userId;
            $transaction->save();
        }
        
        $transactionId = $transaction->id;

        // Transaction Product Check
        $product = TransactionProduct::where('transaction_id', $transactionId)
            ->where('status', 0)
            ->get();

        if ($product->isEmpty()) {
            $carts = 0;
        }

        $productCount = $product->sum('unit');

        if ($productCount > 0) {
            $carts = $productCount;
        }

        // Sell Notification Count
        $sell = TransactionProduct::where('user_id', $userId)
            ->where('status', '>', 0)
            ->where('status', '<', 5)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('address_id')->whereNotNull('payment_id');
            })
            ->groupBy('transaction_id')
            ->get()
            ->count();

        // Buy Notification Count
        $buy = TransactionProduct::where('status', '<=', 4)
            ->whereHas('transaction', function($q) {
                $q->where('user_id', Auth::user()->id)
                ->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->groupBy('user_id', 'transaction_id')
            ->get()
            ->count();

        // Voucher Notification Count
        $voucher = VoucherTransaction::where('status', 0)
            ->where('user_id', Auth::user()->id)
            ->whereNotNull('payment_id')
            ->get()
            ->count();

        $ppob = PpobTransaction::where('status', 0)
            ->where('user_id', Auth::user()->id)
            ->where('status', 0)
            ->count();

        // Buy Total
        $buy = $buy + $voucher + $ppob;

        // Array
        $data = array(
            'cart' => $carts,
            'message' => $messages,
            'buy' => $buy,
            'sell' => $sell,
        );

        // Return Json
        return response()->json($data, 200);
    }
    
	public function provinsi()
	{
        // Initialization
        $response[""] = "Provinsi";

        // Lists
        $lists = Provinsi::orderBy('name', 'asc')
            ->get();
        
        foreach ($lists as $item)
        {
            $response[$item->id] = $item->name;
        }

        print json_encode($response);
    }
	public function kabupaten(Request $request)
	{
        // Initialization
        $provinsi_id = $request->provinsi_id;
        $response[""] = "Kota / Kabupaten";

        // Lists
        $lists = Kabupaten::where('province_id', $provinsi_id)
            ->orderBy('name', 'asc')
            ->get();
        
        foreach ($lists as $item)
        {
            $response[$item->id] = $item->name;
        }

        print json_encode($response);
    }
	public function kecamatan(Request $request)
	{
        // Initialization
        $kabupaten_id = $request->kabupaten_id;
        $response[""] = "Kecamatan";

        // Lists
        $lists = Kecamatan::where('regency_id', $kabupaten_id)
            ->orderBy('name', 'asc')
            ->get();
        
        foreach ($lists as $item)
        {
            $response[$item->id] = $item->name;
        }

        print json_encode($response);
    }
	public function desa(Request $request)
	{
        // Initialization
        $kecamatan_id = $request->kecamatan_id;
        $response[""] = "Kelurahan / Desa";

        // Lists
        $lists = Desa::where('district_id', $kecamatan_id)
            ->orderBy('name', 'asc')
            ->get();
        
        foreach ($lists as $item)
        {
            $response[$item->id] = $item->name;
        }

        print json_encode($response);
    }

    public function paymentMethod(Request $request)
    {
        // Initialization
        $type = $request->type;
        $response = [
            'status_code' => 400,
            'status_message' => 'Data Not Found',
            'items' => null,
        ];

        try {
            // Item list
            $list = TransactionGateway::orderBy('slug');
            if ($type == 'digital') {
                $list->where('id', '>', 1);
            }
            $items = $list->get();
            $response['items'] = $items;
            $response['status_code'] = 200;
            $response['status_message'] = count($items) . ' Data Found';
    
            return response()->json($response, $response['status_code']);
        } catch (\Execption $e) {
            $response['status_code'] = 500;
            $response['status_message'] = 'ERROR: ' . $e;
            return response()->json($response, $response['status_code']);
        }
    }
}
