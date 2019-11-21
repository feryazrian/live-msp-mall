<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    protected $table = 'balance_transactions';
    protected $fillable = [
        "user_id",
        "seller_id",
        "transaction_id",
        "status",
    ];

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function seller() {
        return $this->belongsTo('Marketplace\User', 'seller_id');
    }

    public function transaction() {
        return $this->belongsTo('Marketplace\Transaction');
    }
}
