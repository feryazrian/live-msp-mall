<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class UserActivated extends Model
{
    protected $table = 'user_activated';
    protected $fillable = [ "user_id", "email", "phone", "subscribed"];

    public function user() {
        return $this->belongsTo('Marketplace\User');
    }

    public static function getUserActivatedIfNotExist($id){
        return UserActivated::firstOrCreate(['user_id' => $id],['email' => 0, 'phone' => 0, 'subscribed' => 0]);
    }
}
