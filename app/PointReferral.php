<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PointReferral extends Model
{
    protected $table = 'point_referral';

    public function user() {
        return $this->belongsTo('Marketplace\User','user_id');
    }

    public function referral() {
        return $this->belongsTo('Marketplace\User','referral_id');
    }
}
