<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PointTopup extends Model
{
    protected $table = 'point_topup';

    public function user() {
        return $this->belongsTo('Marketplace\User', 'user_id');
    }

    public function payment() {
        return $this->belongsTo('Marketplace\TransactionPayment','payment_id');
    }

    public function product() {
        return $this->belongsTo('Marketplace\PointProduct','product_id');
    }
}
