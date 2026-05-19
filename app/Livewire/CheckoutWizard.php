<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Notifications\TicketPurchasedNotification;
use App\Models\Payment;

class CheckoutWizard extends Component
{
  public Event $evento;
  public int $step = 1;
  public array $quantities = [];
  public string $paymentMethod = 'tarjeta';
  public ?int $orderId = null;
  public ?string $errorMessage = null;
  public string $cardName   = '';
  public string $cardNumber = '';
  public string $cardBank   = '';
  public string $cardExpiry = '';
  public string $cardCvv    = '';

  public function mount(Event $evento): void
  {
    if ($evento->status !== 'published') {
      abort(404);
    }

    $this->evento = $evento;
    $evento->load(['ticketTypes', 'venue']);

    // Inicializar cantidades en 0 para cada tipo
    foreach ($evento->ticketTypes as $tipo) {
      $this->quantities[$tipo->id] = 0;
    }
  }

  // ── Paso 1: incrementar / decrementar ──────────────────────

  public function increment(int $ticketTypeId): void
  {
    $tipo    = $this->evento->ticketTypes->find($ticketTypeId);
    $current = $this->quantities[$ticketTypeId] ?? 0;
    $maxStock = $tipo->stock_remaining;
    $maxOrder = $tipo->max_per_order ?? $maxStock;
    $limite   = min($maxStock, $maxOrder);

    if ($current < $limite) {
      $this->quantities[$ticketTypeId] = $current + 1;
    }
  }

  public function decrement(int $ticketTypeId): void
  {
    $current = $this->quantities[$ticketTypeId] ?? 0;
    if ($current > 0) {
      $this->quantities[$ticketTypeId] = $current - 1;
    }
  }

  // ── Computed ───────────────────────────────────────────────

  #[Computed]
  public function getSubtotalProperty(): float
  {
    $total = 0;
    foreach ($this->evento->ticketTypes as $tipo) {
      $total += ($this->quantities[$tipo->id] ?? 0) * $tipo->price;
    }
    return $total;
  }

  #[Computed]
  public function getHasItemsProperty(): bool
  {
    return collect($this->quantities)->sum() > 0;
  }

  #[Computed]
  public function getSelectedItemsProperty()
  {
    return $this->evento->ticketTypes->filter(
      fn($tipo) => ($this->quantities[$tipo->id] ?? 0) > 0
    );
  }

  // ── Navegación ─────────────────────────────────────────────

  public function continuar(): void
  {
    if (!$this->hasItems) return;
    $this->step = 2;
    $this->errorMessage = null;
  }

  public function volver(): void
  {
    $this->step = 1;
    $this->errorMessage = null;
  }

  public function cancelar(): void
  {
    $this->redirect(route('eventos.show', $this->evento));
  }

  // ── Paso 3: confirmar compra ───────────────────────────────

  public function confirmar(): void
  {
    try {
      DB::transaction(function () {
        $order = Order::create([
          'user_id'      => Auth::id(),
          'status'       => 'pending',
          'total_amount' => $this->subtotal,
        ]);

        foreach ($this->evento->ticketTypes as $tipo) {
          $qty = $this->quantities[$tipo->id] ?? 0;
          if ($qty <= 0) continue;

          // Verificar stock en tiempo real antes de guardar
          $tipo->refresh();
          if ($tipo->stock_remaining < $qty) {
            throw new \Exception(
              "Ya no hay suficiente stock para \"{$tipo->name}\". Por favor ajusta la cantidad."
            );
          }

          OrderItem::create([
            'order_id'       => $order->id,
            'ticket_type_id' => $tipo->id,
            'quantity'       => $qty,
            'unit_price'     => $tipo->price,
          ]);

          $tipo->increment('quantity_sold', $qty);
        }
        Payment::create([
          'order_id'       => $order->id,
          'payment_method' => $this->paymentMethod,
          'status'         => 'pending',
        ]);

        $this->orderId = $order->id;
      });
      /** @var \App\Models\User $user */
      $user = Auth::user();
      $user->notify(new TicketPurchasedNotification(
        Order::find($this->orderId)
      ));

      $this->step = 3;
    } catch (\Exception $e) {
      $this->errorMessage = $e->getMessage();
    }
  }

  public function render()
  {
    return view('livewire.checkout-wizard')
      ->layout('layouts.checkout', ['titulo' => 'Checkout — ' . $this->evento->name]);
  }
}
