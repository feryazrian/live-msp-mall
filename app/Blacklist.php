<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $table = 'blacklists';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
