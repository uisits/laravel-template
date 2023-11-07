<?php

use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use UisIts\Oidc\Http\Controllers\AuthController;

Route::name('login')->get('login', [AuthController::class, 'login']);

Route::name('callback')->get('/auth/callback', [AuthController::class, 'callback']);

Route::name('logout')->get('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function() {
    Route::get('/', function() {
        return view('home');
    })->name('home');

    Route::resource('/user', UserController::class);
    Route::resource('/impersonate', ImpersonateController::class)
        ->only(['store', 'destroy']);
    Route::get('/feedback', function() {
        return view('feedback.index');
    });
    Route::get('/help', function() {
        return view('help.index');
    });
});
