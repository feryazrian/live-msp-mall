<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function transaction() {
        return $this->belongsTo('Marketplace\Transaction');
    }
}
