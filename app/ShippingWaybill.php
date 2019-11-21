<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class ShippingWaybill extends Model
{
    protected $table = 'shipping_waybill';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function manifest() {
        return $this->hasMany('Marketplace\ShippingManifest', 'waybill_id')->orderBy('created_at', 'ASC');
    }

    public function courier() {
        return $this->belongsTo('Marketplace\ShippingCourier', 'courier_id');
    }

    public function status() {
        return $this->belongsTo('Marketplace\ShippingStatus', 'status_id');
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
}
