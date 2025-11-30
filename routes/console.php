<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Ejecutar todos los días a las 2:00 AM
Schedule::command('app:deactivate-expired-memberships')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();
