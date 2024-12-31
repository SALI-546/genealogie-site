<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InvitationReceived extends Notification
{
    use Queueable;

    protected $invitation;

    /**
     * Créer une nouvelle instance de notification.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Définir les canaux de notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Définir le contenu de l'e-mail.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Vous avez reçu une invitation')
                    ->greeting('Bonjour!')
                    ->line($this->invitation->inviter->name . " vous a invité(e) à rejoindre le site de généalogie.")
                    ->action('Accepter l\'invitation', route('invitations.accept', $this->invitation->token))
                    ->line('Merci d\'utiliser notre plateforme pour construire votre arbre généalogique !');
    }

    /**
     * Définir le contenu de la notification pour les autres canaux.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
