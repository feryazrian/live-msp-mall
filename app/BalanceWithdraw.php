<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class BalanceWithdraw extends Model
{
    protected $table = 'balance_withdraw';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
