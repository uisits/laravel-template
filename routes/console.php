<?php

use Illuminate\Support\Facades\Schedule;

// Telescope Prune
Schedule::command(
    \Laravel\Telescope\Console\PruneCommand::class,
    ['--hours' => 72]
)->dailyAt('02:00');

Schedule::call(function () {
    DB::statement('OPTIMIZE TABLE telescope_entries');
    $this->info('Table `telescope_entries` successfully optimized.');
})->dailyAt('03:00')->withoutOverlapping();