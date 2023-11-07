<?php

use App\Http\Controllers\Api\FeedbackApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::resource('/user', UserApiController::class);
    Route::resource('/feedback', FeedbackApiController::class);
});
