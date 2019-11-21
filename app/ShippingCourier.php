<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class ShippingCourier extends Model
{
    use Sluggable;

    protected $table = 'shipping_courier';

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
