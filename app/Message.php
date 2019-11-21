<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    public function sender() {
        return $this->belongsTo('Marketplace\User','sender_id');
    }

    public function receiver() {
        return $this->belongsTo('Marketplace\User','receiver_id');
    }

    public function list() {
        return $this->hasMany('Marketplace\MessageList');
    }
}
