<?php

use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\TicketController;
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

Artisan::command('analyze', function () {
    $command = 'vendor/bin/phpstan analyse --ansi --memory-limit=2G';
    $this->info('Analyze with phpstan');
    passthru($command);
});

Schedule::command('backup:run')
    ->when(function () {
        return true;
    })
    ->after(function () {
        return true;
    })->daily();

Schedule::call(function () {
    return (new TicketController())->updateStatusTicket();
})->daily();
