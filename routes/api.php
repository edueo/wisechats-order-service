<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use Laravel\Passport\Http\Middleware\EnsureClientIsResourceOwner;

Route::middleware('auth:api')->group(function () {
    Route::apiResource('orders', OrderController::class);
});

