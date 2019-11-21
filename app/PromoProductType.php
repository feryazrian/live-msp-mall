<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class PromoProductType extends Model
{
    protected $table = 'promo_product_type';
    protected $fillable =[
        'promo_id',
        'product_type_id'
    ];
    //
}
