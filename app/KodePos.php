<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class KodePos extends Model
{
    protected $table = 'area_postalcodes';

    public function provinsi() {
        return $this->belongsTo('Marketplace\Provinsi', 'province_id');
    }
}
