<?php

namespace Marketplace;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'username',
        'date_birth',
        'place_birth',
        'phone',
        'sex',
        'gender',

        'email_token',
        'activated',
        'photo',
        'role_id',
        'provider_id',

        'api_msp',
        'api_msp_request',
        'api_app',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function album()
    {
        return $this->hasMany('Marketplace\Album');
    }

    public function address()
    {
        return $this->hasMany('Marketplace\UserAddress');
    }

    public function product()
    {
        return $this->hasMany('Marketplace\Product');
    }

    public function productcomment()
    {
        return $this->hasMany('Marketplace\ProductComment');
    }

    public function contact()
    {
        return $this->hasMany('Marketplace\Contact');
    }

    public function wishlist()
    {
        return $this->hasMany('Marketplace\Wishlist');
    }

    public function post()
    {
        return $this->hasMany('Marketplace\Post');
    }

    public function postcomment()
    {
        return $this->hasMany('Marketplace\PostComment');
    }

    public function kabupaten()
    {
        return $this->belongsTo('Marketplace\Kabupaten', 'place_birth');
    }

    public function provider()
    {
        return $this->belongsTo('Marketplace\UserProvider');
    }

    public function role()
    {
        return $this->belongsTo('Marketplace\UserRole');
    }

    public function type()
    {
        return $this->belongsTo('Marketplace\UserType', 'type_id');
    }

    public function merchant()
    {
        return $this->belongsTo('Marketplace\Merchant');
    }

    public function activatedUser()
    {
        return $this->hasOne('Marketplace\UserActivated', 'user_id');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public static function savingNewUserMSPLife($userMSP)
    {
        $data = [
            'name'          => $userMSP->name,
            'username'      => $userMSP->username,
            'email'         => $userMSP->email,
            'password'      => bcrypt($userMSP->pass),
            'email_token'   => md5($userMSP->email . 'x1O' . $userMSP->name),
            'api_msp'       => 1,
        ];
        $create = User::create($data);
        $create = $create->fresh();

        return $create;
    }

    public static function registerNewUser($data){
        $create = User::create($data);
        $create = $create->fresh();

        return $create;
    }

    public static function getIfUserExist($user){
        return User::where('email', $user)->orWhere('phone', $user)->first();
    }

    public static function checkIfUsernameExist($username){
        return User::where('username', $username)->first();
    }
}
