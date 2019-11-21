<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{
    protected $table = 'user_auths';
    protected $fillable = ['type', 'user_id', 'user_ip', 'user_agent'];

    public function user() {
        return $this->belongsTo('Marketplace\User', 'user_id');
    }

    public static function savingAuthUser($action, $user_id, $ip, $user_agent){
        $data = [
            'type' => $action,
            'user_id' => $user_id,
            'user_ip' => $ip,
            'user_agent' => $user_agent,
        ];

        $create = UserAuth::create($data);
        $create = $create->fresh();

        return $create;
    }
}
