<?php

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\EnsureClientIsResourceOwner;

Route::get('/orders', function () {
    return "orders";
})->middleware(EnsureClientIsResourceOwner::using('orders:create', 'orders:read', 'orders:delete', 'orders:update'));
