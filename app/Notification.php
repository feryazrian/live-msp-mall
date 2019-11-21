<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    public function sender() {
        return $this->belongsTo('Marketplace\User','sender_id');
    }

    public function receiver() {
        return $this->belongsTo('Marketplace\User','receiver_id');
    }

    public function product() {
        return $this->belongsTo('Marketplace\Product');
    }
}
