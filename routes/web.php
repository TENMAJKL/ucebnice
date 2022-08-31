<?php

use App\Controllers\Auth\Login;
use App\Controllers\Auth\Register;
use App\Controllers\Auth\Verify;
use App\Controllers\Home;
use App\Middlewares\Auth as AuthMiddleware;
use Lemon\Route;

Route::collection(function() {
    Route::controller('/', Home::class);
})->middleware([AuthMiddleware::class, 'onlyAuthenticated']);

Route::collection(function() {
    Route::controller('login', Login::class);
    Route::controller('register', Register::class);
    Route::controller('verify', Verify::class);
})->middleware([AuthMiddleware::class, 'onlyGuest']);
