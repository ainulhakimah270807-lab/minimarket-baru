<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $url;
    public $pesan;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $pesan = null, $url = null)
    {
        $this->user = $user;
        $this->pesan = $pesan ?? 'Ada pembaruan status sistem dan informasi penting mengenai akun toko Anda.';
        $this->url = $url ?? url('/dashboard');
    }

    /**
     * Build the message using Markdown template.
     */
    public function build()
    {
        return $this->subject('Notifikasi Sistem Minimarket')
                    ->markdown('emails.notify');
    }
}
