<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    protected $table = 'product_photos';

    public function product() {
        return $this->belongsTo('Marketplace\Product');
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
