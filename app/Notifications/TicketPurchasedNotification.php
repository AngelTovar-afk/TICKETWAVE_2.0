<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketPurchasedNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct(public Order $order)
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
    $order = $this->order->load(['orderItems.ticketType.event']);

    // Generar QR como SVG con los datos del pedido
    $qrData  = "TicketWave|Orden#{$order->id}|Usuario:{$notifiable->name}|Total:{$order->total_amount}";
    $qrSvg   = base64_encode(QrCode::format('svg')->size(200)->generate($qrData));

    $mail = (new MailMessage)
      ->subject("🎫 Tus boletos — Orden #{$order->id}")
      ->greeting("¡Todo listo, {$notifiable->name}!")
      ->line("Tu compra ha sido confirmada. Aquí están los detalles de tu orden **#{$order->id}**:");

    foreach ($order->orderItems as $item) {
      $mail->line("• **{$item->ticketType->name}** — {$item->ticketType->event->name} × {$item->quantity} — \${$item->unit_price} c/u");
    }

    $mail
      ->line("**Total pagado: \${$order->total_amount}**")
      ->line('---')
      ->line('Presenta el siguiente código QR en la entrada del evento:')
      ->line('<img src="data:image/svg+xml;base64,' . $qrSvg . '" width="200" height="200" alt="QR Boleto"/>')
      ->line("Código de referencia: **#TW-{$order->id}-" . str_pad($notifiable->id, 4, '0', STR_PAD_LEFT) . "**")
      ->action('Ver mis boletos', route('mis-boletos'))
      ->salutation('El equipo de TicketWave');

    return $mail;
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
