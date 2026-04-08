<?php

namespace Asantibanez\LivewireCalendar;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class LivewireCalendar extends Component
{
    public Carbon $startsAt;
    public Carbon $endsAt;

    public Carbon $gridStartsAt;
    public Carbon $gridEndsAt;

    public int $weekStartsAt;
    public int $weekEndsAt;

    public string $calendarView;
    public string $dayView;
    public string $eventView;
    public string $dayOfWeekView;

    public string $dragAndDropClasses;

    public ?string $beforeCalendarView;
    public ?string $afterCalendarView;

    public ?int $pollMillis;
    public ?string $pollAction;

    public bool $dragAndDropEnabled;
    public bool $dayClickEnabled;
    public bool $eventClickEnabled;

    public string $viewMode = 'month';

    public function mount($initialYear = null,
                          $initialMonth = null,
                          $weekStartsAt = null,
                          $calendarView = null,
                          $dayView = null,
                          $eventView = null,
                          $dayOfWeekView = null,
                          $dragAndDropClasses = null,
                          $beforeCalendarView = null,
                          $afterCalendarView = null,
                          $pollMillis = null,
                          $pollAction = null,
                          $dragAndDropEnabled = true,
                          $dayClickEnabled = true,
                          $eventClickEnabled = true,
                          $extras = [])
    {
        $this->weekStartsAt = $weekStartsAt ?? Carbon::SUNDAY;
        $this->weekEndsAt = $this->weekStartsAt == Carbon::SUNDAY
            ? Carbon::SATURDAY
            : collect([0,1,2,3,4,5,6])->get($this->weekStartsAt + 6 - 7)
        ;

        $initialYear = $initialYear ?? Carbon::today()->year;
        $initialMonth = $initialMonth ?? Carbon::today()->month;

        $this->startsAt = Carbon::createFromDate($initialYear, $initialMonth, 1)->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

        $this->calculateGridStartsEnds();

        $this->setupViews($calendarView, $dayView, $eventView, $dayOfWeekView, $beforeCalendarView, $afterCalendarView);

        $this->setupPoll($pollMillis, $pollAction);

        $this->dragAndDropEnabled = $dragAndDropEnabled;
        $this->dragAndDropClasses = $dragAndDropClasses ?? 'border border-blue-400 border-4';

        $this->dayClickEnabled = $dayClickEnabled;
        $this->eventClickEnabled = $eventClickEnabled;

        $this->recalculateBounds();

        $this->afterMount($extras);
    }

    public function afterMount($extras = [])
    {
        //
    }

    public function setupViews($calendarView = null,
                               $dayView = null,
                               $eventView = null,
                               $dayOfWeekView = null,
                               $beforeCalendarView = null,
                               $afterCalendarView = null)
    {
        $this->calendarView = $calendarView ?? 'livewire-calendar::calendar';
        $this->dayView = $dayView ?? 'livewire-calendar::day';
        $this->eventView = $eventView ?? 'livewire-calendar::event';
        $this->dayOfWeekView = $dayOfWeekView ?? 'livewire-calendar::day-of-week';

        $this->beforeCalendarView = $beforeCalendarView ?? null;
        $this->afterCalendarView = $afterCalendarView ?? null;
    }

    public function setupPoll($pollMillis, $pollAction)
    {
        $this->pollMillis = $pollMillis;
        $this->pollAction = $pollAction;
    }

    public function goToPreviousMonth()
    {
        $this->startsAt->subMonthNoOverflow();
        $this->endsAt->subMonthNoOverflow();

        $this->calculateGridStartsEnds();
    }

    public function goToNextMonth()
    {
        $this->startsAt->addMonthNoOverflow();
        $this->endsAt->addMonthNoOverflow();

        $this->calculateGridStartsEnds();
    }

    public function goToCurrentMonth()
    {
        $this->startsAt = Carbon::today()->startOfMonth()->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

        $this->calculateGridStartsEnds();
    }

    public function goToPreviousWeek()
    {
        $this->startsAt->subWeek();
        $this->endsAt = $this->startsAt->clone()->endOfWeek($this->weekEndsAt)->startOfDay();
        $this->calculateGridStartsEnds();
    }

    public function goToNextWeek()
    {
        $this->startsAt->addWeek();
        $this->endsAt = $this->startsAt->clone()->endOfWeek($this->weekEndsAt)->startOfDay();
        $this->calculateGridStartsEnds();
    }

    public function goToPreviousDay()
    {
        $this->startsAt->subDay();
        $this->endsAt = $this->startsAt->clone();
        $this->gridStartsAt = $this->startsAt->clone();
        $this->gridEndsAt = $this->endsAt->clone();
    }

    public function goToNextDay()
    {
        $this->startsAt->addDay();
        $this->endsAt = $this->startsAt->clone();
        $this->gridStartsAt = $this->startsAt->clone();
        $this->gridEndsAt = $this->endsAt->clone();
    }

    public function setViewMode(string $mode)
    {
        $this->viewMode = $mode;
        $this->recalculateBounds();
    }

    public function updatedViewMode()
    {
        $this->recalculateBounds();
    }

    private function recalculateBounds()
    {
        match ($this->viewMode) {
            'week' => $this->recalculateWeek(),
            'day' => $this->recalculateDay(),
            default => $this->recalculateMonth(),
        };
    }

    private function recalculateMonth()
    {
        $this->startsAt = $this->startsAt->clone()->startOfMonth()->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();
        $this->calculateGridStartsEnds();
    }

    private function recalculateWeek()
    {
        $this->startsAt = $this->startsAt->clone()->startOfWeek($this->weekStartsAt)->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfWeek($this->weekEndsAt)->startOfDay();
        $this->calculateGridStartsEnds();
    }

    private function recalculateDay()
    {
        $this->endsAt = $this->startsAt->clone();
        $this->gridStartsAt = $this->startsAt->clone();
        $this->gridEndsAt = $this->endsAt->clone();
    }

    public function calculateGridStartsEnds()
    {
        $this->gridStartsAt = $this->startsAt->clone()->startOfWeek($this->weekStartsAt);
        $this->gridEndsAt = $this->endsAt->clone()->endOfWeek($this->weekEndsAt);
    }

    public function grid(): Collection
    {
        return match ($this->viewMode) {
            'week' => $this->weekGrid(),
            'day' => $this->dayGrid(),
            default => $this->monthGrid(),
        };
    }

    public function weekGrid(): Collection
    {
        $start = $this->startsAt->clone()->startOfWeek($this->weekStartsAt);
        $days = collect();
        for ($i = 0; $i < 7; $i++) {
            $days->push($start->clone()->addDays($i));
        }

        return collect([$days]);
    }

    public function dayGrid(): Collection
    {
        return collect([collect([$this->startsAt->clone()])]);
    }

    /**
     * @throws Exception
     */
    public function monthGrid()
    {
        $firstDayOfGrid = $this->gridStartsAt;
        $lastDayOfGrid = $this->gridEndsAt;

        $numbersOfWeeks = ceil($lastDayOfGrid->diffInWeeks($firstDayOfGrid, true));
        $days = ceil($lastDayOfGrid->diffInDays($firstDayOfGrid, true));

        if ($days % 7 != 0) {
            throw new Exception("Livewire Calendar not correctly configured. Check initial inputs.");
        }

        $monthGrid = collect();
        $currentDay = $firstDayOfGrid->clone();

        while(!$currentDay->greaterThan($lastDayOfGrid)) {
            $monthGrid->push($currentDay->clone());
            $currentDay->addDay();
        }

        $monthGrid = $monthGrid->chunk(7);
        if ($numbersOfWeeks != $monthGrid->count()) {
            throw new Exception("Livewire Calendar calculated wrong number of weeks. Sorry :(");
        }

        return $monthGrid;
    }

    public function events() : Collection
    {
        return collect();
    }

    public function getEventsForDay($day, Collection $events) : Collection
    {
        return $events
            ->filter(function ($event) use ($day) {
                $start = Carbon::parse($event['date'])->startOfDay();
                $end = isset($event['date_end'])
                    ? Carbon::parse($event['date_end'])->startOfDay()
                    : $start;

                return $day->between($start, $end);
            });
    }

    public function onDayClick($year, $month, $day)
    {
        //
    }

    public function onEventClick($eventId)
    {
        //
    }

    public function onEventDropped($eventId, $year, $month, $day)
    {
        //
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function render()
    {
        $events = $this->events();

        return view($this->calendarView)
            ->with([
                'componentId' => $this->getId(),
                'grid' => $this->grid(),
                'monthGrid' => $this->grid(),
                'events' => $events,
                'viewMode' => $this->viewMode,
                'getEventsForDay' => function ($day) use ($events) {
                    return $this->getEventsForDay($day, $events);
                }
            ]);
    }
}
