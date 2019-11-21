<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class TransactionAddress extends Model
{
    protected $table = 'transaction_address';

    public function transaction() {
        return $this->belongsTo('Marketplace\Transaction');
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

    public function userAddress() {
        return $this->belongsTo('Marketplace\UserAddress', 'address_id');
    }
}
