<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class MessageList extends Model
{
    protected $table = 'message_lists';

    public function sender() {
        return $this->belongsTo('Marketplace\User','sender_id');
    }

    public function receiver() {
        return $this->belongsTo('Marketplace\User','receiver_id');
    }

    public function message() {
        return $this->belongsTo('Marketplace\Message');
    }
}
