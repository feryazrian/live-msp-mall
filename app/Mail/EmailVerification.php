<?php

namespace Marketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Initialization
        $subject = 'Aktivasi Akun '.config('app.name');
        $url = url('/');

        // Url Activation
        $actionUrl = route('activation', ['token' => $this->user->email_token]);

        // Mobile URL Clearing
        $url = str_replace('m.','', $url);
        $actionUrl = str_replace('m.','', $actionUrl);

        return $this->markdown('vendor.notifications.email-secure')
            ->subject($subject)
            ->with([
                'level' => 'status',
                'actionText' => 'Activate Account',
                'introLines' => array(),
                'outroLines' => array(),
                'actionUrl' => $actionUrl,
                'url' => $url,
        ]);
    }
}
