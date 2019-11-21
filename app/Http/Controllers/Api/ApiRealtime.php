<?php

namespace Marketplace\Http\Controllers\Api;
use Marketplace\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Marketplace\User;
use Marketplace\Message;
use Marketplace\Transaction;
use Marketplace\TransactionProduct;

use Validator;

class ApiRealtime extends Controller
{
    public function stats(Request $request)
    {
		// Initialization
    	$items = array();
        $messages = 0;
        $carts = 0;

		// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails())
        {
        	$items = $validator->errors();

	    	$responses = array(
	    		'status_code' => 207,
	    		'status_message' => 'Validation Error',
	    		'errors' => $items,
	    	);
        }

		// Check
		$userId = $request->user_id;

        $user = User::where('id', $userId)
        	->first();

        if (empty($responses) AND empty($user))
        {
			$responses = array(
		    	'status_code' => 203,
		    	'status_message' => 'Not Found',
		    	'items' => $items,
		    );
        }

        if (empty($responses))
        {
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

			// Data
        	$items[] = array(
				'cart' => $carts,
				'message' => $messages,
        	);

	    	$responses = array(
	    		'status_code' => 200,
	    		'status_message' => 'OK',
	    		'items' => $items,
	    	);
        }

        return response()->json($responses, $responses['status_code']);
    }
}
