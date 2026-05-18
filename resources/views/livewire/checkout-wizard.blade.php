<div class="max-w-6xl mx-auto px-4">
  <div class="flex flex-col lg:flex-row gap-6 items-start">

    {{-- ── Columna principal (izquierda) ── --}}
    <div class="flex-1">

      {{-- Header con pasos --}}
      <div class="flex items-center justify-center gap-4 mb-8">
        @foreach([1 => 'Selección', 2 => 'Resumen', 3 => 'Confirmación'] as $num => $label)
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                            {{ $step >= $num ? 'bg-[#83D5AB] text-[#051F20]' : 'bg-[#235347] text-[#8EB69B]' }}">
            {{ $num }}
          </div>
          <span class="text-sm {{ $step >= $num ? 'text-[#83D5AB]' : 'text-[#8EB69B]' }}">
            {{ $label }}
          </span>
        </div>
        @if($num < 3)
          <div class="w-10 h-px {{ $step > $num ? 'bg-[#83D5AB]' : 'bg-[#235347]' }}">
      </div>
      @endif
      @endforeach
    </div>

    {{-- Info del evento --}}
    <div class="bg-[#0B2B26] border border-[#235347] rounded-xl p-4 mb-6 flex items-center gap-4">
      <div class="bg-[#235347] rounded-lg p-3 text-center min-w-[56px]">
        <p class="text-[#83D5AB] text-2xl font-bold leading-none">
          {{ \Carbon\Carbon::parse($evento->event_date)->format('d') }}
        </p>
        <p class="text-[#83D5AB] text-xs uppercase font-semibold mt-1">
          {{ \Carbon\Carbon::parse($evento->event_date)->locale('es')->translatedFormat('M') }}
        </p>
      </div>
      <div>
        <p class="text-white font-bold">{{ $evento->name }}</p>
        <p class="text-[#8EB69B] text-sm">📍 {{ $evento->venue->city }}, {{ $evento->venue->state }}</p>
      </div>
    </div>

    {{-- ── PASO 1: Selección ── --}}
    @if($step === 1)
    <div class="bg-[#0B2B26] border border-[#235347] rounded-xl overflow-hidden mb-6">
      <div class="p-5 border-b border-[#235347]">
        <h2 class="text-white font-semibold text-lg">Selecciona tus entradas</h2>
      </div>

      <div class="divide-y divide-[#235347]">
        @foreach($evento->ticketTypes as $tipo)
        @php $qty = $quantities[$tipo->id] ?? 0; @endphp
        <div class="p-5 flex items-center justify-between gap-4">
          <div class="flex-1">
            <p class="text-white font-semibold">{{ $tipo->name }}</p>
            @if($tipo->description)
            <p class="text-[#8EB69B] text-sm mt-0.5">{{ $tipo->description }}</p>
            @endif
            <p class="text-[#83D5AB] font-bold mt-1">${{ number_format($tipo->price, 2) }}</p>
            <p class="text-[#8EB69B] text-xs mt-0.5">
              {{ $tipo->stock_remaining }} disponibles
              @if($tipo->max_per_order)
              · máx {{ $tipo->max_per_order }} por orden
              @endif
            </p>
          </div>

          @if($tipo->stock_remaining > 0)
          <div class="flex items-center gap-3">
            <button wire:click="decrement({{ $tipo->id }})"
              class="w-8 h-8 rounded-full border border-[#235347] text-[#83D5AB] font-bold
                                           hover:border-[#83D5AB] transition flex items-center justify-center
                                           {{ $qty === 0 ? 'opacity-30 cursor-not-allowed' : '' }}">
              −
            </button>
            <span class="text-white font-semibold w-6 text-center">{{ $qty }}</span>
            <button wire:click="increment({{ $tipo->id }})"
              @php
              $limite=min($tipo->stock_remaining, $tipo->max_per_order ?? $tipo->stock_remaining);
              @endphp
              class="w-8 h-8 rounded-full border border-[#235347] text-[#83D5AB] font-bold
              hover:border-[#83D5AB] transition flex items-center justify-center
              {{ $qty >= $limite ? 'opacity-30 cursor-not-allowed' : '' }}">
              +
            </button>
          </div>
          @else
          <span class="text-red-400 text-sm border border-red-600/30 bg-red-600/10 px-3 py-1 rounded-full">
            Agotado
          </span>
          @endif
        </div>
        @endforeach
      </div>
    </div>

    {{-- Subtotal --}}
    <div class="bg-[#0B2B26] border border-[#235347] rounded-xl p-5 mb-6">
      <div class="flex justify-between items-center">
        <span class="text-[#8EB69B]">Subtotal</span>
        <span class="text-[#83D5AB] font-bold text-xl">${{ number_format($this->subtotal, 2) }}</span>
      </div>
    </div>

    {{-- Acciones paso 1 --}}
    <div class="flex gap-3">
      <button type="button" wire:click="cancelar"
        class="flex-1 py-3 rounded-xl border border-[#235347] text-[#8EB69B] hover:border-[#83D5AB] hover:text-white transition font-semibold">
        Cancelar
      </button>
      @if($this->hasItems)
      <button wire:click="continuar"
        class="flex-1 py-3 rounded-xl font-semibold transition bg-[#83D5AB] text-[#051F20] hover:bg-[#6bc99a]">
        Continuar →
      </button>
      @else
      <button disabled
        class="flex-1 py-3 rounded-xl font-semibold transition bg-[#235347] text-[#8EB69B] cursor-not-allowed opacity-50">
        Continuar →
      </button>
      @endif
    </div>
    @endif

    {{-- ── PASO 2: Resumen ── --}}
    @if($step === 2)
    <div class="bg-[#0B2B26] border border-[#235347] rounded-xl overflow-hidden mb-6">
      <div class="p-5 border-b border-[#235347]">
        <h2 class="text-white font-semibold text-lg">Resumen de tu compra</h2>
      </div>

      <div class="divide-y divide-[#235347]">
        @foreach($this->selectedItems as $tipo)
        @php $qty = $quantities[$tipo->id]; @endphp
        <div class="p-5 flex justify-between items-center">
          <div>
            <p class="text-white font-semibold">{{ $tipo->name }}</p>
            <p class="text-[#8EB69B] text-sm">{{ $qty }} × ${{ number_format($tipo->price, 2) }}</p>
          </div>
          <p class="text-[#83D5AB] font-bold">${{ number_format($qty * $tipo->price, 2) }}</p>
        </div>
        @endforeach
      </div>

      <div class="p-5 border-t border-[#235347] flex justify-between items-center">
        <span class="text-white font-bold text-lg">Total</span>
        <span class="text-[#83D5AB] font-bold text-2xl">${{ number_format($this->subtotal, 2) }}</span>
      </div>
    </div>

    {{-- Método de pago --}}
    <div class="bg-[#0B2B26] border border-[#235347] rounded-xl p-5 mb-6">
      <h3 class="text-white font-semibold mb-4">Método de pago</h3>
      <div class="flex flex-col gap-3">
        @foreach(['tarjeta' => '💳 Tarjeta de crédito / débito', 'transferencia' => '🏦 Transferencia bancaria', 'efectivo' => '💵 Pago en efectivo'] as $value => $label)
        <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition
        {{ $paymentMethod === $value ? 'border-[#83D5AB] bg-[#83D5AB]/10' : 'border-[#235347] hover:border-[#83D5AB]/50' }}">
          <input type="radio"
            wire:model.live="paymentMethod"
            name="paymentMethod" {{-- ← agrega esto --}}
            value="{{ $value }}"
            class="accent-[#83D5AB]">
          <span class="text-white text-sm">{{ $label }}</span>
        </label>
        @endforeach
      </div>
    </div>

    {{-- Error si hay --}}
    @if($errorMessage)
    <div class="bg-red-600/20 border border-red-600/30 text-red-400 rounded-xl p-4 mb-6 text-sm">
      ⚠️ {{ $errorMessage }}
    </div>
    @endif

    {{-- Acciones paso 2 --}}
    <div class="flex gap-3">
      <button wire:click="volver"
        class="flex-1 py-3 rounded-xl border border-[#235347] text-[#8EB69B] hover:border-[#83D5AB] hover:text-white transition font-semibold">
        ← Volver
      </button>
      <button wire:click="cancelar"
        class="py-3 px-5 rounded-xl border border-[#235347] text-[#8EB69B] hover:border-red-400 hover:text-red-400 transition font-semibold">
        Cancelar
      </button>
      <button wire:click="confirmar"
        wire:loading.attr="disabled"
        class="flex-1 py-3 rounded-xl bg-[#83D5AB] text-[#051F20] font-semibold hover:bg-[#6bc99a] transition">
        <span wire:loading.remove wire:target="confirmar">Confirmar compra</span>
        <span wire:loading wire:target="confirmar">Procesando...</span>
      </button>
    </div>
    @endif

    {{-- ── PASO 3: Confirmación ── --}}
    @if($step === 3)
    <div class="bg-[#0B2B26] border border-[#235347] rounded-xl p-10 text-center">
      <div class="w-16 h-16 rounded-full bg-[#83D5AB]/20 flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-[#83D5AB]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <h2 class="text-white font-bold text-2xl mb-2">¡Compra exitosa!</h2>
      <p class="text-[#8EB69B] mb-1">Tu pedido ha sido registrado correctamente.</p>
      <p class="text-[#83D5AB] font-bold text-lg mb-6">Número de pedido: #{{ $orderId }}</p>

      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('mis-boletos') }}"
          class="py-3 px-6 rounded-xl bg-[#83D5AB] text-[#051F20] font-semibold hover:bg-[#6bc99a] transition">
          Ver mis boletos
        </a>
        <a href="/"
          class="py-3 px-6 rounded-xl border border-[#235347] text-[#8EB69B] hover:border-[#83D5AB] hover:text-white transition">
          Ir al inicio
        </a>
      </div>
    </div>
    @endif

  </div>

  {{-- ── Sidebar (derecha) — solo pasos 1 y 2 ── --}}
  @if($step < 3)
    <div class="w-full lg:w-80 flex-shrink-0 flex flex-col gap-4 lg:sticky lg:top-24">

    {{-- Boletos disponibles --}}
    <div class="bg-[#0B2B26] border border-[#235347] rounded-xl overflow-hidden">
      <div class="px-5 py-3 border-b border-[#235347]">
        <p class="text-[#8EB69B] text-xs uppercase font-semibold tracking-wider">
          Boletos disponibles
        </p>
      </div>
      <div class="divide-y divide-[#235347]">
        @foreach($evento->ticketTypes as $tipo)
        <div class="px-5 py-3 flex items-center justify-between
                                {{ ($quantities[$tipo->id] ?? 0) > 0 ? 'bg-[#83D5AB]/5' : '' }}">
          <div class="flex items-center gap-2">
            @if(($quantities[$tipo->id] ?? 0) > 0)
            <span class="w-2 h-2 rounded-full bg-[#83D5AB] flex-shrink-0"></span>
            @else
            <span class="w-2 h-2 rounded-full bg-[#235347] flex-shrink-0"></span>
            @endif
            <span class="text-white text-sm">{{ $tipo->name }}</span>
          </div>
          <span class="text-[#83D5AB] text-sm font-semibold">
            ${{ number_format($tipo->price, 2) }}
          </span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Resumen de pago --}}
    <div class="bg-[#0B2B26] border border-[#235347] rounded-xl overflow-hidden">
      <div class="px-5 py-3 border-b border-[#235347]">
        <p class="text-[#8EB69B] text-xs uppercase font-semibold tracking-wider">
          Resumen de pago
        </p>
      </div>

      {{-- Detalle de items seleccionados --}}
      <div class="px-5 py-3 divide-y divide-[#235347]/50">
        @forelse($this->selectedItems as $tipo)
        <div class="flex justify-between items-center py-2">
          <span class="text-[#8EB69B] text-sm">
            {{ $tipo->name }} × {{ $quantities[$tipo->id] }}
          </span>
          <span class="text-white text-sm font-semibold">
            ${{ number_format($quantities[$tipo->id] * $tipo->price, 2) }}
          </span>
        </div>
        @empty
        <p class="text-[#8EB69B] text-sm py-3 text-center">
          Sin boletos seleccionados
        </p>
        @endforelse
      </div>

      {{-- Total --}}
      <div class="px-5 py-4 border-t border-[#235347] flex justify-between items-center">
        <span class="text-white font-bold">Total</span>
        <span class="text-[#83D5AB] font-bold text-xl">
          ${{ number_format($this->subtotal, 2) }}
        </span>
      </div>
    </div>

    {{-- Info del evento en sidebar --}}
    <div class="bg-[#0B2B26] border border-[#235347] rounded-xl p-5">
      <p class="text-[#8EB69B] text-xs uppercase font-semibold tracking-wider mb-3">
        Detalles del evento
      </p>
      <p class="text-white font-bold">{{ $evento->name }}</p>
      <p class="text-[#8EB69B] text-sm mt-2">
        🗓 {{ \Carbon\Carbon::parse($evento->event_date)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}
      </p>
      <p class="text-[#8EB69B] text-sm mt-1">
        🕐 {{ \Carbon\Carbon::parse($evento->event_date)->format('H:i') }} hrs
      </p>
      <p class="text-[#8EB69B] text-sm mt-1">
        📍 {{ $evento->venue->name }}
      </p>
      <p class="text-[#8EB69B] text-sm mt-1">
        {{ $evento->venue->city }}, {{ $evento->venue->state }}
      </p>
    </div>

</div>
@endif

</div>
</div>