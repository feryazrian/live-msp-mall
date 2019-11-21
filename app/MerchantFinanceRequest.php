<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class MerchantFinanceRequest extends Model
{
    protected $table = 'merchant_finance_requests';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function merchant() {
        return $this->belongsTo('Marketplace\Merchant');
    }
}
