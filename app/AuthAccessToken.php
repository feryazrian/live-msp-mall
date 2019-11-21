<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;
use Firebase\JWT\JWT;

class AuthAccessToken extends Model
{
    protected $table = 'auth_access_tokens';
    protected $fillable = ['id', 'user_id', 'client_id', 'name', 'expires_at'];
    protected $casts = [
        'id' => 'text',
    ];
    
    const JWT_HASH = ['HS256']; // JWT Hash key

    public function user()
    {
        return $this->belongsTo('Marketplace\User', 'user_id', 'id');
    }

    public static function savingLog($jwt, $user_id, $client_id=0, $accessName='', $expires_date){
        $data = [
            'id' 		=> $jwt,
            'user_id'	=> $user_id,
            'client_id'	=> $client_id,
            'name'		=> $accessName,
            'expires_at'=> $expires_date
        ];

        $create = AuthAccessToken::create($data);
        $create = $create->fresh();

        return $create;
    }

    public static function jwtGenerate($data, $expired){
        $payload = [
            "iss"       => url(''),
            "iat"       => time(),
            "exp"       => $expired,
            "jti"       => encrypt($data->id),
            "username"  => $data->username,
            "name"      => $data->name,
            "email"     => $data->email,
            "admin"     => $data->role_id ? true : false,
            "merchant"  => $data->merchant_id ? true : false,
            "activated" => $data->activated == 1 ? true : false,
            "blokir"    => $data->activated == 2 ? true : false
        ];
        try {
            // JWT Encoded...
            $jwt = JWT::encode($payload, env('JWT_SECRET'));
            return $jwt;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }

    public static function jwtValidate($token){
        $exist = AuthAccessToken::find($token);
        $result = (object) [
            'status' => 401,
            'message' => 'Valid authorization token'
        ];

        if (!$exist) {
            $result->message = 'Invalid authorization token';
            return $result;
        }
        $decoded = AuthAccessToken::jwtDecode($token);
        if ($decoded === "Expired token" || !empty($decoded->exp) < time() || strtotime($exist->expires_at) < time()) {
            $result->message = 'Expired authorization token';
            return $result;
        }
        if ($exist->user->username != !empty($decoded->username)) {
            $result->message = 'Invalid authorization user';
            return $result;
        }

        $result->status = 200;
        $result->message = 'Valid authorization token';
        return $result;
    }

    public static function jwtDecode($token){
        try {
            // JWT Deccode...
            return JWT::decode($token, env('JWT_SECRET'), static::JWT_HASH);
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public static function setExpiryOldToken($token){
        try {
            // Update Expiry Token...
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
}
