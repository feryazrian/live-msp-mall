<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class ShippingStatus extends Model
{
    use Sluggable;

    protected $table = 'shipping_status';

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
