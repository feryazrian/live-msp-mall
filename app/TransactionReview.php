<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class TransactionReview extends Model
{
    protected $table = 'transaction_reviews';

    public function transaction() {
        return $this->belongsTo('Marketplace\Transaction');
    }

    public function buyer() {
        return $this->belongsTo('Marketplace\User', 'buyer_id');
    }

    public function seller() {
        return $this->belongsTo('Marketplace\User', 'seller_id');
    }
}
