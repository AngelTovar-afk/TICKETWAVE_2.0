<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eventos — TicketWave</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#051F20]">

{{-- Navbar --}}
<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-8 py-4 bg-[#163832]">
    <div class="flex items-center gap-10">
        <a href="/" class="text-[#83D5AB] font-bold text-xl">T<span class="text-white">icketwave</span></a>
        <div class="hidden md:flex gap-8 text-white text-sm">
            <a href="/eventos" class="text-[#83D5AB] font-semibold transition">Eventos</a>

        </div>
    </div>
    <div class="flex gap-3">
        @auth
            <a href="{{ route('dashboard') }}"
               class="bg-[#235347] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-[#0B2B26] transition">
                Mi cuenta
            </a>
        @else
            <a href="{{ route('login') }}"
               class="bg-[#235347] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-[#0B2B26] transition">
                Iniciar sesión
            </a>
            <a href="{{ route('register') }}"
               class="bg-[#8EDBB1] text-[#051F20] px-5 py-2 rounded-lg text-sm font-semibold hover:bg-[#83D5AB] transition">
                Registrarse
            </a>
        @endauth
    </div>
</nav>

{{-- Header de la página --}}
<div class="pt-24 pb-8 px-8" style="background: linear-gradient(135deg, #0d3d2a 0%, #163832 100%);">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-1">Todos los eventos</h1>
        <p class="text-sm" style="color:#8EB69B;">Encuentra y compra entradas para los mejores eventos</p>
    </div>
</div>

{{-- Listado con Livewire --}}
<section class="bg-[#051F20] min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-8">
        @livewire('eventos-list')
    </div>
</section>

{{-- Footer --}}
<footer class="bg-[#0B2B26] border-t border-[#235347] py-10">
    <div class="max-w-6xl mx-auto px-8 grid grid-cols-2 md:grid-cols-4 gap-6">
        <div>

    </div>
    <p class="text-center mt-6 text-[#8EB69B] text-sm">@ 2026 eventos, todos los derechos reservados</p>
</footer>

@livewireScripts
</body>
</html>