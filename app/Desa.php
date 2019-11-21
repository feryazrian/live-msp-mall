<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $table = 'area_villages';

    public function kecamatan() {
        return $this->belongsTo('Marketplace\Kecamatan', 'district_id');
    }
}
