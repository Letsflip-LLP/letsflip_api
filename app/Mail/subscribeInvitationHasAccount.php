<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class subscribeInvitationHasAccount extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;

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
        // return $this->view('emails.subscribe-invitation-has-acount',$this->data)
        //        ->subject("Let's Fli!p | Account Upgraded");

        return $this->view('emails.subscribe-invitation-upgrade',$this->data)
               ->subject("Let's Fli!p | Account Upgraded");
    }
}
