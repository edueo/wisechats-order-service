<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use Laravel\Passport\Http\Middleware\EnsureClientIsResourceOwner;

Route::middleware([EnsureClientIsResourceOwner::using('orders:create', 'orders:read', 'orders:delete', 'orders:update')])->group(function () {
    Route::apiResource('orders', OrderController::class);
});
