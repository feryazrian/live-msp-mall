<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $table = 'balances';
    protected $fillable = [
        "user_id",
        "deposit_id",
        "withdraw_id",
        "transaction_id",
        "voucher_id",
        "ppob_id",
        "ads_id",
        "notes",
    ];

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function deposit() {
        return $this->belongsTo('Marketplace\BalanceDeposit','deposit_id');
    }

    public function withdraw() {
        return $this->belongsTo('Marketplace\BalanceWithdraw','withdraw_id');
    }

    public function transaction() {
        return $this->belongsTo('Marketplace\BalanceTransaction','transaction_id');
    }

    public function voucher() {
        return $this->belongsTo('Marketplace\VoucherTransaction','voucher_id');
    }

    public function ppob() {
        return $this->belongsTo('Marketplace\PpobTransaction','ppob_id');
    }
}
