<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('sqlite:generate', function () {
    $database = database_path('database.sqlite');
    file_put_contents($database, '');
    $this->info("SQLite database file created at {$database}");
});

Schedule::command('backup:run')
    ->when(function () {
        return true;
    })
    ->after(function () {
        return true;
    })->daily();
