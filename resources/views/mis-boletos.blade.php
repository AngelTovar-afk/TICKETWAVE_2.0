<x-comprador-layout>
@php
    $todosLosItems = collect();
    foreach ($orders as $order) {
        foreach ($order->orderItems as $item) {
            $fecha = \Carbon\Carbon::parse($item->ticketType->event->event_date);
            $todosLosItems->push([
                'nombre'    => $item->ticketType->event->name,
                'tipo'      => $item->ticketType->name,
                'fecha'     => $fecha->locale('es')->translatedFormat('d \d\e F \d\e Y'),
                'status'    => $order->status,
                'esProximo' => $fecha->isFuture() && $order->status !== 'cancelled',
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

            {{-- Boletos activos --}}
            <div class="rounded-2xl" style="background:#122b20; border:1px solid #1e4a32; padding:28px 28px 32px;">
                <p style="color:#7aab90; font-size:15px; font-weight:500; margin-bottom:12px;">Boletos activos</p>
                <p style="color:#7fffc4; font-size:36px; font-weight:700; line-height:1;">{{ $boletosActivos }}</p>
            </div>

            {{-- Total gastado --}}
            <div class="rounded-2xl" style="background:#122b20; border:1px solid #1e4a32; padding:28px 28px 32px;">
                <p style="color:#7aab90; font-size:15px; font-weight:500; margin-bottom:12px;">Total gastado</p>
                <p style="color:#e8f5ee; font-size:36px; font-weight:700; line-height:1;">${{ number_format($totalGastado, 0) }}</p>
                <p style="color:#7aab90; font-size:11px; margin-top:10px; font-family:monospace; letter-spacing:.08em;">este mes</p>
            </div>
        </div>

        {{-- Próximos eventos --}}
        <div class="rounded-2xl overflow-hidden" style="background:#122b20; border:1px solid #1e4a32; margin:0 24px 24px;">

            <div style="padding:24px 32px; border-bottom:1px solid #1e4a32;">
                <h2 style="color:#e8f5ee; font-size:20px; font-weight:600;">Próximos eventos</h2>
            </div>

            {{-- Sin boletos --}}
            <template x-if="proximos.length === 0">
                <div class="px-8 py-16 text-center">
                    <p class="text-3xl mb-3">🎫</p>
                    <p class="font-semibold mb-1" style="color:#e8f5ee;">Aún no tienes boletos activos</p>
                    <p class="text-sm mb-5" style="color:#7aab90;">Explora los eventos disponibles y compra tus entradas</p>
                    <a href="{{ url('/') }}"
                       class="inline-block px-6 py-2 rounded-lg font-semibold text-sm"
                       style="background:#7fffc4; color:#0d2a1f;">
                        Ver eventos
                    </a>
                </div>
            </template>

            {{-- Primeros 3 próximos — cada uno en su propia inner card --}}
            <div style="padding:16px 20px; display:flex; flex-direction:column; gap:10px;">
                <template x-for="(item, i) in proximos.slice(0, 3)" :key="i">
                    <div class="flex items-center gap-4"
                         style="background:#0a2018; border:1px solid #1e4a32; border-radius:12px; padding:16px 20px;">

                        {{-- Thumbnail --}}
                        <div style="width:56px; height:56px; min-width:56px; background:#1a3d2e; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                            <svg style="width:28px; height:28px; color:#7fffc4;" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3v10.55A4 4 0 1 0 14 17V7h4V3h-6z"/>
                            </svg>
                        </div>

                        {{-- Info --}}
                        <div style="flex:1; min-width:0;">
                            <p style="font-weight:700; font-size:15px; color:#e8f5ee; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="item.nombre"></p>
                            <p style="font-size:13px; color:#7fffc4; margin-top:2px;" x-text="item.tipo"></p>
                            <p style="font-size:12px; color:#7aab90; margin-top:2px;" x-text="item.fecha"></p>
                        </div>

                        {{-- Badge --}}
                        <span style="font-size:11px; font-weight:700; padding:6px 16px; border-radius:999px; background:rgba(127,255,196,0.1); color:#7fffc4; border:1px solid #7fffc4; white-space:nowrap; letter-spacing:.05em; flex-shrink:0;">
                            PRÓXIMO
                        </span>
                    </div>
                </template>
            </div>

            {{-- Ver más --}}
            <template x-if="items.length > 0">
                <div class="py-6 text-center">
                    <button @click="vista = 'completa'; tabActivo = 'todos'"
                            class="flex flex-col items-center gap-1.5 mx-auto transition hover:opacity-70"
                            style="color:#7fffc4; background:none; border:none; cursor:pointer;">
                        <span class="text-sm font-semibold">Ver más</span>
                        <span class="w-8 h-8 rounded-full flex items-center justify-center"
                              style="border:1px solid #7fffc4;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- ══ ESTADO 2: Lista completa ══ --}}
    <div x-show="vista === 'completa'" x-transition>

        {{-- Tabs --}}
        <div class="flex gap-3 mb-6" style="padding:24px 24px 0;">
            @foreach(['todos' => 'Todos', 'proximos' => 'Próximos', 'usados' => 'Usados'] as $key => $label)
                <button @click="tabActivo = '{{ $key }}'"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition-all"
                        :style="tabActivo === '{{ $key }}'
                            ? 'background:rgba(127,255,196,0.12); color:#7fffc4; border:1px solid #7fffc4;'
                            : 'background:transparent; color:#7aab90; border:1px solid #1e4a32;'">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Lista --}}
        <div class="rounded-2xl overflow-hidden" style="background:#122b20; border:1px solid #1e4a32; margin:0 24px;">

            {{-- Vacío --}}
            <template x-if="itemsFiltrados.length === 0">
                <div class="px-8 py-16 text-center">
                    <p class="text-3xl mb-3">🎫</p>
                    <p class="font-semibold mb-1" style="color:#e8f5ee;">No hay boletos en esta categoría</p>
                    <p class="text-sm" style="color:#7aab90;">Prueba con otro filtro</p>
                </div>
            </template>

            {{-- Filas — cada una en su propia inner card --}}
            <div style="padding:16px 20px; display:flex; flex-direction:column; gap:10px;">
                <template x-for="(item, i) in itemsFiltrados" :key="i">
                    <div class="flex items-center gap-4"
                         :style="item.status === 'cancelled'
                             ? 'background:#0a2018; border:1px solid #1e4a32; border-radius:12px; padding:16px 20px; opacity:0.5;'
                             : 'background:#0a2018; border:1px solid #1e4a32; border-radius:12px; padding:16px 20px;'">

                        {{-- Thumbnail --}}
                        <div style="width:56px; height:56px; min-width:56px; background:#1a3d2e; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                            <svg style="width:28px; height:28px; color:#7fffc4;" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3v10.55A4 4 0 1 0 14 17V7h4V3h-6z"/>
                            </svg>
                        </div>

                        {{-- Info --}}
                        <div style="flex:1; min-width:0;" :class="item.status === 'cancelled' ? 'line-through' : ''">
                            <p style="font-weight:700; font-size:15px; color:#e8f5ee; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="item.nombre"></p>
                            <p style="font-size:13px; color:#7fffc4; margin-top:2px;" x-text="item.tipo"></p>
                            <p style="font-size:12px; color:#7aab90; margin-top:2px;" x-text="item.fecha"></p>
                        </div>

                        {{-- Badges --}}
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
                    </div>
                </template>
            </div>
        </div>

        {{-- Volver --}}
        <div class="mt-6 text-center">
            <button @click="vista = 'resumen'"
                    class="flex items-center gap-2 mx-auto text-sm font-semibold transition hover:opacity-70"
                    style="color:#7aab90; background:none; border:none; cursor:pointer;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
                Ver resumen
            </button>
        </div>
    </div>

</div>
</x-comprador-layout>