<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $dates = [
        'preorder_expired',
    ];

    public function type() {
        return $this->belongsTo('Marketplace\ProductType');
    }

    public function action() {
        return $this->belongsTo('Marketplace\ProductAction');
    }

    public function productphoto() {
        return $this->hasMany('Marketplace\ProductPhoto');
    }

    public function category() {
        return $this->belongsTo('Marketplace\Category');
    }

    public function wishlist() {
        return $this->hasMany('Marketplace\Wishlist');
    }

    public function condition() {
        return $this->belongsTo('Marketplace\Condition');
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function comment() {
        return $this->hasMany('Marketplace\ProductComment');
    }
}
