<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class LifePointTransaction extends Model
{
    protected $table = 'life_points_transactions';
    protected $fillable = [
        "transaction_point",
        "life_point_id",
        "point_operator",
        "status",
        "transaction_id",
        "point_transaction_type_id",
        "user_id",
        "description"
    ];

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
