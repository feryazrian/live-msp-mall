<?php

namespace Marketplace\Http\Controllers\Api;

use DB;
use Mail;

use Illuminate\Http\Request;
use Marketplace\Http\Controllers\Controller;

use Marketplace\Transaction;
use Marketplace\TransactionPayment;
use Marketplace\TransactionPaymentHistory;
use Marketplace\TransactionProduct;

use Marketplace\Http\Controllers\KredivoController;

class ApiNotification extends Controller
{
    private $kredivo;

    public function __construct()
    {
        $this->kredivo = new KredivoController;
    }

    public function kredivoNotification(Request $request){
        // Initialize
        $transaction_time = date('Y-m-d H:i:s', $request->transaction_time);
        $status_code = $this->kredivo->getStatusCodeByTransStatus($request->transaction_status);
        $status_transaction = $this->kredivo->getStatusValueByTransStatus($request->transaction_status);
        $string_result = json_encode($request->all());

        // Checking request status
        if ($request->status === 'OK') {
            // Checking Transaction exists?
            $transaction = Transaction::find($request->order_id);
            if ($transaction) {
                $transactionPayment = [
                    'user_id'               => $transaction->user_id,
                    'gateway_id'            => $transaction->gateway->id,
                    'status_code'           => $status_code,
                    'status_message'        => $request->message,
                    'transaction_id'        => $request->transaction_id,
                    'order_id'              => $request->order_id,
                    'gross_amount'          => $request->amount,
                    'payment_type'          => $transaction->gateway->slug,
                    'transaction_time'      => $transaction_time,
                    'transaction_status'    => $request->transaction_status,
                    'fraud_status'          => 'accept',
                    'finish_redirect_url'   => '',
                    'result'                => $string_result
                ];

                DB::beginTransaction();
                try {
                    // Create or update payment and save history payment
                    $payment = TransactionPayment::updateOrCreate(['order_id' => $request->order_id],$transactionPayment);
                    $paymentHistory = TransactionPaymentHistory::create($transactionPayment);
                    // Update Transaction and set payment id
                    $updateTransaction = Transaction::where('id', $request->order_id)->update(['payment_id' => $payment->id]);
                    // Update Transaction Product and set status
                    $updateTransactionProduct = TransactionProduct::where('transaction_id', $request->order_id)->update(['status' => $status_transaction]);
                    // Commit Change
                    $update = $this->kredivoUpdate($request);
                    DB::commit();

                    return response()->json(['status' => 'OK', 'message' => 'we have received a notification '.json_encode($update)], 200);
                } catch (Exception $e) {
                    // Rollback if any error
                    DB::rollback();
                    return $e;
                }
                
            }
        }
    }

    public function kredivoUpdate(Request $request)
    {
        $path = 'v2/update?transaction_id='.$request->transaction_id.'&signature_key='.$request->signature_key;

        $getUpdate = $this->kredivo->get($path);

        if ($getUpdate->status === 'ERROR') {
            return response()->json($getUpdate);
        }

        $status_transaction = $this->kredivo->getStatusValueByTransStatus($getUpdate->transaction_status);
        $payment = TransactionPayment::where('order_id', $getUpdate->order_id)->update(['transaction_status' => $getUpdate->transaction_status]);
        $updateTransactionProduct = TransactionProduct::where('transaction_id', $getUpdate->order_id)->update(['status' => $status_transaction]);
        return response()->json($getUpdate);
    }
}
