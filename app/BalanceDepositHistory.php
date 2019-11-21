<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class BalanceDepositHistory extends Model
{
    protected $table = 'balance_deposit_history';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
