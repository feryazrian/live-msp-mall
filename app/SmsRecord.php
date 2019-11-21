<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class SmsRecord extends Model
{
    protected $table = 'sms_records';

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }
}
