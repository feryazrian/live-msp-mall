<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class VoucherClaim extends Model
{
    protected $table = 'voucher_claims';

    public function user() {
        return $this->belongsTo('Marketplace\User', 'user_id');
    }

    public function transaction() {
        return $this->belongsTo('Marketplace\VoucherTransaction','transaction_id');
    }
}
