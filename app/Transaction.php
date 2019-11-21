<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable=[
        "user_id",
        "address_id",
        "payment_id",
        "gateway_id",
        "promo_id",
        "total"
    ];

    public function user() {
        return $this->belongsTo('Marketplace\User', 'user_id');
    }

    public function address() {
        return $this->belongsTo('Marketplace\TransactionAddress','address_id');
    }

    public function payment() {
        return $this->belongsTo('Marketplace\TransactionPayment','payment_id');
    }

    public function gateway() {
        return $this->belongsTo('Marketplace\TransactionGateway','gateway_id');
    }

    public function promo() {
        return $this->belongsTo('Marketplace\TransactionPromo','promo_id');
    }

    public function product() {
        return $this->hasMany('Marketplace\TransactionProduct');
    }

    public static function getCheckoutTransactionByUserId($user_id){
        $data = Transaction::where('user_id', $user_id)
			->where('payment_id', null)
			->orderBy('id', 'ASC')
            ->first();
        
        return $data;
    }
}
