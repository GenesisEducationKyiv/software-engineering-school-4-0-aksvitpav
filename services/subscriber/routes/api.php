<?php

use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\UnsubscribeController;
use Illuminate\Support\Facades\Route;

Route::post('/subscribe', SubscribeController::class);
Route::post('/unsubscribe', UnsubscribeController::class);
