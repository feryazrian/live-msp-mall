<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class AdsRequest extends Model
{
    protected $table = 'ads_requests';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function position() {
        return $this->belongsTo('Marketplace\AdsPosition','position_id');
    }
}
