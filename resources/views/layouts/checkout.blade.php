<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $titulo ?? 'Checkout — TicketWave' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="bg-[#051F20] font-sans min-h-screen">

  <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-8 py-4 bg-[#163832] border-b border-[#235347]">
    <a href="/" class="text-[#83D5AB] font-bold text-xl">T<span class="text-white">icketwave</span></a>
  </nav>

  <div class="pt-20 pb-12">
    {{ $slot }}
  </div>

  @livewireScripts
</body>

</html>