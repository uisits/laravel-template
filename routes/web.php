<?php

use App\Http\Controllers\Filament\LogoutController;
use Filament\Actions\Exports\Http\Controllers\DownloadExport;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::post('logout', [LogoutController::class, 'logout'])->name('filament.app.auth.logout');

if (app()->isLocal()) {
    /**
     * Override the url for Livewire javascript and update calls.
     */
    Livewire::setScriptRoute(function ($handle) {
        return Route::get('/' . config('app.base_name') . '/livewire/livewire.js', $handle)
            ->middleware(['web', 'auth']);
    });

    Livewire::setUpdateRoute(function ($handle) {
        return Route::get('/' . config('app.base_name') . '/livewire/update', $handle)
            ->middleware(['web', 'auth']);
    });

    /**
     * Override the url for filament-export package required to download generated files.
     */
    Route::get('/' . config('app.base_name') . '/filament/exports/{export}/download', DownloadExport::class)
        ->name('filament.exports.download')
        ->middleware(['web', 'auth']);
}
