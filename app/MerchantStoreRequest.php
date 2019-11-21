<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class MerchantStoreRequest extends Model
{
    protected $table = 'merchant_store_requests';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function merchant() {
        return $this->belongsTo('Marketplace\Merchant');
    }
    
    public function type() {
        return $this->belongsTo('Marketplace\UserType');
    }

    public function category() {
        return $this->belongsTo('Marketplace\Category');
    }

    public function additional() {
        return $this->belongsTo('Marketplace\Category');
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
