<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $evento->name }} — TicketWave</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="bg-[#051F20] font-sans">

  {{-- Navbar --}}
  <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-8 py-4 bg-[#163832] border-b border-[#235347]">
    <a href="/" class="text-[#83D5AB] font-bold text-xl">T<span class="text-white">icketwave</span></a>
  </nav>

  {{-- Banner --}}
  <div class="relative mt-16" style="height: 280px;">
    <img src="{{ $evento->image_url ? asset('storage/' . $evento->image_url) : asset('hero.jpg') }}"
      alt="{{ $evento->name }}"
      class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-[#051F20]/50"></div>
    <a href="/"
      class="absolute top-4 left-4 w-10 h-10 flex items-center justify-center rounded-full bg-black/40 text-white hover:bg-black/60 transition text-lg">
      ←
    </a>
  </div>

  {{-- Layout principal --}}
  <div class="bg-[#051F20] py-8">
    <div class="max-w-6xl mx-auto px-8">
      <div class="flex flex-col lg:flex-row gap-6">

        {{-- Columna izquierda (320px fija en desktop) --}}
        <div class="w-full lg:w-80 flex-shrink-0">
          <div class="bg-[#0B2B26] rounded-xl border border-[#235347] overflow-hidden">

            {{-- 1. Botón ver mapa --}}
            <div class="p-4 border-b border-[#235347]">
              <button onclick="document.getElementById('mapa-grande').classList.toggle('hidden')"
                class="text-[#83D5AB] font-semibold text-sm hover:text-white transition">
                Ver Mapa completo
              </button>
              <div id="mapa-grande" class="hidden mt-3">
                @if($evento->venue->image_url)
                <img src="{{ asset('storage/' . $evento->venue->image_url) }}"
                  alt="Mapa completo"
                  class="w-full rounded-lg object-cover">
                @else
                <div class="w-full h-32 bg-[#051F20] rounded-lg flex items-center justify-center">
                  <p class="text-[#8EB69B] text-xs">Sin mapa disponible</p>
                </div>
                @endif
              </div>
            </div>

            {{-- 2. Thumbnail mapa --}}
            <div class="p-4 border-b border-[#235347]">
              <p class="text-[#8EB69B] text-xs uppercase font-semibold tracking-wider mb-2">Mapa del recinto</p>
              @if($evento->venue->image_url)
              <img src="{{ asset('storage/' . $evento->venue->image_url) }}"
                alt="Mapa {{ $evento->venue->name }}"
                class="w-full h-32 object-cover rounded-lg">
              @else
              <div class="w-full h-32 bg-[#051F20] rounded-lg flex items-center justify-center">
                <p class="text-[#8EB69B] text-xs">Sin mapa disponible</p>
              </div>
              @endif
            </div>

            {{-- 3. Espacios (tipos de entrada) --}}
            <div class="p-4 border-b border-[#235347]">
              <p class="text-[#83D5AB] font-semibold mb-2">Espacios</p>
              <div class="flex flex-col gap-1">
                @foreach($evento->ticketTypes as $tipo)
                <span class="text-white text-sm py-1 cursor-pointer hover:text-[#83D5AB] transition">
                  {{ $tipo->name }}
                </span>
                @endforeach
              </div>
            </div>

            {{-- 4. Info del recinto --}}
            <div class="p-4 bg-[#051F20]">
              <p class="text-[#8EB69B] text-xs uppercase font-semibold tracking-wider mb-2">Recinto</p>
              <p class="text-[#83D5AB] font-semibold">{{ $evento->venue->name }}</p>
              <p class="text-[#8EB69B] text-sm mt-1">{{ $evento->venue->city }}, {{ $evento->venue->state }}</p>
              @if($evento->venue->address)
              <p class="text-[#8EB69B] text-sm mt-1">{{ $evento->venue->address }}</p>
              @endif
              <p class="text-[#8EB69B] text-sm mt-1">Capacidad: {{ number_format($evento->venue->capacity) }} personas</p>
            </div>
          </div>
        </div>

        {{-- Columna derecha --}}
        <div class="flex-1 flex flex-col gap-6">

          {{-- Card 1: Fecha e info del evento --}}
          <div class="bg-[#0B2B26] rounded-xl border border-[#235347] p-6">
            <div class="flex items-start gap-4">
              <div class="bg-[#235347] rounded-lg p-3 text-center min-w-[70px] flex-shrink-0">
                <p class="text-[#83D5AB] text-3xl font-bold leading-none">{{ \Carbon\Carbon::parse($evento->event_date)->format('d') }}</p>
                <p class="text-[#83D5AB] text-sm uppercase font-semibold mt-1">{{ \Carbon\Carbon::parse($evento->event_date)->locale('es')->translatedFormat('M') }}</p>
              </div>
              <div>
                <h1 class="text-white font-bold text-2xl">{{ $evento->name }}</h1>
                <p class="text-[#83D5AB] text-sm mt-1">{{ $evento->category }}</p>
                <p class="text-[#8EB69B] text-sm mt-2">📍 {{ $evento->venue->city }}, {{ $evento->venue->state }}</p>
                <p class="text-[#8EB69B] text-sm mt-1">🗓 {{ \Carbon\Carbon::parse($evento->event_date)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }} — {{ \Carbon\Carbon::parse($evento->event_date)->format('H:i') }} hrs</p>
              </div>
            </div>
          </div>

          {{-- Card 2: Sobre el evento --}}
          <div class="bg-[#0B2B26] rounded-xl border border-[#235347] p-6 flex-1">
            <h2 class="text-[#83D5AB] font-semibold text-lg mb-3">Sobre el evento</h2>
            <p class="text-[#8EB69B] leading-relaxed text-base">{{ $evento->description ?? 'Sin descripción disponible.' }}</p>
          </div>
        </div>
      </div>

      {{-- Tabla de boletos (ancho completo) --}}
      <div class="mt-8 bg-[#0B2B26] rounded-xl border border-[#235347] overflow-hidden">
        <div class="p-6 border-b border-[#235347]">
          <h2 class="text-white font-semibold text-lg">Tipos de entrada</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-[#051F20]">
              <tr class="text-[#8EB69B] text-xs uppercase tracking-wider">
                <th class="text-left px-6 py-3">Tipo de boleto</th>
                <th class="text-left px-6 py-3">Descripción</th>
                <th class="text-right px-6 py-3">Precio</th>
                <th class="text-center px-6 py-3">Disponibles</th>
                <th class="text-center px-6 py-3">Acción</th>
              </tr>
            </thead>
            <tbody>
              @forelse($evento->ticketTypes as $tipo)
              <tr class="border-t border-[#235347]">
                <td class="px-6 py-4 text-white font-semibold">{{ $tipo->name }}</td>
                <td class="px-6 py-4 text-[#8EB69B] text-sm">{{ $tipo->description ?? '—' }}</td>
                <td class="px-6 py-4 text-[#83D5AB] font-semibold text-right">${{ number_format($tipo->price, 2) }}</td>
                <td class="px-6 py-4 text-center">
                  @if($tipo->quantity_available > 0)
                  <span class="text-white">{{ $tipo->quantity_available }}</span>
                  @else
                  <span class="bg-red-600/20 text-red-400 text-xs px-3 py-1 rounded-full border border-red-600/30">Agotado</span>
                  @endif
                </td>
                <td class="px-6 py-4 text-center">
                  @if($tipo->quantity_available > 0)
                  @auth
                  <a href="{{ route('eventos.checkout', $evento) }}"
                    class="bg-[#8EDBB1]/10 border border-[#83D5AB] text-[#83D5AB] px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#83D5AB] hover:text-[#051F20] transition inline-block">
                    Comprar
                  </a>
                  @else
                  <a href="{{ route('login') }}?message=Inicia+sesión+para+continuar"
                    class="bg-[#8EDBB1]/10 border border-[#83D5AB] text-[#83D5AB] px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#83D5AB] hover:text-[#051F20] transition inline-block">
                    Comprar
                  </a>
                  @endauth
                  @else
                  <button disabled class="bg-white/5 border border-[#235347] text-[#8EB69B] px-4 py-2 rounded-lg text-sm cursor-not-allowed opacity-50">
                    Agotado
                  </button>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="px-6 py-10 text-center text-[#8EB69B]">No hay tipos de entrada disponibles.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Footer --}}
  <footer class="bg-[#0B2B26] border-t border-[#235347] py-6 mt-12">
    <p class="text-center text-[#8EB69B] text-sm">@ 2026 eventos, todos los derechos reservados</p>
  </footer>

  @livewireScripts
</body>

</html>