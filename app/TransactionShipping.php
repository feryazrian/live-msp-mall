<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class TransactionShipping extends Model
{
    protected $table = 'transaction_shipping';

    public function transaction() {
        return $this->belongsTo('Marketplace\Transaction');
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public static function sumShippingPriceByTransId($trans_id){
        $data = TransactionShipping::where('transaction_id', $trans_id)
            ->sum('price');
        return $data;
    }
}
