<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class MerchantFinance extends Model
{
    protected $table = 'merchant_finance';

    public function merchant() {
        return $this->belongsTo('Marketplace\Merchant');
    }
}
