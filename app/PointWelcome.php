<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PointWelcome extends Model
{
    protected $table = 'point_welcome';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
