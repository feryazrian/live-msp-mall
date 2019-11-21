<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    protected $table = 'product_comments';

    public function product() {
        return $this->belongsTo('Marketplace\Product');
    }

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function reply() {
        return $this->hasMany('Marketplace\ProductReply','comment_id');
    }
}
