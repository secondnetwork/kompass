<?php

namespace Secondnetwork\Kompass\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    public $datamessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        $this->datamessage = $maildata;
        // with no $ sign after ->
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('kompass::mail.invitation-new')->subject(Lang::get('Invitation to access of').' '.config('app.name'));
    }
}
