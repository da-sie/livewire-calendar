<div
    @if($pollMillis !== null && $pollAction !== null)
        wire:poll.{{ $pollMillis }}ms="{{ $pollAction }}"
    @elseif($pollMillis !== null)
        wire:poll.{{ $pollMillis }}ms
    @endif
>
    <div>
        @includeIf($beforeCalendarView)
    </div>

    <div class="flex">
        <div class="overflow-x-auto w-full">
            <div class="inline-block min-w-full overflow-hidden" role="grid" aria-label="Calendar">

                @if($viewMode !== 'day')
                    <div class="w-full flex flex-row" role="row">
                        @foreach($grid->first() as $day)
                            @include($dayOfWeekView, ['day' => $day])
                        @endforeach
                    </div>
                @endif

                @foreach($grid as $week)
                    <div class="w-full flex flex-row" role="row">
                        @foreach($week as $day)
                            @include($dayView, [
                                    'componentId' => $componentId,
                                    'day' => $day,
                                    'dayInMonth' => $viewMode !== 'month' || $day->isSameMonth($startsAt),
                                    'isToday' => $day->isToday(),
                                    'events' => $getEventsForDay($day, $events),
                                    'viewMode' => $viewMode,
                                ])
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div>
        @includeIf($afterCalendarView)
    </div>

    <div aria-live="polite" class="sr-only"></div>
</div>
