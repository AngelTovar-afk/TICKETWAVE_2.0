@if ($visible)
<div
  x-data="{ show: true }"
  x-init="setTimeout(() => { show = false; $wire.hide() }, 4000)"
  x-show="show"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 translate-y-2"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed bottom-4 right-4 z-50 flex items-center gap-3 px-5 py-3 rounded-lg shadow-lg text-white
            {{ $type === 'success' ? 'bg-green-500' :
               ($type === 'error'   ? 'bg-red-500'   : 'bg-yellow-500') }}">
  @if ($type === 'success')
  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
  </svg>
  @elseif ($type === 'error')
  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
  </svg>
  @endif

  <span class="text-sm font-medium">{{ $message }}</span>

  <button wire:click="hide" class="ml-2 opacity-70 hover:opacity-100">✕</button>
</div>
@endif