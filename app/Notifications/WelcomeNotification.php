<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct()
  {
    //
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail(object $notifiable): MailMessage
  {
    return (new MailMessage)
      ->subject('¡Bienvenido a TicketWave!')
      ->greeting("¡Hola, {$notifiable->name}!")
      ->line('Nos alegra tenerte en TicketWave, la plataforma para vivir experiencias inolvidables.')
      ->line('Ya puedes explorar eventos, comprar boletos y gestionar tus entradas desde tu cuenta.')
      ->action('Explorar eventos', url('/'))
      ->line('Si tienes alguna duda, estamos aquí para ayudarte.')
      ->salutation('El equipo de TicketWave');
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
    return [
      //
    ];
  }
}
