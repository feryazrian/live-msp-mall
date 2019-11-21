<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class MerchantAccountRequest extends Model
{
    protected $table = 'merchant_account_requests';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function merchant() {
        return $this->belongsTo('Marketplace\Merchant');
    }

    public function kabupaten() {
        return $this->belongsTo('Marketplace\Kabupaten','place_birth');
    }
}
