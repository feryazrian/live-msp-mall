<?php

namespace Marketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OTPCode extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('misc.otp-mail')
            ->subject('Kode OTP Aktivasi MSP Mall')
            ->with([
                'logo'      => $this->data->logo,
                'otpCode'   => $this->data->code,
                'otpImg'    => $this->data->otpImage,
                'expiry'    => $this->data->expiry
            ]);
    }
}
