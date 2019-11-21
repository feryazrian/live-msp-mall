<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class VoucherTransaction extends Model
{
    protected $table = 'voucher_transactions';

    public function user() {
        return $this->belongsTo('Marketplace\User', 'user_id');
    }

    public function payment() {
        return $this->belongsTo('Marketplace\TransactionPayment','payment_id');
    }

    public function product() {
        return $this->belongsTo('Marketplace\Product','product_id');
    }
}
