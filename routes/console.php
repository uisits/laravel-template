<?php

use Illuminate\Support\Facades\Schedule;
use Laravel\Telescope\Console\PruneCommand;

// Telescope Prune
Schedule::command(
    PruneCommand::class,
    ['--hours' => 72]
)->dailyAt('02:00');

Schedule::call(function () {
    DB::statement('OPTIMIZE TABLE telescope_entries');
    $this->info('Table `telescope_entries` successfully optimized.');
})
    ->name('optimize:telescope-tables')
    ->dailyAt('03:00')
    ->withoutOverlapping();
