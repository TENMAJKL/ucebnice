<?php

use App\Controllers\Auth;
use App\Controllers\Home;
use App\Middlewares\Auth as AuthMiddleware;
use Lemon\Kernel\Application;
use Lemon\Route;

Route::collection(function() {
    Route::get('/', [Home::class, 'handle']);
})->middleware([AuthMiddleware::class, 'onlyAuthenticated']);

Route::collection(function(Application $app) {
    Route::template('login');
    Route::get('register', 
        fn() => template('register', years: explode("\n", file_get_contents($app->file('years', 'txt'))))
    );

    Route::post('login', [Auth::class, 'login']);
    Route::post('register', [Auth::class, 'register']);

    Route::get('verify/{token}', [Auth::class, 'verify']);
})->middleware([AuthMiddleware::class, 'onlyGuest']);
