<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class BalanceDeposit extends Model
{
    protected $table = 'balance_deposit';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function payment() {
        return $this->belongsTo('Marketplace\TransactionPayment','payment_id');
    }
}
