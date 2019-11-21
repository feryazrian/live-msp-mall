<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    protected $table = 'transaction_products';

    public function transaction() {
        return $this->belongsTo('Marketplace\Transaction');
    }

    public function product() {
        return $this->belongsTo('Marketplace\Product');
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function checkout() {
        return $this->belongsTo('Marketplace\TransactionCheckout','checkout_id');
    }

    public function shipping() {
        return $this->belongsTo('Marketplace\TransactionShipping','shipping_id');
    }

    public function review() {
        return $this->hasMany('Marketplace\TransactionReview', 'transaction_id', 'transaction_id');
    }

    public function review_seller() {
        return $this->hasMany('Marketplace\TransactionReview', 'transaction_id', 'transaction_id')
            ->whereNotNull('seller_id');
    }

    public function review_buyer() {
        return $this->hasMany('Marketplace\TransactionReview', 'transaction_id', 'transaction_id')
            ->whereNotNull('buyer_id');
    }

    public function merchant() {
        return $this->belongsTo('Marketplace\Merchant', 'user_id', 'user_id');
    }

    public static function getCalculateTransactionProductsByTransId($trans_id){
        $data = TransactionProduct::where('transaction_id', $trans_id)
                ->where('status', 0)
                ->selectRaw('sum(unit * price) AS total, sum(unit) AS quantity, sum(point) AS point, sum(point * point_price) AS point_price')
                ->first();
        return $data;
    }

    public static function getCalculateTransactionProductsByTransIdAndSellerId($trans_id, $seller_id){
        $data = TransactionProduct::where('transaction_id', $trans_id)
                ->where('user_id', $seller_id)
                ->where('status', 0)
                ->selectRaw('sum(unit * price) AS total, sum(unit) AS quantity, sum(point) AS point, sum(point * point_price) AS point_price')
                ->first();
        return $data;
    }
}
