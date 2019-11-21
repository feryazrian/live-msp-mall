<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;

class OtpsVerifiedLog extends Model
{
    protected $table = 'otps_verified_logs';
    protected $fillable = ['user_provider', 'action', 'verified'];
    
    /**
     * Block multiple Verify OTP in 24 Hours
     *
     * @param int $userProvider
     * @param int $action
     * @return boolean
     */
    public static function blockOtpVerify($userProvider, $action){
        $countOtp = OtpsVerifiedLog::where('user_provider', $userProvider)->where('action', $action)->where('verified', 0)->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:23:59')])->count();
        if ($countOtp >= 5) {
            return true;
        }
        return false;
    }
}
