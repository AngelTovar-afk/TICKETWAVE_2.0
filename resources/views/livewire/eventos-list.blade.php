<div>
    {{-- Filtros --}}
    <div class="flex flex-col md:flex-row gap-4 mb-8">
        <input
            type="text"
            wire:model.live="busqueda"
            placeholder="Buscar eventos..."
            class="flex-1 px-5 py-3 rounded-lg bg-white/10 border border-[#235347] text-white placeholder-[#8EB69B] focus:outline-none focus:border-[#83D5AB]"
        />
        <select
            wire:model.live="categoria"
            class="px-5 py-3 rounded-lg bg-[#0B2B26] border border-[#235347] text-white focus:outline-none focus:border-[#83D5AB]">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
    </div>

    {{-- Grid de eventos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventos as $evento)
            <div class="bg-[#0B2B26] rounded-xl overflow-hidden border border-[#235347] hover:border-[#83D5AB] transition">
                <div class="relative">
                    <img
                        src="{{ $evento->image_url ?? 'https://via.placeholder.com/400x200/051F20/83D5AB?text=TicketWave' }}"
                        alt="{{ $evento->name }}"
                        class="w-full h-48 object-cover"
                    />
                    @if($evento->ticketTypes->sum('quantity_available') <= 0)
                        <span class="absolute top-2 right-2 bg-red-600 text-white text-xs px-3 py-1 rounded-full font-semibold">
                            Agotado
                        </span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-1">{{ $evento->name }}</h3>
                    <p class="text-[#8EB69B] text-sm mb-2">
                        {{ $evento->event_date ? \Carbon\Carbon::parse($evento->event_date)->format('d M Y') : 'Fecha por confirmar' }}
                    </p>
                    <p class="text-[#83D5AB] font-semibold mb-4">
                        @if($evento->ticketTypes->isNotEmpty())
                            Desde ${{ number_format($evento->ticketTypes->min('price'), 2) }}
                        @else
                            Precio por confirmar
                        @endif
                    </p>
                    @if($evento->ticketTypes->sum('quantity_available') > 0)
                        <a href="{{ route('eventos.show', $evento) }}"
                           class="block w-full bg-[#8EDBB1] text-[#051F20] py-2 rounded-lg font-semibold hover:bg-[#83D5AB] transition text-center">
                            Ver evento
                        </a>
                    @else
                        <button disabled class="w-full bg-gray-600 text-gray-400 py-2 rounded-lg font-semibold cursor-not-allowed">
                            Agotado
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-[#8EB69B] py-12">
                No se encontraron eventos.
            </div>
        @endforelse
    </div>
</div>