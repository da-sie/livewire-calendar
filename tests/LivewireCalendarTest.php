<?php

namespace Omnia\LivewireCalendar\Tests;

use Omnia\LivewireCalendar\LivewireCalendar;
use Livewire\Livewire;

class LivewireCalendarTest extends TestCase
{
    private function createComponent($parameters = [])
    {
        return Livewire::test(LivewireCalendar::class, $parameters);
    }

    public function test_can_build_component()
    {
        $component = $this->createComponent([]);

        $this->assertNotNull($component);
    }

    public function test_can_navigate_to_next_month()
    {
        $component = $this->createComponent([]);

        $component->call('goToNextMonth');

        $this->assertEquals(
            today()->startOfMonth()->addMonthNoOverflow(),
            $component->get('startsAt')
        );

        $this->assertEquals(
            today()->endOfMonth()->startOfDay()->addMonthNoOverflow(),
            $component->get('endsAt')
        );
    }

    public function test_can_navigate_to_previous_month()
    {
        $component = $this->createComponent([]);

        $component->call('goToPreviousMonth');

        $this->assertEquals(
            today()->startOfMonth()->subMonthNoOverflow(),
            $component->get('startsAt')
        );

        $this->assertEquals(
            today()->endOfMonth()->startOfDay()->subMonthNoOverflow(),
            $component->get('endsAt')
        );
    }

    public function test_can_navigate_to_current_month()
    {
        $component = $this->createComponent([]);

        $component->call('goToPreviousMonth');
        $component->call('goToPreviousMonth');
        $component->call('goToPreviousMonth');

        $component->call('goToCurrentMonth');

        $this->assertEquals(
            today()->startOfMonth(),
            $component->get('startsAt')
        );

        $this->assertEquals(
            today()->endOfMonth()->startOfDay(),
            $component->get('endsAt')
        );
    }
}
