<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class UserProvider extends Model
{
    protected $table = 'user_providers';
    protected $fillable = ['user_id', 'provider_id', 'provider'];

    public function user() {
        return $this->belongsTo('Marketplace\User', 'user_id');
    }
}
