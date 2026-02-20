<?php

namespace Secondnetwork\Kompass\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public $token;

    public $notifiable;

    public function __construct($token, $notifiable, $url)
    {
        $this->token = $token;
        $this->notifiable = $notifiable;
        $this->url = $url;
    }

    public function build()
    {
        return $this->markdown('kompass::mail.reset-password')
            ->subject(Lang::get('Reset Password Notification'));
    }
}
