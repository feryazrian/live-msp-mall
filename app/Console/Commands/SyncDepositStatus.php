<?php

namespace Marketplace\Console\Commands;

use Illuminate\Console\Command;
use Marketplace\Veritrans\Veritrans;
use Marketplace\Balance;
use Marketplace\BalanceDeposit;
use Marketplace\TransactionPayment;
use Marketplace\TransactionPaymentHistory;
use Illuminate\Support\Facades\Mail;

class SyncDepositStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncstatus:deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Deposit status';

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
        $balanceDeposit = BalanceDeposit::where('status', 0)->orderBy('created_at')->get();
        $notifs = [];
        $successCount = 0;
        $spacing = '|||---------------------------------------------------------------|||';

        // START - Transaction Status Check
        $midtrans = new Veritrans;

        foreach ($balanceDeposit as $key => $deposit) {
            try {
                //code...
                $notif = $midtrans->status($deposit->transaction_id);
                array_push($notifs, $notif);

                if (!empty($notif)) {
                    $resultContent = json_encode($notif);
                    $result = $notif;
                    $orderId = $notif->order_id;
                    $userId = $deposit->user_id;
                    $type = $notif->payment_type;
                    $transaction = $notif->transaction_status;
                    $fraud = '';
                    $finish_redirect_url = '';
                    $gross_amount = '';
                    $transactionStatus = 0;
                    $transactionMessage = 'Menunggu Penyelesaian Pembayaran';
    
                    if (!empty($notif->gross_amount)) {
                        $gross_amount = $notif->gross_amount;
                    }
    
                    if (!empty($notif->fraud_status)) {
                        $fraud = $notif->fraud_status;
                    }
    
                    if (!empty($notif->finish_redirect_url)) {
                        $finish_redirect_url = $notif->finish_redirect_url;
                    }
    
                    if ($transaction == 'capture') {
                        if ($type == 'credit_card') {
                            $transactionStatus = ($fraud == 'challenge') ? 0 : 1;
                        }
                    } else if ($transaction == 'settlement') {
                        // Transaksi Sukses
                        $transactionStatus = 1;
                        $transactionMessage = 'Penambahan Saldo';
                        if ($gross_amount >= 150000) {
                            Mail::raw(json_encode($deposit) . $spacing . json_encode($notif) . $spacing . 'Data Count: ' . $balanceDeposit->count(), function ($message){
                                $message->to('riyo.s94@gmail.com');
                            });
                        }
                    } else if ($transaction == 'deny' || $transaction == 'cancel' || $transaction == 'expire') {
                        $transactionStatus = 7;
                        $transactionMessage = 'Deposit Gagal';
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
                        $balanceUpdate = BalanceDeposit::where('transaction_id', $orderId)
                            ->where('user_id', $userId)
                            ->update([
                                'payment_id' => $payment->id,
                                'status' => $transactionStatus
                            ]);
                        $successCount += $balanceUpdate;
        
                        $balanceDepositStatusCheck = Balance::where('deposit_id', $deposit->id)->first();
        
                        // Create Transaction Update Status
                        if (!$balanceDepositStatusCheck) {
                            $balanceData = [
                                'user_id' => $userId,
                                'deposit_id' => $deposit->id,
                                'notes' => $transactionMessage
                            ];
                            Balance::create($balanceData);
                        }
                    }
                }
            } catch (\Throwable $th) {
                Mail::raw($th->getMessage(). $spacing . json_encode($deposit) . $spacing . json_encode($balanceDeposit) . $spacing . json_encode($notifs) . $spacing . 'Success Count: ' .$successCount . $spacing . 'Data Count: ' . $balanceDeposit->count(), function ($message){
                    $message->to('riyo.s94@gmail.com');
                });
            }
        }

        // Mail::raw(json_encode($balanceDeposit) . $spacing . json_encode($notifs) . $spacing . 'Success Count: ' .$successCount . $spacing . 'Data Count: ' . $balanceDeposit->count(), function ($message){
        //     $message->to('riyo.s94@gmail.com');
        // });
    }
}
