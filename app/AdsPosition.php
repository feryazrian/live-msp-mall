<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class AdsPosition extends Model
{
    protected $table = 'ads_position';

    public function request() {
        return $this->hasMany('Marketplace\AdsRequest');
    }
}
