<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Планировщик для импорта карточек товаров
Schedule::command('wb:import-cards')
    ->hourly()
    ->onOneServer()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/import-cards.log'));

// Альтернативные варианты планировки:
// ->everyFourHours() - каждые 4 часа
// ->daily() - раз в день
// ->twiceDaily(8, 20) - дважды в день в 8:00 и 20:00
