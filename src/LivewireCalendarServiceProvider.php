<?php

namespace Asantibanez\LivewireCalendar;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LivewireCalendarServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-calendar');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/livewire-calendar'),
            ], 'livewire-calendar');

            $this->publishes([
                __DIR__.'/../resources/js' => public_path('vendor/livewire-calendar'),
            ], 'livewire-calendar-assets');
        }

        Blade::directive('livewireCalendarScripts', function () {
            $js = file_get_contents(__DIR__.'/../resources/js/calendar.js');

            return "<script>{$js}</script>";
        });
    }
}
