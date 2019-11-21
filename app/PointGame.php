<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PointGame extends Model
{
    protected $table = 'point_game';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
