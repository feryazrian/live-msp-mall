<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'area_regencies';

    public function provinsi() {
        return $this->belongsTo('Marketplace\Provinsi', 'province_id');
    }

    public function kecamatan() {
        return $this->hasMany('Marketplace\Kecamatan');
    }
}
