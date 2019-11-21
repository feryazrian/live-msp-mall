<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class TransactionCheckout extends Model
{
    protected $table = 'transaction_checkout';

    public function transaction() {
        return $this->belongsTo('Marketplace\Transaction');
    }
}
