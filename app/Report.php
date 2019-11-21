<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    public function reporter() {
        return $this->belongsTo('Marketplace\User','reporter_id');
    }

    public function reported() {
        return $this->belongsTo('Marketplace\User','reported_id');
    }
}
