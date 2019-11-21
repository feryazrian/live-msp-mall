<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PointInstall extends Model
{
    protected $table = 'point_install';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
