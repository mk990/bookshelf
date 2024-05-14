<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_DEBUG')) {
            DB::listen(function ($sql) {
                // black list tables
                $blackListTables = ['cache', 'pulse_entries', 'sessions', 'pulse_aggregates', 'pulse_values', 'jobs'];
                foreach ($blackListTables as $table) {
                    if (str_contains($sql->sql, $table)) {
                        return;
                    }
                }
                Log::info($sql->sql);
                Log::info($sql->bindings);
                Log::info($sql->time);
            });
        }
    }
}
