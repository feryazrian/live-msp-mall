<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    protected $table = 'transaction_payment';
    protected $fillable = [
        "user_id",
        "gateway_id",
        "status_code",
        "status_message",
        "transaction_id",
        "order_id",
        "gross_amount",
        "payment_type",
        "transaction_time",
        "transaction_status",
        "fraud_status",
        "finish_redirect_url",
        "result",
    ];

    public function transaction() {
        return $this->belongsTo('Marketplace\Transaction', 'order_id');
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function gateway() {
        return $this->belongsTo('Marketplace\TransactionGateway','gateway_id');
    }
}
