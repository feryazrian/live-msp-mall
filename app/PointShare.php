<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PointShare extends Model
{
    protected $table = 'point_share';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
