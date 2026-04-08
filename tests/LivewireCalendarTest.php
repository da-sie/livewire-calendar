<?php

namespace Asantibanez\LivewireCalendar\Tests;

use Asantibanez\LivewireCalendar\LivewireCalendar;
use Carbon\Carbon;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;

class LivewireCalendarTest extends TestCase
{
    private function createComponent(array $parameters = [])
    {
        return Livewire::test(LivewireCalendar::class, $parameters);
    }

    #[Test]
    public function can_build_component()
    {
        //Arrange

        //Act
        $component = $this->createComponent([]);

        //Assert
        $this->assertNotNull($component);
    }

    #[Test]
    public function can_navigate_to_next_month()
    {
        //Arrange
        $component = $this->createComponent([]);

        //Act
        $component->call('goToNextMonth');

        //Assert
        $this->assertEquals(
            today()->startOfMonth()->addMonthNoOverflow(),
            $component->get('startsAt')
        );

        $this->assertEquals(
            today()->endOfMonth()->startOfDay()->addMonthNoOverflow(),
            $component->get('endsAt')
        );
    }

    #[Test]
    public function can_navigate_to_previous_month()
    {
        //Arrange
        $component = $this->createComponent([]);

        //Act
        $component->call('goToPreviousMonth');

        //Assert
        $this->assertEquals(
            today()->startOfMonth()->subMonthNoOverflow(),
            $component->get('startsAt')
        );

        $this->assertEquals(
            today()->endOfMonth()->startOfDay()->subMonthNoOverflow(),
            $component->get('endsAt')
        );
    }

    #[Test]
    public function can_navigate_to_current_month()
    {
        //Arrange
        $component = $this->createComponent([]);

        $component->call('goToPreviousMonth');
        $component->call('goToPreviousMonth');
        $component->call('goToPreviousMonth');

        //Act
        $component->call('goToCurrentMonth');

        //Assert
        $this->assertEquals(
            today()->startOfMonth(),
            $component->get('startsAt')
        );

        $this->assertEquals(
            today()->endOfMonth()->startOfDay(),
            $component->get('endsAt')
        );
    }

    #[Test]
    public function single_day_event_appears_on_its_day()
    {
        $calendar = new LivewireCalendar();
        $events = collect([
            ['id' => 1, 'title' => 'Test', 'date' => '2026-04-08'],
        ]);

        $result = $calendar->getEventsForDay(Carbon::parse('2026-04-08'), $events);
        $this->assertCount(1, $result);

        $result = $calendar->getEventsForDay(Carbon::parse('2026-04-09'), $events);
        $this->assertCount(0, $result);
    }

    #[Test]
    public function multi_day_event_appears_on_all_days_in_range()
    {
        $calendar = new LivewireCalendar();
        $events = collect([
            ['id' => 1, 'title' => 'Conference', 'date' => '2026-04-08', 'date_end' => '2026-04-10'],
        ]);

        $this->assertCount(1, $calendar->getEventsForDay(Carbon::parse('2026-04-08'), $events));
        $this->assertCount(1, $calendar->getEventsForDay(Carbon::parse('2026-04-09'), $events));
        $this->assertCount(1, $calendar->getEventsForDay(Carbon::parse('2026-04-10'), $events));
        $this->assertCount(0, $calendar->getEventsForDay(Carbon::parse('2026-04-07'), $events));
        $this->assertCount(0, $calendar->getEventsForDay(Carbon::parse('2026-04-11'), $events));
    }

    #[Test]
    public function event_without_date_end_behaves_as_single_day()
    {
        $calendar = new LivewireCalendar();
        $events = collect([
            ['id' => 1, 'title' => 'Quick', 'date' => '2026-04-08'],
        ]);

        $this->assertCount(1, $calendar->getEventsForDay(Carbon::parse('2026-04-08'), $events));
        $this->assertCount(0, $calendar->getEventsForDay(Carbon::parse('2026-04-09'), $events));
    }

    #[Test]
    public function week_grid_returns_one_week_of_seven_days()
    {
        $calendar = new LivewireCalendar();
        $calendar->mount(initialYear: 2026, initialMonth: 4);
        $calendar->viewMode = 'week';

        $grid = $calendar->grid();
        $this->assertCount(1, $grid);
        $this->assertCount(7, $grid->first());
    }

    #[Test]
    public function day_grid_returns_one_day()
    {
        $calendar = new LivewireCalendar();
        $calendar->mount(initialYear: 2026, initialMonth: 4);
        $calendar->viewMode = 'day';

        $grid = $calendar->grid();
        $this->assertCount(1, $grid);
        $this->assertCount(1, $grid->first());
    }

    #[Test]
    public function can_navigate_weeks()
    {
        $component = $this->createComponent([]);
        $component->set('viewMode', 'week');

        // After setting week mode, startsAt aligns to week start
        $weekStart = today()->startOfMonth()->startOfWeek(Carbon::SUNDAY);

        $component->call('goToNextWeek');

        $this->assertEquals(
            $weekStart->addWeek(),
            $component->get('startsAt')
        );
    }

    #[Test]
    public function can_navigate_days()
    {
        $component = $this->createComponent([]);
        $component->set('viewMode', 'day');

        $startBefore = today()->startOfMonth();

        $component->call('goToNextDay');

        $this->assertEquals(
            $startBefore->addDay(),
            $component->get('startsAt')
        );
    }

    #[Test]
    public function on_event_dropped_keeps_original_signature()
    {
        $component = $this->createComponent([]);
        $component->call('onEventDropped', '1', 2026, 4, 15);
        $this->assertTrue(true);
    }

    #[Test]
    public function can_switch_view_mode()
    {
        $component = $this->createComponent([]);

        $component->call('setViewMode', 'week');
        $this->assertEquals('week', $component->get('viewMode'));

        $component->call('setViewMode', 'day');
        $this->assertEquals('day', $component->get('viewMode'));

        $component->call('setViewMode', 'month');
        $this->assertEquals('month', $component->get('viewMode'));
    }
}
