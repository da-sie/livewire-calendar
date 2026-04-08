# Livewire Calendar

A Laravel Livewire calendar component with month, week, and day views. Supports multi-day events,
drag-and-drop, keyboard navigation, and full accessibility (ARIA).

> **Fork of [asantibanez/livewire-calendar](https://github.com/asantibanez/livewire-calendar)**, modernized for Livewire 4, Laravel 11-13, and PHP 8.2+.

## Requirements

- PHP 8.2+
- Laravel 11, 12, or 13
- Livewire 4
- TailwindCSS (for default styling)

## Installation

```bash
composer require da-sie/livewire-calendar
```

## Quick Start

Create a Livewire component that extends `LivewireCalendar`:

```bash
php artisan make:livewire AppointmentsCalendar
```

```php
use Asantibanez\LivewireCalendar\LivewireCalendar;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AppointmentsCalendar extends LivewireCalendar
{
    public function events(): Collection
    {
        return collect([
            [
                'id' => 1,
                'title' => 'Breakfast',
                'description' => 'Pancakes!',
                'date' => Carbon::today(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting',
                'description' => 'Work stuff',
                'date' => Carbon::tomorrow(),
            ],
        ]);
    }
}
```

Include in a Blade view:

```blade
<livewire:appointments-calendar />
```

Add the drag-and-drop scripts after Livewire's scripts:

```blade
@livewireCalendarScripts
```

## Event Format

Events are keyed arrays with these fields:

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `id` | mixed | yes | Unique identifier |
| `title` | string | yes | Display title |
| `description` | string | no | Description text |
| `date` | Carbon/string | yes | Start date |
| `date_end` | Carbon/string | no | End date (inclusive) for multi-day events |

### Multi-Day Events

Add `date_end` to make an event span multiple days:

```php
[
    'id' => 1,
    'title' => 'Conference',
    'description' => 'Annual tech conference',
    'date' => '2026-04-08',
    'date_end' => '2026-04-10',
]
```

Multi-day events display across all days in their range. The title shows on the start day;
continuation days show a compact indicator. Events without `date_end` behave as single-day events.

**Loading multi-day events:** If your events can start before the visible grid, use an overlap query:

```php
public function events(): Collection
{
    return Event::query()
        ->whereDate('date', '<=', $this->gridEndsAt)
        ->where(function ($q) {
            $q->whereDate('date_end', '>=', $this->gridStartsAt)
              ->orWhereNull('date_end');
        })
        ->get()
        ->map(fn (Event $e) => [
            'id' => $e->id,
            'title' => $e->title,
            'description' => $e->notes,
            'date' => $e->date,
            'date_end' => $e->date_end,
        ]);
}
```

## View Modes

The calendar supports three view modes: `month` (default), `week`, and `day`.

```blade
<livewire:appointments-calendar view-mode="week" />
```

### Switching Views

Call `setViewMode()` from your component or a custom view:

```php
$this->setViewMode('week');  // 'month', 'week', 'day'
```

Or from Blade via `wire:click`:

```blade
<button wire:click="setViewMode('month')">Month</button>
<button wire:click="setViewMode('week')">Week</button>
<button wire:click="setViewMode('day')">Day</button>
```

### Navigation

| Method | Description |
|--------|-------------|
| `goToPreviousMonth` | Navigate to previous month |
| `goToNextMonth` | Navigate to next month |
| `goToCurrentMonth` | Navigate to current month |
| `goToPreviousWeek` | Navigate to previous week |
| `goToNextWeek` | Navigate to next week |
| `goToPreviousDay` | Navigate to previous day |
| `goToNextDay` | Navigate to next day |

Use `before-calendar-view` or `after-calendar-view` to add navigation controls.

### Filtering Properties

Use these properties in `events()` to filter your data:

| Property | Description |
|----------|-------------|
| `$this->startsAt` | Start of the visible period |
| `$this->endsAt` | End of the visible period |
| `$this->gridStartsAt` | Start of the grid (may include adjacent days) |
| `$this->gridEndsAt` | End of the grid |

In day mode, `gridStartsAt` and `gridEndsAt` match the single visible day.
In week mode, they match the visible week.

## Customization

### Component Props

```blade
<livewire:appointments-calendar
    year="2026"
    month="4"
    view-mode="month"
    week-starts-at="1"
    event-view="custom.event"
    day-view="custom.day"
    day-of-week-view="custom.day-of-week"
    calendar-view="custom.calendar"
    before-calendar-view="custom.header"
    after-calendar-view="custom.footer"
    drag-and-drop-classes="border border-blue-400 border-4"
    :day-click-enabled="true"
    :event-click-enabled="true"
    :drag-and-drop-enabled="true"
    poll-millis="5000"
    poll-action="refreshEvents"
/>
```

### Publishing Views

```bash
php artisan vendor:publish --tag=livewire-calendar
```

Published views receive these variables:

**`day` view:** `$componentId`, `$day`, `$dayInMonth`, `$isToday`, `$events`, `$viewMode`

**`event` view:** `$event`, `$isStart`, `$isEnd`, `$eventClickEnabled`, `$dragAndDropEnabled`

### Publishing Assets

To use the JS file directly (instead of `@livewireCalendarScripts`):

```bash
php artisan vendor:publish --tag=livewire-calendar-assets
```

Then include manually:

```html
<script src="{{ asset('vendor/livewire-calendar/calendar.js') }}" defer></script>
```

## Interactions

Override these methods in your component:

```php
public function onDayClick($year, $month, $day)
{
    // Triggered when a day cell is clicked
}

public function onEventClick($eventId)
{
    // Triggered when an event is clicked
}

public function onEventDropped($eventId, $year, $month, $day)
{
    // Triggered when an event is dragged and dropped to another day
    // $year/$month/$day = target day
}
```

### Multi-Day Drag Source

For multi-day events, you may need to know which day segment was dragged.
Listen for the `calendar-event-dropped` Livewire event:

```php
use Livewire\Attributes\On;

#[On('calendar-event-dropped')]
public function handleEventDrop($eventId, $targetYear, $targetMonth, $targetDay, $sourceYear, $sourceMonth, $sourceDay)
{
    $event = Event::find($eventId);

    // Calculate how many days the event was moved
    $source = Carbon::create($sourceYear, $sourceMonth, $sourceDay);
    $target = Carbon::create($targetYear, $targetMonth, $targetDay);
    $diff = $source->diffInDays($target, false);

    // Shift both start and end dates by the same offset
    $event->update([
        'date' => Carbon::parse($event->date)->addDays($diff),
        'date_end' => $event->date_end ? Carbon::parse($event->date_end)->addDays($diff) : null,
    ]);
}
```

## Accessibility

The calendar includes full ARIA support:

- `role="grid"` on the calendar, `role="gridcell"` on day cells, `role="columnheader"` on day-of-week headers
- `aria-label` on days (e.g. "April 8, 2026") and events
- **Keyboard navigation:** Arrow keys move between day cells, Enter/Space triggers day click
- **Event keyboard:** Enter/Space triggers event click when enabled
- `aria-live="polite"` region for screen reader announcements
- `role="button"` and `tabindex="0"` on clickable events only

## Testing

```bash
composer test
```

## Credits

- [Andres Santibanez](https://github.com/asantibanez) (original author)
- [All Contributors](../../contributors)

## License

MIT. See [LICENSE.md](LICENSE.md).
