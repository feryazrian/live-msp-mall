<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PointCoupon extends Model
{
    protected $table = 'point_coupons';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
