<?php

use App\Http\Controllers\CurrentRateController;
use Illuminate\Support\Facades\Route;

Route::get('/rate', CurrentRateController::class);
