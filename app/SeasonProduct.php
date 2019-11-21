<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class SeasonProduct extends Model
{
    protected $table = 'season_products';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function season() {
        return $this->belongsTo('Marketplace\Season');
    }

    public function product() {
        return $this->belongsTo('Marketplace\Product');
    }
}
