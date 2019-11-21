<?php

namespace Marketplace\Console\Commands;

use Illuminate\Console\Command;
use Marketplace\TransactionProduct;
use Marketplace\TransactionPayment;
use Marketplace\TransactionPaymentHistory;
use Marketplace\Veritrans\Veritrans;
use Illuminate\Support\Facades\Mail;

class SyncTransactionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncstatus:transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Transaction status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        Veritrans::$serverKey = env('MIDTRANS_SERVER_KEY');
        Veritrans::$isProduction = env('MIDTRANS_PRODUCTION');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transProducts = TransactionProduct::whereIn('status', [0,1])
            ->whereHas('transaction', function ($q) {
                $q->whereNotNull('address_id')
                    ->whereNotNull('payment_id')
                    ->where('gateway_id', 1)
                    ->whereHas('payment', function ($qp) {
                        $qp->whereIn('transaction_status', ['pending', 'capture']);
                    });
            })
            ->orderBy('created_at')
            ->groupBy('transaction_id')
            ->get();

        $notifs = [];
        $successCount = 0;
        $spacing = '|||---------------------------------------------------------------|||';

        // START - Transaction Status Check
        $midtrans = new Veritrans;

        foreach ($transProducts as $key => $transProduct) {
            try {
                //code...
                $notif = $midtrans->status($transProduct->transaction_id);
                array_push($notifs, $notif);

                if (!empty($notif)) {
                    $resultContent = json_encode($notif);
                    $result = $notif;
                    $orderId = $notif->order_id;
                    $userId = $transProduct->transaction->user_id;
                    $type = $notif->payment_type;
                    $transaction = $notif->transaction_status;
                    $fraud = '';
                    $finish_redirect_url = '';
                    $gross_amount = '';
                    $transactionStatus = 0;

                    if (!empty($notif->gross_amount)) {
                        $gross_amount = $notif->gross_amount;
                    }

                    if (!empty($notif->fraud_status)) {
                        $fraud = $notif->fraud_status;
                    }

                    if (!empty($notif->finish_redirect_url)) {
                        $finish_redirect_url = $notif->finish_redirect_url;
                    }

                    switch ($transaction) {
                        case 'capture':
                            if ($type == 'credit_card') {
                                $transactionStatus = ($fraud == 'challenge') ? 0 : 1;
                            }
                            break;
                        case 'settlement':
                            $transactionStatus = 1;
                            break;
                        default:
                            $transactionStatus = 7;
                            break;
                    }

                    if ($transaction != 'pending') {
                        $transactionPayment = [
                            'user_id' => $userId,
                            'orderId' => $orderId,
                            'fraud_status' => $fraud,
                            'finish_redirect_url' => $finish_redirect_url,
                            'status_code' => $result->status_code,
                            'status_message' => $result->status_message,
                            'transaction_id' => $result->transaction_id,
                            'gross_amount' => $gross_amount,
                            'payment_type' => $result->payment_type,
                            'transaction_time' => $result->transaction_time,
                            'transaction_status' => $result->transaction_status,
                            'result' => $resultContent
                        ];

                        // Create or Update Payment Transaction
                        $payment = TransactionPayment::updateOrCreate(['order_id' => $orderId], $transactionPayment);
                        TransactionPaymentHistory::create($transactionPayment);

                        // START | IF Transaction == Balance Deposit
                        // Transaction - Update Transaction Payment ID
                        $transProductUpdate = TransactionProduct::where('transaction_id', $orderId)->where('status', 0)
                            ->update(['status' => $transactionStatus]);
                        $successCount += $transProductUpdate;
                    }
                }
            } catch (\Throwable $th) {
                Mail::raw($th->getMessage() . $spacing . json_encode($transProduct) . $spacing . json_encode($transProducts) . $spacing . json_encode($notifs) . $spacing . 'Success Count: ' . $successCount . $spacing . 'Data Count: ' . $transProducts->count(), function ($message) {
                    $message->to('riyo.s94@gmail.com');
                });
            }
        }

        // Mail::raw(json_encode($balanceDeposit) . $spacing . json_encode($notifs) . $spacing . 'Success Count: ' .$successCount . $spacing . 'Data Count: ' . $balanceDeposit->count(), function ($message){
        //     $message->to('riyo.s94@gmail.com');
        // });
    }
}
