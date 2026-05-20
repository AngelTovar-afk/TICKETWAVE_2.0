<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>TicketWave</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    body {
      background-color: #0d1f1a;
      color: #e2e8f0;
      font-family: 'Figtree', sans-serif;
    }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #0d1f1a; }
    ::-webkit-scrollbar-thumb { background: #2d6a4f; border-radius: 3px; }
  </style>
</head>

<body class="antialiased">
  <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Overlay oscuro en móvil --}}
    <div x-show="sidebarOpen"
      @click="sidebarOpen = false"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-20 bg-black/50 lg:hidden"
      style="display:none;">
    </div>

    {{-- SIDEBAR --}}
    <aside id="sidebar"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
      class="flex flex-col justify-between w-56 min-h-screen px-4 py-6
             fixed top-0 left-0 z-30
             transform transition-transform duration-300 ease-in-out"
      style="background-color:#0d1f1a; border-right:1px solid #1a3328;">

      <div>
        <div class="flex items-center justify-between mb-8 px-2">
          <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="text-2xl font-bold" style="color:#4ade80;">T</span>
            <span class="text-xl font-bold text-white">icketwave</span>
          </a>
          <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded-lg" style="color:#94a3b8;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <nav class="flex flex-col gap-1">
          @php
          $navItems = [
            ['route' => 'dashboard',    'icon' => 'home',   'label' => 'Inicio'],
            ['route' => 'mis-boletos',  'icon' => 'ticket', 'label' => 'Mis boletos'],
            ['route' => 'favoritos',    'icon' => 'heart',  'label' => 'Favoritos'],
            ['route' => 'profile.edit', 'icon' => 'user',   'label' => 'Perfil'],
            ['route' => 'ajustes',      'icon' => 'cog',    'label' => 'Ajustes'],
          ];
          @endphp

          @foreach($navItems as $item)
          @php
            $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'].'*');
            $activeStyle = $isActive ? 'background-color:#1a3d2e; color:#4ade80;' : 'color:#94a3b8;';
          @endphp
          @if(Route::has($item['route']))
          <a href="{{ route($item['route']) }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
            style="{{ $activeStyle }}">
            @include('layouts.comprador-icons', ['icon' => $item['icon'], 'active' => $isActive])
            {{ $item['label'] }}
          </a>
          @else
          <span class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium cursor-not-allowed opacity-50"
            style="color:#94a3b8;">
            @include('layouts.comprador-icons', ['icon' => $item['icon'], 'active' => false])
            {{ $item['label'] }}
          </span>
          @endif
          @endforeach
        </nav>
      </div>

      <div class="flex flex-col gap-4">
        <div class="rounded-xl p-3 text-xs" style="background-color:#1a3328; border:1px solid #2d6a4f;">
          <div class="flex items-center gap-2 mb-1">
            <svg class="w-4 h-4" style="color:#4ade80;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="font-semibold text-white">¡No te pierdas nada!</span>
          </div>
          <p style="color:#94a3b8;">Activa las notificaciones y sé el primero en enterarte de nuevos eventos.</p>
          <button class="mt-2 w-full py-1.5 rounded-lg text-xs font-semibold transition"
            style="background-color:#1a3d2e; color:#4ade80; border:1px solid #2d6a4f;">
            Activar notificaciones
          </button>
        </div>

        <div class="flex items-center justify-between px-1 text-xs" style="color:#4a6741;">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Centro de ayuda</span>
          </div>
          <div class="flex gap-2">
            <span>f</span>
            <span>ig</span>
          </div>
        </div>
      </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="flex-1 flex flex-col lg:ml-56 ml-0 transition-all duration-300">

      {{-- NAVBAR --}}
      <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3"
        style="background-color:#0d1f1a; border-bottom:1px solid #1a3328;">

        <div class="flex items-center gap-4">
          {{-- Hamburguesa móvil --}}
          <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg" style="color:#94a3b8;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          {{-- BUSCADOR DESKTOP con dropdown en tiempo real --}}
          <div class="relative hidden lg:block"
               x-data="{
                  query: '',
                  resultados: [],
                  abierto: false,
                  cargando: false,
                  timer: null,
                  buscar() {
                      clearTimeout(this.timer);
                      if (this.query.length < 2) {
                          this.resultados = [];
                          this.abierto = false;
                          return;
                      }
                      this.cargando = true;
                      this.timer = setTimeout(() => {
                          fetch(`/buscar-eventos?q=${encodeURIComponent(this.query)}`)
                              .then(r => r.json())
                              .then(data => {
                                  this.resultados = data;
                                  this.abierto = true;
                                  this.cargando = false;
                              })
                              .catch(() => { this.cargando = false; });
                      }, 300);
                  },
                  irA(url) {
                      this.abierto = false;
                      this.query = '';
                      window.location.href = url;
                  }
               }"
               @click.outside="abierto = false">

            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none"
                 style="color:#4a6741;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>

            <input type="text"
                   x-model="query"
                   @input="buscar()"
                   @keydown.escape="abierto = false"
                   placeholder="Buscar eventos, artistas o lugares"
                   class="pl-9 pr-4 py-2 rounded-lg text-sm w-72 focus:outline-none focus:ring-1"
                   style="background-color:#1a3328; color:#e2e8f0; border:1px solid #2d6a4f; --tw-ring-color:#4ade80;"
                   autocomplete="off">

            {{-- Dropdown resultados --}}
            <div x-show="abierto"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute left-0 top-full mt-2 w-80 rounded-xl shadow-xl z-50 overflow-hidden"
                 style="background-color:#1a3328; border:1px solid #2d6a4f;">

              <template x-if="cargando">
                <div class="px-4 py-3 text-sm" style="color:#94a3b8;">Buscando...</div>
              </template>

              <template x-if="!cargando && resultados.length > 0">
                <ul>
                  <template x-for="evento in resultados" :key="evento.id">
                    <li @click="irA(evento.url)"
                        class="flex items-center gap-3 px-4 py-3 cursor-pointer transition"
                        style="border-bottom:1px solid #0d2a1f;"
                        onmouseover="this.style.backgroundColor='#1a3d2e'"
                        onmouseout="this.style.backgroundColor=''">
                      <div class="w-10 h-10 rounded-lg flex-shrink-0 flex items-center justify-center"
                           style="background:linear-gradient(135deg,#1a3d2e,#2d6a4f);">
                        <span class="text-lg">🎵</span>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate" x-text="evento.nombre"></p>
                        <p class="text-xs truncate" style="color:#94a3b8;" x-text="evento.fecha + ' · ' + evento.recinto"></p>
                      </div>
                      <span class="text-xs font-semibold flex-shrink-0" style="color:#4ade80;" x-text="evento.precio"></span>
                    </li>
                  </template>
                </ul>
              </template>

              <template x-if="!cargando && resultados.length === 0">
                <div class="px-4 py-3 text-sm text-center" style="color:#94a3b8;">
                  No se encontraron eventos
                </div>
              </template>
            </div>
          </div>

          {{-- Buscador móvil --}}
          <div class="lg:hidden" x-data="{ searchOpen: false }">
            <button @click="searchOpen = !searchOpen" class="p-2 rounded-lg" style="color:#94a3b8;">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </button>
            <div x-show="searchOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute left-0 right-0 px-4 py-3 z-10"
                 style="top:100%; background-color:#0d1f1a; border-bottom:1px solid #1a3328; display:none;">
              <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:#4a6741;"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       placeholder="Buscar eventos, artistas o lugares"
                       x-ref="searchInput"
                       x-init="$watch('searchOpen', val => val && $nextTick(() => $refs.searchInput.focus()))"
                       class="w-full pl-9 pr-4 py-2 rounded-lg text-sm focus:outline-none focus:ring-1"
                       style="background-color:#1a3328; color:#e2e8f0; border:1px solid #2d6a4f; --tw-ring-color:#4ade80;">
              </div>
            </div>
          </div>
        </div>

        {{-- Acciones derecha --}}
        <div class="flex items-center gap-4">
          <button class="relative p-2 rounded-full" style="color:#94a3b8;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </button>
          <button class="relative p-2 rounded-full" style="color:#94a3b8;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
          </button>

          {{-- Avatar dropdown --}}
          <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2">
              @if(auth()->user()->avatar)
              <img src="{{ Storage::url(auth()->user()->avatar) }}"
                   class="w-8 h-8 rounded-full object-cover"
                   style="border:2px solid #4ade80;">
              @else
              <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                   style="background-color:#2d6a4f; color:#fff;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
              </div>
              @endif
              <span class="text-sm font-medium text-white">{{ auth()->user()->name }}</span>
            </button>

            <div x-show="open" @click.outside="open = false"
                 x-transition
                 class="absolute right-0 mt-2 w-44 rounded-lg shadow-lg py-1 z-50"
                 style="background-color:#1a3328; border:1px solid #2d6a4f;">
              <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm" style="color:#e2e8f0;">
                Mi perfil
              </a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm" style="color:#f87171;">
                  Cerrar sesión
                </button>
              </form>
            </div>
          </div>
        </div>
      </header>

      {{-- CONTENIDO --}}
      <main class="flex-1 px-6 py-6">
        {{ $slot }}
      </main>

      {{-- FOOTER --}}
      <footer class="px-6 py-4 flex items-center justify-between text-xs" style="border-top:1px solid #1a3328; color:#4a6741;">
        <span>FORMAS DE PAGO</span>
        <div class="flex items-center gap-3" style="color:#94a3b8;">
          <span class="px-2 py-1 rounded" style="background:#1a3328;">VISA</span>
          <span class="px-2 py-1 rounded" style="background:#1a3328;">MC</span>
          <span class="px-2 py-1 rounded" style="background:#1a3328;">AMEX</span>
          <span class="px-2 py-1 rounded" style="background:#1a3328;">OXXO</span>
          <span class="px-2 py-1 rounded" style="background:#1a3328;">MSI</span>
        </div>
        <span>Con bancos participantes</span>
      </footer>
    </div>
  </div>
</body>
</html>