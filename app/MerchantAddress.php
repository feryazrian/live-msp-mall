<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class MerchantAddress extends Model
{
    protected $table = 'merchant_address';

    public function merchant() {
        return $this->belongsTo('Marketplace\Merchant');
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
