<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class AutoDebet extends Model
{
    protected $table = 'auto_debet';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
