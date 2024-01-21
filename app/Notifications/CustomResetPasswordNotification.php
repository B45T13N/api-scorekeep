<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe')
            ->line('Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe.')
            ->action('Réinitialiser le Mot de Passe', $this->url)
            ->line('Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune action supplémentaire n\'est requise.');
    }
}
