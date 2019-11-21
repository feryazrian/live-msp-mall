<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'area_provinces';

    public function kabupaten() {
        return $this->hasMany('Marketplace\Kabupaten', 'province_id');
    }
}
