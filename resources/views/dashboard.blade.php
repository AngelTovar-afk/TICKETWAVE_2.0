<x-comprador-layout>

    {{-- Saludo --}}
    <h1 class="text-2xl font-bold text-white mb-6">
        Hola, {{ explode(' ', auth()->user()->name)[0] }} 👋
    </h1>

    {{-- HERO BANNER --}}
    <div class="relative rounded-2xl overflow-hidden mb-8" style="min-height:260px; background: linear-gradient(135deg, #0d3d2a 0%, #1a5c3a 50%, #0d2d1f 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1200&q=80'); background-size:cover; background-position:center;"></div>
        <div class="relative z-10 p-10 flex flex-col justify-center h-full" style="min-height:260px;">
            <h2 class="text-4xl font-bold text-white mb-2 leading-tight">
                Vive experiencias<br>
                <span style="color:#4ade80;">inolvidables</span>
            </h2>
            <p class="mb-6 text-sm" style="color:#94a3b8; max-width:320px;">
                Encuentra y compra entradas para los mejores eventos cerca de ti.
            </p>
            <a href="{{ route('eventos.index') }}"
               class="inline-block px-6 py-2.5 rounded-lg font-semibold text-sm transition w-fit"
               style="background-color:#4ade80; color:#0d1f1a;">
                Explorar eventos
            </a>
        </div>
    </div>

    {{-- CATEGORÍAS --}}
    <section class="mb-8">
        <h3 class="text-lg font-semibold text-white mb-3">Explora por categorías</h3>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition"
               style="{{ !$categoria ? 'background-color:#2d6a4f; color:#fff;' : 'background-color:#1a3328; color:#94a3b8; border:1px solid #2d6a4f;' }}">
                Todos
            </a>
            @foreach($categorias as $cat)
                <a href="{{ route('dashboard', ['categoria' => $cat['slug']]) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition"
                   style="{{ $categoria === $cat['slug'] ? 'background-color:#2d6a4f; color:#fff;' : 'background-color:#1a3328; color:#94a3b8; border:1px solid #2d6a4f;' }}">
                    {{ $cat['emoji'] }} {{ $cat['label'] }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- PRÓXIMOS EVENTOS --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Próximos Eventos</h3>
            <a href="#" class="text-sm" style="color:#4ade80;">Ver todas</a>
        </div>

        @if($eventos->isEmpty())
            <div class="rounded-xl py-16 text-center" style="background-color:#051F20; border:1px dashed #2d6a4f;">
                <p class="text-4xl mb-3">🎟️</p>
                <p class="font-medium text-white">No hay eventos disponibles por el momento</p>
                <p class="text-sm mt-1" style="color:#94a3b8;">Vuelve pronto para ver nuevos eventos</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($eventos as $evento)
                    @php
                        $precio = $evento->ticketTypes->min('price');
                    @endphp
                    <a href="{{ route('eventos.show', $evento) }}"
                       class="rounded-xl overflow-hidden transition hover:scale-105 block"
                       style="background-color:#051F20; border:1px solid #2d6a4f;">
                        {{-- Imagen --}}
                        <div class="h-36 flex items-center justify-center relative"
                             style="background: linear-gradient(135deg, #1a3d2e, #2d6a4f);">
                            @if($evento->image_url)
                                <img src="{{ $evento->image_url }}" alt="{{ $evento->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-5xl">🎵</span>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="p-3">
                            <p class="font-semibold text-white text-sm truncate">{{ $evento->name }}</p>
                            <p class="text-xs mt-1" style="color:#94a3b8;">
                                {{ \Carbon\Carbon::parse($evento->event_date)->format('d M Y') }}
                                @if($evento->venue)
                                    · {{ $evento->venue->name }}
                                @endif
                            </p>
                            @if($precio)
                                <p class="text-xs mt-2 font-semibold" style="color:#4ade80;">
                                    Desde ${{ number_format($precio, 0, '.', ',') }} MXN
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

</x-comprador-layout>