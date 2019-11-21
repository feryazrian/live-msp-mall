<?php

namespace Marketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailTransaction extends Mailable
{
    use Queueable, SerializesModels;

    protected $status;
    protected $transaction;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($status, $transaction, $user)
    {
        $this->status = $status;
        $this->transaction = $transaction;
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
        $greeting = 'Hello!';
        $subject = 'Pesanan Baru dari '.config('app.name').'! Segera Kirim dan Masukkan No. Resi';
        $intro = 'Selamat, kamu mendapatkan pesanan baru! Harap kunjungi Website atau Aplikasi '.config('app.name').' untuk melihat detail pesanan.';
        $outro = '';
        $url = url('/');

        // Mobile URL Clearing
        $url = str_replace('m.','', $url);

        // Send Mail
        return $this->markdown('vendor.notifications.email-secure')
            ->subject($subject)
            ->with([
                'level' => 'status',
                'greeting' => $greeting,
                'introLines' => array($intro),
                'outroLines' => array($outro),
                'url' => $url,
            ]);
    }
}
