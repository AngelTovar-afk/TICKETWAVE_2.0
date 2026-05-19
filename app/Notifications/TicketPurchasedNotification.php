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

    // Generar un QR por cada OrderItem (uno por tipo de boleto)
    $tickets = $order->orderItems->map(function ($item) use ($notifiable, $order) {
      $qrData = implode('|', [
        'TW',
        "ORD:{$order->id}",
        "ITEM:{$item->id}",
        "EVT:{$item->ticketType->event->name}",
        "TIPO:{$item->ticketType->name}",
        "QTY:{$item->quantity}",
        "USR:{$notifiable->id}",
      ]);

      return [
        'evento'     => $item->ticketType->event->name,
        'tipo'       => $item->ticketType->name,
        'cantidad'   => $item->quantity,
        'precio'     => number_format($item->unit_price, 2),
        'subtotal'   => number_format($item->unit_price * $item->quantity, 2),
        'fecha'      => \Carbon\Carbon::parse($item->ticketType->event->event_date)
          ->locale('es')
          ->translatedFormat('l, d \d\e F \d\e Y — H:i \h\r\s'),
        'referencia' => 'TW-' . str_pad($order->id, 6, '0', STR_PAD_LEFT)
          . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
        'qr'         => base64_encode(
          QrCode::format('svg')->size(300)->margin(2)->generate($qrData)
        ),
      ];
    });

    return (new MailMessage)
      ->subject("🎫 Tus boletos — Orden #" . str_pad($order->id, 6, '0', STR_PAD_LEFT))
      ->view('emails.ticket-purchased', [
        'notifiable' => $notifiable,
        'order'      => $order,
        'tickets'    => $tickets,
        'total'      => number_format($order->total_amount, 2),
        'orderId'    => str_pad($order->id, 6, '0', STR_PAD_LEFT),
      ]);
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
