<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $table = 'conditions';

    public function product() {
        return $this->hasMany('Marketplace\Product');
    }
}
