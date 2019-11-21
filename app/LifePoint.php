<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class LifePoint extends Model
{
    protected $table = 'life_points';
    protected $fillable = [
        "total_point",
        "user_id"
    ];

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
