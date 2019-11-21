<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class UserPin extends Model
{
    protected $table = 'user_pins';
    protected $fillable = ['user_id', 'pin', 'status'];

    public function user()
    {
        return $this->hasOne('Marketplace\User');
    }
}
