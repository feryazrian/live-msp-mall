<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';

    public function product() {
        return $this->belongsTo('Marketplace\Product');
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
