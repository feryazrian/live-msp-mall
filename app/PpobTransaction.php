<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PpobTransaction extends Model
{
    protected $table = 'ppob_transactions';

    public function user() {
        return $this->belongsTo('Marketplace\User', 'user_id');
    }

    public function plan() {
        return $this->belongsTo('Marketplace\PpobPlan','plan_id');
    }

    public function type() {
        return $this->belongsTo('Marketplace\PpobType','type_id');
    }

    public function operator() {
        return $this->belongsTo('Marketplace\PpobOperator','operator_id');
    }

    public function payment() {
        return $this->belongsTo('Marketplace\TransactionPayment','payment_id');
    }
    public function transaction(){
        return $this->belongsTo('Marketplace\Transaction','transaction_id');

    }
    protected $fillable =[
        "user_id",
        "type_id",
        "operator_id",
        "plan_id",
        "payment_id",
        "transaction_id",
        "product",
        "ref_id" ,
        "cust_number",
        "tr_code",
        "tr_id",
        "price",
        "status",
        "reff_id",
        "serial_number",            
        "balance",
        "r_balance",
        "pin",
        "date_transaction"
    ];
    //
}
