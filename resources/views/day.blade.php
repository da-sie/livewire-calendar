
<div
    @if($dragAndDropEnabled)
        x-data="livewireCalendarDay({
            componentId: '{{ $componentId }}',
            year: {{ $day->year }},
            month: {{ $day->month }},
            day: {{ $day->day }}
        })"
        x-on:dragenter.prevent="onDragEnter"
        x-on:dragleave="onDragLeave"
        x-on:dragover.prevent
        x-on:drop="onDrop"
    @endif
    class="flex-1 {{ ($viewMode ?? 'month') === 'day' ? 'min-w-full h-auto min-h-[24rem]' : 'h-40 lg:h-48' }} border border-gray-200 -mt-px -ml-px"
    style="{{ ($viewMode ?? 'month') !== 'day' ? 'min-width: 10rem;' : '' }}">

    <div
        class="w-full h-full"
        @if($dragAndDropEnabled)
            :class="dragOver ? '{{ $dragAndDropClasses }}' : ''"
        @endif
        id="{{ $componentId }}-{{ $day }}">

        <div
            @if($dayClickEnabled)
                wire:click="onDayClick({{ $day->year }}, {{ $day->month }}, {{ $day->day }})"
            @endif
            class="w-full h-full p-2 {{ $dayInMonth ? $isToday ? 'bg-yellow-100' : ' bg-white ' : 'bg-gray-100' }} flex flex-col">

            {{-- Number of Day --}}
            <div class="flex items-center">
                <p class="text-sm {{ $dayInMonth ? ' font-medium ' : '' }}">
                    {{ $day->format('j') }}
                </p>
                <p class="text-xs text-gray-600 ml-4">
                    @if($events->isNotEmpty())
                        {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
                    @endif
                </p>
            </div>

            {{-- Events --}}
            <div class="p-2 my-2 flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 grid-flow-row gap-2">
                    @foreach($events as $event)
                        <div
                            @if($dragAndDropEnabled)
                                draggable="true"
                                x-on:dragstart="$event.dataTransfer.setData('id', '{{ $event['id'] }}')"
                            @endif
                        >
                            @include($eventView, [
                                'event' => $event,
                                'isStart' => \Carbon\Carbon::parse($event['date'])->isSameDay($day),
                                'isEnd' => \Carbon\Carbon::parse($event['date_end'] ?? $event['date'])->isSameDay($day),
                            ])
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
