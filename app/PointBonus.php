<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PointBonus extends Model
{
    protected $table = 'point_bonus';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
