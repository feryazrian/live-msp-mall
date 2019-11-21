<?php

namespace Marketplace\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Mail;
use Marketplace\Mail\EmailTransaction;

class SendTransactionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $status;
    protected $transaction;
    protected $user;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new EmailTransaction($this->status, $this->transaction, $this->user);
        Mail::to($this->user->email)->send($email);
    }
}
