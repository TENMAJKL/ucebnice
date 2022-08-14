<?php

use App\Controllers\Auth;
use App\Controllers\Home;
use App\Middlewares\Auth as AuthMiddleware;
use Lemon\Route;

Route::collection(function() {
    Route::get('/', [Home::class, 'handle']);
})->middleware([AuthMiddleware::class, 'onlyAuthenticated']);

Route::collection(function() {
    Route::template('login');
    Route::template('register');

    Route::post('login', [Auth::class, 'login']);
    Route::post('login', [Auth::class, 'register']);
})->middleware([AuthMiddleware::class, 'onlyGuest']);
