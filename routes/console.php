<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('sqlite:create', function () {
    $database = database_path('database.sqlite');
    file_put_contents($database, '');
    $this->info("SQLite database file created at {$database}");
});

Artisan::command('env:create', function () {
    $env = file_put_contents('.env', '');
    $this->info(".env database file created at {$env}");
});
