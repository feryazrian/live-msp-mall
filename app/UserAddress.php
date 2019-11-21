<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_address';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function provinsi() {
        return $this->belongsTo('Marketplace\Provinsi','provinsi_id');
    }

    public function kabupaten() {
        return $this->belongsTo('Marketplace\Kabupaten','kabupaten_id');
    }

    public function kecamatan() {
        return $this->belongsTo('Marketplace\Kecamatan','kecamatan_id');
    }

    public function desa() {
        return $this->belongsTo('Marketplace\Desa','desa_id');
    }
}
