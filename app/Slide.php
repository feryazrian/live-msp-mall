<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $table = 'slides';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function position() {
        return $this->belongsTo('Marketplace\SlidePosition', 'position_id');
    }
}
