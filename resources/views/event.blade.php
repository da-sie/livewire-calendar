@php
    $isStart = $isStart ?? true;
    $isEnd = $isEnd ?? true;
@endphp
<div
    @if($eventClickEnabled)
        role="button"
        tabindex="0"
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
        x-on:keydown.enter.stop="$wire.onEventClick('{{ $event['id'] }}')"
        x-on:keydown.space.prevent.stop="$wire.onEventClick('{{ $event['id'] }}')"
    @endif
    aria-label="{{ $event['title'] }}"
    @if($dragAndDropEnabled)
        aria-grabbed="false"
    @endif
    class="{{ $isStart ? 'rounded-l-lg' : 'rounded-l-none -ml-px' }} {{ $isEnd ? 'rounded-r-lg' : 'rounded-r-none -mr-px' }} bg-white border py-2 px-3 shadow-md cursor-pointer">

    @if($isStart)
        <p class="text-sm font-medium">
            {{ $event['title'] }}
        </p>
        <p class="mt-2 text-xs">
            {{ $event['description'] ?? 'No description' }}
        </p>
    @else
        <p class="text-xs text-gray-400 truncate">
            {{ $event['title'] }}
        </p>
    @endif
</div>
