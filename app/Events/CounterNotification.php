<?php

namespace Marketplace\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Auth;

use Marketplace\User;
use Marketplace\Message;
use Marketplace\Transaction;
use Marketplace\TransactionProduct;
use Marketplace\VoucherTransaction;

class CounterNotification implements ShouldBroadcast
{
    use SerializesModels;
 
    public $counter;
 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($counter)
    {
		// Initialization
        $userId = $counter;
        $messages = 0;
        $carts = 0;
        $buy = 0;
        $sell = 0;

		// Messages
		$messages = Message::where('receiver_id', $userId)
            ->where('receiver_view', '>', 0)
            ->orWhere('sender_id', $userId)
			->where('sender_view', '>', 0)
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
            ->whereHas('transaction', function($q) use ($userId) {
                $q->where('user_id', $userId)
                ->whereNotNull('address_id')
                ->whereNotNull('payment_id');
            })
            ->groupBy('user_id', 'transaction_id')
            ->get()
            ->count();

        // Voucher Notification Count
        $voucher = VoucherTransaction::where('status', 0)
            ->where('user_id', $userId)
            ->whereNotNull('payment_id')
            ->get()
            ->count();

        // Buy Total
        $buy = $buy + $voucher;

        // Array
        $data = array(
            'id' => $userId,
            'cart' => $carts,
            'message' => $messages,
            'buy' => $buy,
            'sell' => $sell,
        );

        $this->counter = (object) $data;
    }
 
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('counter.'.$this->counter->id);
    }
}
