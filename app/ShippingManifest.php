<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class ShippingManifest extends Model
{
    protected $table = 'shipping_manifest';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function waybill() {
        return $this->belongsTo('Marketplace\ShippingWaybill', 'waybill_id');
    }

    public function kabupaten() {
        return $this->belongsTo('Marketplace\Kabupaten','kabupaten_id');
    }
}
