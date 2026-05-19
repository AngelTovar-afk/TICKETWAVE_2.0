<x-comprador-layout>
  @php
  $todosLosItems = collect();
  foreach ($orders as $order) {
  foreach ($order->orderItems as $item) {
  $fecha = \Carbon\Carbon::parse($item->ticketType->event->event_date);
  $todosLosItems->push([
  'nombre' => $item->ticketType->event->name,
  'tipo' => $item->ticketType->name,
  'fecha' => $fecha->locale('es')->translatedFormat('d \d\e F \d\e Y'),
  'status' => $order->status,
  'esProximo' => $fecha->isFuture() && $order->status !== 'cancelled',
  'cantidad' => $item->quantity,
  'precio' => '$' . number_format($item->unit_price, 2),
  'total' => '$' . number_format($order->total_amount, 2),
  'pago' => $order->payment?->payment_method ?? 'Sin información',
  'orderId' => $order->id,
  'userId' => auth()->id(),
  'qrData' => base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(150)->generate(
  "TicketWave|Orden#{$order->id}|{$item->ticketType->event->name}|{$item->ticketType->name}"
  )),
  ]);
  }
  }
  @endphp

  <div x-data="{
        vista: 'resumen',
        tabActivo: 'todos',
        items: {{ $todosLosItems->toJson() }},
        get proximos() {
            return this.items.filter(i => i.esProximo)
        },
        get itemsFiltrados() {
            if (this.tabActivo === 'proximos') return this.items.filter(i => i.esProximo)
            if (this.tabActivo === 'usados')   return this.items.filter(i => !i.esProximo)
            return this.items
        }
    }">

    {{-- ══ ESTADO 1: Resumen ══ --}}
    <div x-show="vista === 'resumen'" x-transition>

      {{-- Cards estadísticas --}}
      <div class="grid grid-cols-2 gap-4 mb-6" style="margin-top:24px; padding:0 24px;">
        <div class="rounded-2xl" style="background:#051F20; border:1px solid #1e4a32; padding:28px 28px 32px;">
          <p style="color:#7aab90; font-size:15px; font-weight:500; margin-bottom:12px;">Boletos activos</p>
          <p style="color:#7fffc4; font-size:36px; font-weight:700; line-height:1;">{{ $boletosActivos }}</p>
        </div>
        <div class="rounded-2xl" style="background:#051F20; border:1px solid #1e4a32; padding:28px 28px 32px;">
          <p style="color:#7aab90; font-size:15px; font-weight:500; margin-bottom:12px;">Total gastado</p>
          <p style="color:#e8f5ee; font-size:36px; font-weight:700; line-height:1;">${{ number_format($totalGastado, 0) }}</p>
          <p style="color:#7aab90; font-size:11px; margin-top:10px; font-family:monospace; letter-spacing:.08em;">este mes</p>
        </div>
      </div>

      {{-- Próximos eventos --}}
      <div class="rounded-2xl overflow-hidden" style="background:#051F20; border:1px solid #1e4a32; margin:0 24px 24px;">
        <div style="padding:24px 32px; border-bottom:1px solid #1e4a32;">
          <h2 style="color:#e8f5ee; font-size:20px; font-weight:600;">Próximos eventos</h2>
        </div>

        <template x-if="proximos.length === 0">
          <div class="px-8 py-16 text-center">
            <p class="text-3xl mb-3">🎫</p>
            <p class="font-semibold mb-1" style="color:#e8f5ee;">Aún no tienes boletos activos</p>
            <p class="text-sm mb-5" style="color:#7aab90;">Explora los eventos disponibles y compra tus entradas</p>
            <a href="{{ url('/eventos') }}" class="inline-block px-6 py-2 rounded-lg font-semibold text-sm" style="background:#7fffc4; color:#0d2a1f;">
              Ver eventos
            </a>
          </div>
        </template>

        <div style="padding:16px 20px; display:flex; flex-direction:column; gap:10px;">
          <template x-for="(item, i) in itemsFiltrados" :key="i">
            <div class="flex items-center gap-4"
              :style="item.status === 'cancelled'
                 ? 'background:#051F20; border:1px solid #1e4a32; border-radius:12px; padding:16px 20px; opacity:0.5;'
                 : 'background:#051F20; border:1px solid #1e4a32; border-radius:12px; padding:16px 20px;'">

              {{-- Icono --}}
              <div style="width:56px; height:56px; min-width:56px; background:#1a3d2e; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <svg style="width:28px; height:28px; color:#7fffc4;" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 3v10.55A4 4 0 1 0 14 17V7h4V3h-6z" />
                </svg>
              </div>

              {{-- Información --}}
              <div style="flex:1; min-width:0;"
                :class="item.status === 'cancelled' ? 'line-through' : ''">

                <p style="font-weight:700; font-size:15px; color:#e8f5ee; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                  x-text="item.nombre"></p>

                <p style="font-size:13px; color:#7fffc4; margin-top:2px;"
                  x-text="item.tipo"></p>

                <p style="font-size:12px; color:#7aab90; margin-top:2px;"
                  x-text="item.fecha"></p>

                <div style="display:flex; gap:16px; margin-top:4px; flex-wrap:wrap;">

                  <p style="font-size:12px; color:#7aab90;">
                    <span style="color:#e8f5ee;">Boletos comprados:</span>
                    <span x-text="item.cantidad"></span>
                  </p>

                  <p style="font-size:12px; color:#7aab90;">
                    <span style="color:#e8f5ee;">Precio unitario:</span>
                    <span x-text="item.precio"></span>
                  </p>

                  <p style="font-size:12px; color:#7aab90;">
                    <span style="color:#e8f5ee;">Total:</span>
                    <span x-text="item.total"></span>
                  </p>

                  <p style="font-size:12px; color:#7aab90;">
                    <span style="color:#e8f5ee;">Método de pago:</span>
                    <span x-text="item.pago"></span>
                  </p>

                </div>
              </div>

              {{-- Estado --}}
              <template x-if="item.status === 'cancelled'">
                <span style="font-size:11px; font-weight:700; padding:6px 16px; border-radius:999px; background:rgba(239,68,68,0.12); color:#f87171; border:1px solid rgba(239,68,68,0.3); white-space:nowrap; flex-shrink:0; letter-spacing:.05em;">
                  CANCELADO
                </span>
              </template>

              <template x-if="item.status !== 'cancelled' && item.esProximo">
                <span style="font-size:11px; font-weight:700; padding:6px 16px; border-radius:999px; background:rgba(127,255,196,0.1); color:#7fffc4; border:1px solid #7fffc4; white-space:nowrap; flex-shrink:0; letter-spacing:.05em;">
                  PRÓXIMO
                </span>
              </template>

              <template x-if="item.status !== 'cancelled' && !item.esProximo">
                <span style="font-size:11px; font-weight:700; padding:6px 16px; border-radius:999px; background:rgba(255,255,255,0.05); color:#7aab90; border:1px solid #1e4a32; white-space:nowrap; flex-shrink:0; letter-spacing:.05em;">
                  USADO
                </span>
              </template>

              {{-- QR --}}
              <template x-if="item.status !== 'cancelled'">
                <div style="display:flex; flex-direction:column; align-items:center; gap:6px; flex-shrink:0; margin-left:8px;">

                  <img :src="'data:image/svg+xml;base64,' + item.qrData"
                    style="width:64px; height:64px; border-radius:8px; background:white; padding:4px;"
                    alt="QR">

                  <span style="font-size:10px; color:#7aab90; font-family:monospace;"
                    x-text="'#TW-' + item.orderId">
                  </span>

                </div>
              </template>

            </div>
          </template>
        </div>

        <div class="mt-6 text-center">
          <button @click="vista = 'resumen'"
            class="flex items-center gap-2 mx-auto text-sm font-semibold transition hover:opacity-70"
            style="color:#7aab90; background:none; border:none; cursor:pointer;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
            Ver resumen
          </button>
        </div>
      </div>

    </div>
</x-comprador-layout>