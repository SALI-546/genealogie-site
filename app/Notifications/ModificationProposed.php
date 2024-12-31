<?php

namespace App\Notifications;

use App\Models\ModificationProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ModificationProposed extends Notification
{
    use Queueable;

    protected $proposal;

    /**
     * Créer une nouvelle instance de notification.
     *
     * @return void
     */
    public function __construct(ModificationProposal $proposal)
    {
        $this->proposal = $proposal;
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
                    ->subject('Nouvelle Proposition de Modification')
                    ->greeting('Bonjour!')
                    ->line($this->proposal->proposer->name . " a proposé une modification.")
                    ->action('Voir la Proposition', route('proposals.show', $this->proposal->id))
                    ->line('Merci de votre participation à la communauté !');
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
