<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class TransactionPromo extends Model
{
    protected $table = 'transaction_promo';
    protected $fillable = [
        "transaction_id",
        "user_id",
        "promo_id",
        "type",
        "name",
        "code",
        "expired",
        "price"
    ];

    public function transaction() {
        return $this->belongsTo('Marketplace\Transaction');
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function promo() {
        return $this->belongsTo('Marketplace\Promo');
    }

    public static function deleteTransactionPromoByUserId($id, $user_id){
        $delete = TransactionPromo::where('transaction_id', $id)
                ->where('user_id', $user_id)
                ->delete();

        return $delete;
    }

    public static function getdetailTransactionPromoByTransId($trans_id){
        $data = TransactionPromo::where('transaction_id', $trans_id)
            ->where('expired', '>', Carbon::now()->format('Y-m-d H:i:s'))
            ->first();
        return $data;
    }

    public static function countQuotaPromoCodeByPromoId($promo_id){
        $quota = TransactionPromo::where('promo_id', $promo_id)
                ->whereHas('transaction', function($q) {
                    $q->whereNotNull('payment_id');
                })
                ->whereDate('created_at', Carbon::today())
                ->count();
        return $quota;
    }

    public static function updateTransactionPromoPriceByTransId($trans_id, $amount){
        // $update = TransactionPromo::where('transaction_id', $trans_id)->update(['price' => $amount]);
        $update = TransactionPromo::where('transaction_id', $trans_id)->first();
        $update->price = $amount;
        $update->save();
        return $update;
    }

    public static function getAvailablePromoQuotaByPromoId($promo_id){
        $data = TransactionPromo::where('promo_id', $promo_id)
            ->whereHas('transaction', function($q) {
                $q->whereNotNull('payment_id');
            })
            ->whereDate('created_at', Carbon::today())
            ->count();
        return $data;
    }
}
