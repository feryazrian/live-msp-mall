<?php

namespace Marketplace;

use Illuminate\Database\Eloquent\Model;
use Marketplace\Jobs\SendOTPCodeEmail;
use Illuminate\Support\Facades\DB;
use Image;

class Otp extends Model
{
    protected $table = 'otps';
    protected $fillable = ['code', 'user_provider', 'provider_type', 'used', 'action'];

    const EXPIRATION_TIME = 15; // minutes
    const REREQUEST_TIME = 1.5; // minutes

    // public function __construct(array $attributes = [])
    // {
    //     if (! isset($attributes['code'])) {
    //         $attributes['code'] = $this->generateCode();
    //     }

    //     parent::__construct($attributes);
    // }

    /**
     * True if the token is not used nor expired
     *
     * @return bool
     */
    public function isValid()
    {
        return ! $this->isUsed() && ! $this->isExpired();
    }

    /**
     * Is the current token used
     *
     * @return bool
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * Is the current token expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->created_at->diffInMinutes(date('Y-m-d H:i:s')) > static::EXPIRATION_TIME;
    }

    /**
     * Is the can re request time
     *
     * @return bool
     */
    public function reRequest()
    {
        return $this->created_at->diffInMinutes(date('Y-m-d H:i:s')) > static::REREQUEST_TIME;
    }

    /**
     * Is the current email valid
     *
     * @return string
     */
    public static function isValidEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Is the current email valid
     *
     * @return int
     */
    public static function isValidPhone($value)
    {
        if (strlen($value) < 9) {
            return 0;
        }
        return preg_match("/^([0-9\s\-\+\(\)]*)$/", $value);
    }

    /**
     * Generate a six digits code
     *
     * @param int $codeLength
     * @return string
     */
    public static function generateCode($codeLength = 5)
    {
        $min = pow(10, $codeLength);
        $max = $min * 10 - 1;
        $code = mt_rand($min, $max);

        return $code;
    }

    /**
     * Generate a OTP Image
     *
     * @param int $otp
     * @return string
     */
    public static function generateImageCode($otp, $user)
    {
        try {
            // create Image from file
            $randName = random_int(1,10);
            $imgPath = public_path('images/bg-otp/'. $randName .'.png');
    
            $img = Image::make($imgPath);
            // use callback to define details
            $img->text($otp, 200, 100, function($font) {
                $font->file('fonts/lato/lato-black.ttf');
                $font->size(90);
                $font->color('#000000');
                $font->align('center');
                $font->valign('middle');
            });
    
            $encryptName = encrypt($otp.$user);
            $newFileName = 'images/otps/'.$encryptName.'.png';
            $newFilePath = public_path($newFileName);
            $img->save($newFilePath);

            return $newFileName;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Request OTP code by user provider email / phone
     *
     * Generate Default Provider type by - SUM(ASCII) / 3
     * Email = 120
     * Phone = 252
     * 
     * @param int $codeLength
     * @return object
     */
    public static function requestOtp($userProvider, $type, $action)
    {
        $result = (object) ['status' => 500, "message" => '', "data" => null, 'timer' => 0];

        $otpExist = otp::where('user_provider', $userProvider)->orderBy('created_at', 'DESC')->first();

        if ($otpExist) {
            $current = time();
            $expire = strtotime($otpExist->created_at)+120;
            if (!empty($otpExist->reRequest()) === false) {
                $timer = $expire - $current;
                $result->status = 400;
                $result->message = 'Oops!. Anda dapat meminta OTP kembali dalam waktu ' . $timer . ' detik';
                $result->timer = $timer;
                return $result;
            }
        }

        $otp = Otp::generateCode();
        if (!$otp) {
            $result->message = 'Oops!. Kode OTP gagal digenerate';
            return $result;
        }

        $otpImage = Otp::generateImageCode($otp, $userProvider);
        if (!$otpImage) {
            $result->message = 'Oops!. Gambar Kode OTP gagal digenerate';
            return $result;
        }

        DB::beginTransaction();
        try {
            $logo = Option::find(2);
            $data = [
                'code'          => $otp,
                'user_provider' => $userProvider,
                'provider_type' => $type,
                'used'          => 0,
                'action'        => $action,
                'expiry'        => static::EXPIRATION_TIME,
                'logo'          => !empty($logo->content) ? $logo->content : 'https://mymspmall.id/uploads/options/logo_color.png',
                'otpImage'      => $otpImage
            ];

            Otp::create($data);

            if ($type == 120) {
                dispatch(new SendOTPCodeEmail((object) $data)); 
            }

            $result->status = 201;
            $result->message = 'Kode OTP berhasil dibuat dan telah dikirim ke ' . $userProvider;

            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            $result->message = 'Oops!. Something when wrong. Err: ' . $th->getMessage();
            DB::rollback();
            return $result;
        }
    }

    /**
     * Verified OTP code by user provider email / phone
     *
     * Generate Default Provider type by - SUM(ASCII) / 3
     * Email = 120
     * Phone = 252
     * 
     * @param int $codeLength
     * @return object
     */
    public static function verifyOtp($userProvider, $otp, $type, $action)
    {
        $result = (object) ['status' => 500, "message" => '', "data" => null];
        $logData = [
            'user_provider' => $userProvider,
            'action'        => $action,
            'verified'      => 0
        ];

        $otp = Otp::where('code', $otp)->where('user_provider', $userProvider)->orderBy('created_at', 'DESC')->first();

        if (!$otp || !$otp->isValid() || $otp->isUsed()) {
            $result->status = 400;
            $result->message = 'Kode OTP tidak valid';
            // Saving log
            OtpsVerifiedLog::create($logData);
            return $result;
        }

        if ($otp->isExpired()) {
            $result->status = 400;
            $result->message = 'Kode OTP sudah kadaluarsa';
            // Saving log
            OtpsVerifiedLog::create($logData);
            return $result;
        }

        DB::beginTransaction();
        try {
            // Verify OTP Code status
            Otp::where('id', $otp->id)->update(['used' => 1]);
            $result->status = 200;
            $result->message = 'Kode OTP berhasil divalidasi';
            if ($action == 'aktivasi') {
                $userData = User::getIfUserExist($userProvider);
                if ($userData) {
                    $userActivated = UserActivated::getUserActivatedIfNotExist($userData->id);
                    if ($userActivated) {
                        switch ($type) {
                            case 120:
                                $data = ['email' => 1];
                                break;
                            case 252:
                                $data = ['phone' => 1];
                                break;
                        }
                        UserActivated::where('user_id', $userData->id)->update($data);
                    }
                }
            }
            // Saving log
            $logData['verified'] = 1;
            OtpsVerifiedLog::create($logData);
            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            $result->message = 'Oops! Something when wrong. Err: ' . $th->getMessage();
            DB::rollback();
            return $result;
        }
    }

    /**
     * Block multiple request OTP in 24 Hours
     *
     * @param int $userProvider
     * @param int $action
     * @return boolean
     */
    public static function blockOtpRequest($userProvider, $action){
        $countOtp = Otp::where('user_provider', $userProvider)->where('action', $action)->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:23:59')])->count();
        if ($countOtp >= 5) {
            return true;
        }
        return false;
    }
}
