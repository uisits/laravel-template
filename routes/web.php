<?php

use App\Http\Controllers\Filament\LogoutController;
use Illuminate\Support\Facades\Route;

Route::post('logout', [LogoutController::class, 'logout'])->name('filament.app.auth.logout');
