<?php

use Illuminate\Support\Facades\Schedule;

// Telescope Prune
Schedule::command(
    \Laravel\Telescope\Console\PruneCommand::class,
    ['--hours' => 72]
)->dailyAt('02:00');

// Health Check
Schedule::command(
    \Spatie\Health\Commands\RunHealthChecksCommand::class
)->everyMinute();
