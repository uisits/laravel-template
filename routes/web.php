<?php

use Illuminate\Support\Facades\Route;
use UisIts\Oidc\Http\Controllers\AuthController;
use App\Http\Controllers\Filament\LogoutController;
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

//LIVEWIRE
Livewire::setScriptRoute(function($handle) {
		            return Route::get('/' . env('VITE_APP_NAME') . '/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function($handle) {
		            return Route::get('/' . env('VITE_APP_NAME') . '/livewire/update', $handle);
});

Route::name('login')->get('login', [AuthController::class, 'login']);
Route::name('callback')->get('/auth/callback', [AuthController::class, 'callback']);
Route::name('logout')->get('/logout', [AuthController::class, 'logout']);

