<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'merchants';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function type() {
        return $this->belongsTo('Marketplace\UserType');
    }

    public function category() {
        return $this->belongsTo('Marketplace\Category');
    }

    public function additional() {
        return $this->belongsTo('Marketplace\Category');
    }

    public function address() {
        return $this->belongsTo('Marketplace\MerchantAddress');
    }

    public function finance() {
        return $this->belongsTo('Marketplace\MerchantFinance');
    }
}
