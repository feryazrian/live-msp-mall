<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $table = 'footers';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public function page() {
        return $this->hasMany('Marketplace\Page');
    }

    public function position() {
        return $this->belongsTo('Marketplace\FooterPosition', 'position_id');
    }
}
