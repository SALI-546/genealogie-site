<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;

    /**
     * Créer une nouvelle instance de message.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Construire le message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Invitation à rejoindre le site de généalogie')
                    ->view('emails.invitation');
    }
}
