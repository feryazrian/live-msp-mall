<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'area_districts';

    public function kabupaten() {
        return $this->belongsTo('Marketplace\Kabupaten', 'regency_id');
    }

    public function desa() {
        return $this->hasMany('Marketplace\Desa');
    }
}
