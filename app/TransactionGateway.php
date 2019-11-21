<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class TransactionGateway extends Model
{
    protected $table = 'transaction_gateways';

    public function transaction() {
        return $this->hasMany('Marketplace\Transaction');
    }

    public function payment() {
        return $this->hasMany('Marketplace\TransactionPayment');
    }
}
