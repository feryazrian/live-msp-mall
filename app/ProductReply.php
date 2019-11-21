<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class ProductReply extends Model
{
    protected $table = 'product_replies';

    public function product() {
        return $this->belongsTo('Marketplace\Product');
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function comment() {
        return $this->belongsTo('Marketplace\ProductComment');
    }
}
